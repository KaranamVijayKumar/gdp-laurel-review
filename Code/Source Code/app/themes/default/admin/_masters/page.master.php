<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0">

    <title><?php echo $title ?></title>

    <link rel="stylesheet" href="<?php echo to('themes/default/admin/global/style.css') ?>">

    <link rel="apple-touch-icon" href="<?php echo to('themes/default/admin/global/apple-touch-icon.png') ?>">
    <link rel="shortcut icon" href="<?php echo to('themes/default/global/favicon.ico') ?>" />

    <?php
    if (isset($extra_head)) {
        echo $extra_head;
    }
    ?>
</head>
<body>
<header class="global-header">
    <div class="media media--rev">
        <nav class="media__img nav--user">
            <?php
            echo \Project\Support\MenuFactory::get('admin-menu-user', array(), array('class' => 'nav nav--block')) ?>
        </nav>

        <div class="media__body">
            <h1 class="m0 i-story"><a href=""><?php echo $title ?></a></h1>
        </div>
    </div>
    <div class="toolbar flag flag--rev">

        <?php echo $global_toolbar ?>

    </div>
</header>
<div class="container">
    <a class="nav--main__handle i-navicon"></a>
    <nav class="nav nav--main">
        <?php
        echo \Project\Support\MenuFactory::get(
            'admin-menu-main',
            isset($selected) ? $selected : array(),
            array('class' => 'list-ui list-ui--small')
        ) ?>

    </nav>
    <div class="content content--toolbar">
        <div class="media media--rev">
            <div class="media__body">
                <?php echo $global_content ?>
            </div>
        </div>
    </div>
</div>
<footer>
</footer>
<?php require __DIR__ .'/../_partials/notifications.partial.php' ?>
<script src="<?php echo to('themes/default/admin/vendor/jquery.min.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/global/plugins.js') ?>"></script>
<?php
if (isset($extra_footer)) {
    echo $extra_footer;
}
?>
<script src="<?php echo to('themes/default/admin/global/main.js') ?>"></script>
</body>
</html>
