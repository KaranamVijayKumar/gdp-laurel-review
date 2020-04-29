<?php

if (!isset($issue) || !$issue) {
    return;
}

$cover_page = issue_cover_page_url($issue);

$issue_link = action('\Project\Controllers\Issues\Index', array($issue->slug));
?>
<!-- .hero -->
<div class="hero hero--secondary">
    <!-- .hero-issue -->
    <div class="flag flag--responsive hero-issue u-1-of-1 container">
        <div class="flag__img hero-issue__img">
            <a href="<?php echo $issue_link ?>" style="background-image: url('<?php echo $cover_page ?>')"></a>
        </div>
        <div class="flag__body hero-issue__body">
            <h2><?php echo \Story\HTML::link($issue_link, h($issue->title)) ?></h2>
            <div class="hero-issue__description">
                <?php echo $issue->content ?>
            </div>
        </div>
    </div><!-- /.hero-issue -->
</div><!-- /.hero -->
