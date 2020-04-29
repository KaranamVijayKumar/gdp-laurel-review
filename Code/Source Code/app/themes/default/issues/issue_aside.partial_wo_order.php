<a href="<?php echo action('\Project\Controllers\Issues\Index', array($issue->slug)) ?>">
    <img class="grid-list__img u-1-of-1" src="<?php echo issue_cover_page_url($issue) ?>" alt=""/>
</a>

<?php echo $engine->getSection('issue-aside TOC'); ?>
