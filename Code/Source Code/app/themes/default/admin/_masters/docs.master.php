<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0">

    <title><?php echo $title .' - ' . _('Help &amp; Docs') ?></title>

    <link rel="stylesheet" href="<?php echo to('themes/default/admin/global/style.css') ?>">

    <?php
    if (isset($extra_head)) {
        echo $extra_head;
    }
    ?>
</head>
<body>
<div class="container">

    <div class="content content--toolbar m- pv--">
        <div class="media media--rev">
            <div class="media__body">
                <h4 class="mt-- c gray"><?php echo $title ?></h4>
                <?php echo $global_content ?>
            </div>
        </div>
    </div>
</div>
<footer>
</footer>
<script src="<?php echo to('themes/default/admin/vendor/jquery.min.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/global/plugins.js') ?>"></script>
<script src="<?php echo to('themes/default/admin/global/main.js') ?>"></script>
</body>
</html>
