<?php
/*!
 * article.php v0.1
 * http://arpadolasz.com
 *
 * Copyright 2015 Arpad Olasz
 * All rights reserved.
 *
 */

include __DIR__ . '/sections.php';
require_once __DIR__ . '/../issues/sections.php';

// --------------------------------------------------------------
// Title
// --------------------------------------------------------------
if (!isset($title)) {
//    $title = h($article->sections['content']->title);
    $title = _('News');
}
// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<div class="archive">
    <article class="archive__article">
        <?php
        echo $engine->getSection('news-article-headline');
        echo $engine->getSection('news-article-content');
        ?>
    </article>
</div>
<?php
echo $engine->getSection('news-article-footer');
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_content_aside
// --------------------------------------------------------------
ob_start();
echo $engine->getSection('news-article-aside');
echo $engine->getSection('aside-last-issue');
echo $engine->getSection('other-news');

$global_content_aside = ob_get_clean();

// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
// extra head

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
// extra footer

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// $breadcrumbs
// --------------------------------------------------------------
$breadcrumbs = array(
    \Story\HTML::link('', _('Home')),
    \Story\HTML::link(action('\Project\Controllers\News\Index'), _('News')),
    \Story\HTML::link(
        action('\Project\Controllers\News\Show', array($article->slug)),
        h($article->sections['content']->title)
    )
);

// --------------------------------------------------------------
// Overrides
// --------------------------------------------------------------
$palm_hidden = 1;

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------
include __DIR__ . '/../_nav/sections.php';
include __DIR__ .'/../_masters/page.master.php';
