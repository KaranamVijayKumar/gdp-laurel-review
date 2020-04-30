<?php
/**
 * File: SnippetsSeeder.php
 * Created: 31-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Seeds;

use Html2Text\Html2Text;
use Project\Models\Snippet;

class SnippetsSeeder
{
    /**
     * @var \Story\DB
     */
    public $db;

    protected $table;

    protected $created;

    public function __construct()
    {
        $this->table = Snippet::getTable();
        $this->created = time();
    }

    public function run()
    {
        // Delete all from the users table
        $this->db->query('DELETE FROM ' . $this->db->i($this->table));

        // global feature: submissions
        $content = file_get_contents(__DIR__ . '/stubs/snippet.global-features-submissions.html');
        $content_text = new Html2Text($content);
        $this->db->insert(
            $this->table,
            array(
                'slug' => 'global-features-submissions',
                'description' => "The welcome page submission feature content",
                'content' => $content,
                'content_text' => $content_text->getText(),
                'status' => '1',
                'created' => $this->created
            )
        );
        // global features: subscriptions
        $content = file_get_contents(__DIR__ . '/stubs/snippet.global-features-subscriptions.html');
        $content_text = new Html2Text($content);
        $this->db->insert(
            $this->table,
            array(
                'slug' => 'global-features-subscriptions',
                'description' => "The welcome page subscription feature content",
                'content' => $content,
                'content_text' => $content_text->getText(),
                'status' => '1',
                'created' => $this->created
            )
        );
        // global features: issues
        $content = file_get_contents(__DIR__ . '/stubs/snippet.global-features-issues.html');
        $content_text = new Html2Text($content);
        $this->db->insert(
            $this->table,
            array(
                'slug' => 'global-features-issues',
                'description' => "The welcome page issues feature content",
                'content' => $content,
                'content_text' => $content_text->getText(),
                'status' => '1',
                'created' => $this->created
            )
        );

        // helpful links
        // global features: issues
        $content = file_get_contents(__DIR__ . '/stubs/snippet.helpful-links.html');
        $content_text = new Html2Text($content);
        $this->db->insert(
            $this->table,
            array(
                'slug' => 'helpful-links',
                'description' => "Helpful links",
                'content' => $content,
                'content_text' => $content_text->getText(),
                'status' => '1',
                'created' => $this->created
            )
        );

    }
}
