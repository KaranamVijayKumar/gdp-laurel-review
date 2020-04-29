<?php
if ($chapbook) { ?>

    <!-- .featured-issue -->
    <div class="featured-issue u-mb">
        <div class="featured-issue__title">
            <?php echo $chapbook->title ?>
        </div>
        <div class="featured-issue__body">
            <a href="<?php echo action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug)) ?>">
                <img src="<?php echo chapbook_cover_page_url($chapbook) ?>" alt="" class="featured-issue__img">

            </a>
            <?php
            echo has_access('chapbooks_order') ? link_to(
                action('\Project\Controllers\Chapbooks\Order', array($chapbook->slug)),
                _('Order Now'),
                array('class' => 'btn btn--alert')
            ) : ''?>
        </div>
    </div><!-- /.featured-issue -->
    
<?php } ?>
