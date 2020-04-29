<?php

// --------------------------------------------------------------
// $global_content
// --------------------------------------------------------------
ob_start();
?>
<div class="mv--">
    <?php echo $content ?>
</div>

<?php
$global_content = ob_get_clean();

// --------------------------------------------------------------
// $global_toolbar
// --------------------------------------------------------------
ob_start();
?>
<div class="flag__body pl- pt--">
    <div class="flag flag--small flag--responsive ">
        <div class="flag__body zeta pv--">
            <?php echo $title ?>
        </div>
    </div>
</div>
<?php
$global_toolbar = ob_get_clean();
// --------------------------------------------------------------
// $extra_head
// --------------------------------------------------------------
ob_start();
// extra head

$extra_head = ob_get_clean();

// --------------------------------------------------------------
// $extra_footer
// --------------------------------------------------------------
ob_start();
 // extra footer

$extra_footer = ob_get_clean();

// --------------------------------------------------------------
// Includes
// --------------------------------------------------------------

include __DIR__ .'/../_masters/docs.master.php';
