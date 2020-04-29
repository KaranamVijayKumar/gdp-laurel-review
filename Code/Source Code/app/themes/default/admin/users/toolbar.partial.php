<div class="flag__body">
    <div class="flag flag--small flag--rev">
        <div class="flag__img">
            <?php echo \Story\HTML::gravatar($user->email, 32, '', 'mm'); ?>
        </div>
        <div class="flag__body gamma pv-">
            <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Users\Index'), $title) ?>
            /
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Users\Edit', array($user->id)),
                h($user->profiles->findBy('name', 'name')->value ?: $user->email)
            ) ?>
            /
            <?php echo $user_subtitle; ?>
        </div>
    </div>
</div>
