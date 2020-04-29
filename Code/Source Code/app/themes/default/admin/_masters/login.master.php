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
<body class="login">
<header class="global-header mt mb--">
    <div class="media--rev c">
        <h1 class="m0 i-story">Story</h1>
    </div>
</header>
<div class=" container">
    <?php echo $global_content ?>
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
