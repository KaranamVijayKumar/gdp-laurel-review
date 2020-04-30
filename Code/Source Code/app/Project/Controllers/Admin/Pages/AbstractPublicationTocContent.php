<?php
/**
 * File: AbstractPublicationTocContent.php
 * Created: 25-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Html2Text\Html2Text;
use Project\Support\Publications\TocContentCollection;
use Story\Collection;
use Story\HTML;
use Story\ORM;

abstract class AbstractPublicationTocContent extends ORM
{
    /**
     * @var string
     */
    public static $type = 'publication';

    /**
     * @var string
     */
    public static $action = '\Project\Controllers\Publication\TocContent';

    /**
     * @param $items
     *
     * @return TocContentCollection
     */
    public static function collection($items)
    {

        return new TocContentCollection($items);
    }

    /**
     * Creates a new title content from user data form
     *
     * @param array                       $data
     * @param AbstractPublicationToc      $toc
     * @param AbstractPublicationTocTitle $toc_title
     *
     * @return static
     */
    public static function createFromForm(
        array $data,
        AbstractPublicationToc $toc,
        AbstractPublicationTocTitle $toc_title
    ) {

        // create the slug
        $slug = slug($toc->content . '-' . $toc_title->content);

        // create the text
        $text = new Html2Text($data['content']);
        $model = new static;

        $model->set(
            array(
                static::$type . '_id'           => $toc->{static::$type . '_id'},
                static::$type . '_toc_title_id' => $toc_title->id,
                'slug'                          => $slug,
                'status'                        => (int)$data['status'],
                'highlight'                     => isset($data['highlight']) ? (int)$data['highlight'] : null,
                'content'                       => $data['content'],
                'content_text'                  => $text->getText()
            )
        );

        return $model->save();
    }

    /**
     * Returns random highlights for the publications
     *
     * @param Collection $publications
     * @param int        $content_limit
     *
     * @return array
     */
    public static function getRandomHighlighted(Collection $publications, $content_limit = 3)
    {

        /** @var AbstractPublication $publication */
        $publication = $publications->first();

        if (!$publication) {
            return array();
        }

        /** @var AbstractPublicationTocContent $toc_content_repo */
        $toc_content_repo = $publication::$publication_toc_content_repository;
        /** @var AbstractPublicationToc $toc_repo */
        $toc_repo = $publication::$publication_toc_repository;
        /** @var AbstractPublicationTocTitle $toc_title_repo */
        $toc_title_repo = $publication::$publication_toc_title_repository;

        $ids = $publications->lists('id');
        // we get a few contents for each publication at random
        // sql template:
        // SELECT
        //     itc.*,
        //     `issue_toc_titles`.`content` as title,
        //     `issue_toc`.`content` as author
        // FROM (
        //     (select * from issue_toc_contents where issue_id = 45 order by rand() limit 2)
        //     union all
        //     (select * from issue_toc_contents where issue_id = 46 order by rand() limit 2)
        // ) as itc
        // inner join `issue_toc_titles` on `itc`.`issue_toc_title_id` = `issue_toc_titles`.`id`
        // inner join `issue_toc` on `issue_toc`.`id` = `issue_toc_titles`.`issue_toc_id`
        // ;
        $i = static::$db->quoteIdentifier;
        $toc_contents = static::$db->i($toc_content_repo::getTable());
        $toc = static::$db->i($toc_repo::getTable());
        $toc_titles = static::$db->i($toc_title_repo::getTable());


        // Build the sub-queries
        $subqueries = array();
        foreach ($ids as $id) {
            $subqueries[] = "(SELECT * " .
                "FROM {$toc_contents} ".
                " WHERE {$i->{static::$type . '_id'}} = {$id} ORDER BY RAND() LIMIT {$content_limit})";
        }
        $subqueries = implode("\n\tUNION ALL\n\t", $subqueries);
        // Build the content query
        $sql = "\nSELECT " .
            "\n\t{$i->itc}.*," .
            "\n\t{$i->get($toc_titles . '.content')} as title," .
            "\n\t{$i->get($toc . '.content')} as author" .
            "\nFROM (\n\t{$subqueries}\n) as {$i->itc}" .
            "\nINNER JOIN {$toc_titles} ON ".
                "{$i->get('itc.'.static::$type.'_toc_title_id')} = {$i->get($toc_titles .'.id')}" .
            "\nINNER JOIN {$toc} ON {$i->get($toc .'.id')} = {$i->get($toc_titles . '.'.static::$type.'_toc_id')}" .
            "\nWHERE {$i->itc}.{$i->highlight} = '1' AND {$i->itc}.{$i->status} = '1'";

        return static::$db->fetch($sql);
    }

    /**
     * Sets the title links for the passes title id's
     *
     * @param                     $toc_title_ids
     * @param AbstractPublication $publication
     *
     * @param int|null            $status
     *
     * @return bool
     */
    public static function setTitleLinks($toc_title_ids, AbstractPublication $publication, $status = 1)
    {

        if (!count($toc_title_ids)) {
            return false;
        }
        $db = static::$db;

        $select = array(
            $publication::TYPE . '_id' => $publication->id,
            "{$db->i($publication::TYPE . '_toc_title_id')} IN (" . implode(', ', $toc_title_ids) . ")"
        );

        if ($status !== null) {
            $select['status'] = $status;
        }
        $contents = new Collection(static::select('fetch', '*', null, $select));

        $content_array = $contents->lists($publication::TYPE . '_toc_title_id', 'slug');


        // get the author and toc ids
        foreach ($publication->toc as $toc) {
            if ($toc->is_header || !count($toc->titles)) {
                continue;
            }
            foreach ($toc->titles as $title) {
                if (array_key_exists($title->id, $content_array)) {

                    $title->link = HTML::link(
                        action(
                            static::$action,
                            array($publication->slug, $content_array[$title->id])
                        ),
                        $title->content
                    );
                    $title->linked_content = $contents->findBy($publication::TYPE . '_toc_title_id', $title->id);
                }
            }
        }

        return true;
    }

    /**
     * Updates the content from user form
     *
     * @param array $data
     *
     * @return $this
     */
    public function updateFromForm($data)
    {

        $text = new Html2Text($data['content']);
        $this->set(
            array(
                'status'       => (int)$data['status'],
                'highlight'    => isset($data['highlight']) ? (int)$data['highlight'] : null,
                'content'      => $data['content'],
                'content_text' => $text->getText()
            )
        );

        return $this->save();
    }
}
