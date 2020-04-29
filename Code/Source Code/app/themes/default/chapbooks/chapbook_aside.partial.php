<a href="<?php echo action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)) ?>">
    <img class="grid-list__img u-1-of-1" src="<?php echo chapbook_cover_page_url($chapbook) ?>" alt=""/>
</a>
<?php
echo has_access('chapbooks_order') ?  $chapbook->inventory ? \Story\HTML::link(
    action('\Project\Controllers\Chapbooks\Order', array($chapbook->slug)),
    _('Order Now'),
    array('class' => 'btn btn--alert mt- u-1-of-1')
) : _('Out of Stock') : '' ?>

<?php echo $engine->getSection('chapbook-aside TOC'); ?>
