<a href="<?php echo action('\Project\Controllers\Issues\Index', array($issue->slug)) ?>">
    <img class="grid-list__img u-1-of-1" src="<?php echo issue_cover_page_url($issue) ?>" alt=""/>
</a>
<?php
echo has_access('issues_order') ? $issue->inventory ? \Story\HTML::link(
    action('\Project\Controllers\Issues\Order', array($issue->slug)),
    _('Order Now'),
    array('class' => 'btn btn--alert mt- u-1-of-1')
) : _('Out of Stock') : '' ?>

<?php echo $engine->getSection('issue-aside TOC'); ?>
