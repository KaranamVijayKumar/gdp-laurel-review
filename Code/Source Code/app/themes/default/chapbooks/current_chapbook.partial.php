<?php

if (!isset($chapbook) || !$chapbook) {
    return;
}

$cover_page = chapbook_cover_page_url($chapbook);

$chapbook_link = action('\Project\Controllers\Chapbooks\Index', array($chapbook->slug));
?>
<!-- .hero -->
<div class="hero hero--secondary">
    <!-- .hero-issue -->
    <div class="flag flag--responsive hero-issue u-1-of-1 container">
        <div class="flag__img hero-issue__img">
            <a href="<?php echo $chapbook_link ?>" style="background-image: url('<?php echo $cover_page ?>')"></a>
        </div>
        <div class="flag__body hero-issue__body">
            <h2><?php echo \Story\HTML::link($chapbook_link, h($chapbook->title)) ?></h2>
            <div class="hero-issue__description">
                <?php echo $chapbook->content ?>
            </div>
        </div>
    </div><!-- /.hero-issue -->
</div><!-- /.hero -->
