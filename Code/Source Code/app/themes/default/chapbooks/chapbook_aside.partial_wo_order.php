<a href="<?php echo action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)) ?>">
    <img class="grid-list__img u-1-of-1" src="<?php echo chapbook_cover_page_url($chapbook) ?>" alt=""/>
</a>

<?php echo $engine->getSection('chapbook-aside TOC'); ?>
