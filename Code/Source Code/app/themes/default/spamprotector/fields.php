<?php echo \Story\Form::label('sp-response', _('Enter the following with numbers (spaces allowed)')) ?>

<nav class="">
    <ol class="nav breadcrumb--path text--alert">
        <?php echo implode(', ', $sp_html_keys) ?>
    </ol>
</nav>

<?php echo \Story\Form::text('sp-response', '', array('class' => 'text-input u-1-of-1', 'id' => 'sp-response')) ?>

<small class="extra-help pb"><?php echo sprintf(_('For date related entries use: %s'), $sp_date) ?></small>

<input name="sp-challenge" type="hidden" value="<?php echo $sp_challenge ?>"/>

