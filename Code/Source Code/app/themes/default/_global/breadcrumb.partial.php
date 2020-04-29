<!-- .breadcrumb -->
<ol class="nav  breadcrumb container u-mb- content-hero">
    <?php foreach ($breadcrumbs as $index => $breadcrumb) { ?>

        <li <?php echo $index + 1 === count($breadcrumbs) ? 'class="current"':'' ?>>
            <?php echo $breadcrumb ?>
        </li>

    <?php } ?>
</ol><!-- /.breadcrumb -->
