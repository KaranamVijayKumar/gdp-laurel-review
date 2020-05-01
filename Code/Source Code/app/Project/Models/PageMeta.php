<?php
/**
 * File: PageMeta.php
 * Created: 24-08-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Models;

use Story\Collection;
use Story\ORM;

/**
 * Class PageMeta
 *
 * @package Project\Models
 */
class PageMeta extends ORM
{

    /**
     * @var array
     */
    public static $belongs_to = array(
        'page' => '\Project\Models\Page',
    );

    /**
     * @var bool
     */
    public static $timestamps = false;

    /**
     * @var string
     */
    protected static $table = 'page_meta';

    /**
     * Updates the meta for the page
     *
     * @param Page $page
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public static function updateForPage(Page $page, array $data)
    {
        // load the meta for the page if not loaded
        if (!$page->meta instanceof Collection) {
            $page->related('meta', null, 0, 0, array('name' => 'asc'))->load();
        }

        // delete removed ones
        self::deleteRemoved($page, $data);

        // Insert or update the meta
        static::pushCustomMeta($page, $data);

        // Save the models
        $result = array();
        foreach ($page->meta as $meta) {

            /** @var PageMeta $meta */
            $result[] = $meta->save();
        }

        return $result;
    }

    /**
     * Inserts the user defined custom meta when the user entered some extra meta elements
     * @param Page $page
     * @param array $input
     */
    public static function pushCustomMeta(Page $page, array $input)
    {

        // Check if we have meta name and content
        if (isset($input['meta_name']) && isset($input['meta_content'])) {

            $input['meta_name'] = array_unique($input['meta_name']);
            foreach ($input['meta_name'] as $key => $value) {
                // Spin through the meta names and add them as new models
                $model = $page->meta->findBy(
                    'id',
                    (string)$key,
                    new PageMeta(
                        array(
                            'value' => '',
                        )
                    )
                );

                // set the value
                $model->value = isset($input['meta_content'][$key]) ? $input['meta_content'][$key] : $model->value;

                // set the id if not set
                if (!isset($model->attributes['id'])) {
                    $model->id = null;
                    $model->page_id = $page->id;
                    $model->name = $value;
                    $page->meta->push($model);
                }

            }
        }
    }

    /**
     * Deletes the models not present in the data
     *
     * @param Page $page
     * @param array $data
     */
    protected static function deleteRemoved(Page $page, array $data)
    {
        // we first delete the those that were removed and not in the data but in the meta relations
        $existing_ones = array_unique($page->meta->lists('id'));
        $existing_ones = array_map('intval', $existing_ones);
        $updated_ones = array_keys(isset($data['meta_name']) ? $data['meta_name'] : array());

        foreach (array_diff($existing_ones, $updated_ones) as $id) {

            /** @var PageMeta $model */
            $model = $page->meta->findBy('id', (string)$id);

            if ($model) {
                $page->meta->forget($id);
                $model->delete();

            }
        }
    }
}
