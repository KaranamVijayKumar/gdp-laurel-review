<?php
/**
 * File: PagesContentSeeder.php
 * Created: 19-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Html2Text\Html2Text;
use Project\Models\PageContent;

class PagesContentSeeder
{
    /**
     * @var \Story\DB
     */
    public $db;


    /**
     *
     */
    public function run()
    {
        $tbl = PageContent::getTable();

        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i($tbl));

        foreach (config('languages') as $lang) {

            // --------------------------------------------------------------
            // about
            // --------------------------------------------------------------

            $content = file_get_contents(__DIR__ . '/stubs/pages.about.html');
            $content_text = new Html2Text($content);
            $this->db->insert(
                $tbl,
                array(

                    'page_id' => 1,
                    'name'    => 'content',
                    'locale'   => $lang,
                    'title' => 'About Us',
                    'content' => $content,
                    'content_text' => $content_text->getText()
                )
            );

            // --------------------------------------------------------------
            // contact
            // --------------------------------------------------------------

            $content = file_get_contents(__DIR__ . '/stubs/pages.contact.html');
            $content_text = new Html2Text($content);
            $this->db->insert(
                $tbl,
                array(
                    'page_id' => 2,
                    'name'    => 'content',
                    'locale'   => $lang,
                    'title' => 'Contact Us',
                    'content' => $content,
                    'content_text' => $content_text->getText()
                )
            );

            $content = file_get_contents(__DIR__ . '/stubs/pages.contact.aside.html');
            $content_text = new Html2Text($content);
            $this->db->insert(
                $tbl,
                array(
                    'page_id' => 2,
                    'name'    => 'aside',
                    'locale'   => $lang,
                    'title' => 'Contact Us',
                    'content' => $content,
                    'content_text' => $content_text->getText()
                )
            );

            // --------------------------------------------------------------
            // submissions
            // --------------------------------------------------------------
            $content = file_get_contents(__DIR__ . '/stubs/pages.submissions.html');
            $content_text = new Html2Text($content);
            $this->db->insert(
                $tbl,
                array(
                    'page_id' => 3,
                    'name'    => 'content',
                    'locale'   => $lang,
                    'title' => 'Submissions',
                    'content' => $content,
                    'content_text' => $content_text->getText()
                )
            );

            // --------------------------------------------------------------
            // subscriptions
            // --------------------------------------------------------------
            $content = file_get_contents(__DIR__ . '/stubs/pages.subscriptions.html');
            $content_text = new Html2Text($content);
            $this->db->insert(
                $tbl,
                array(
                    'page_id' => 4,
                    'name'    => 'content',
                    'locale'   => $lang,
                    'title' => 'Subscriptions',
                    'content' => $content,
                    'content_text' => $content_text->getText()
                )
            );

        }



    }
}
