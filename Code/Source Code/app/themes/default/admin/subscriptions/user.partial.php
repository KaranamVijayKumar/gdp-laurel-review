<div class="flag">
    <div class="flag__img">
        <?php echo \Story\HTML::gravatar($user->email, 48, $user_name, 'mm') ?>
    </div>
    <div class="flag__body">
        <?php
        if (has_access('admin_users_edit')) { ?>
            <span class="i-user mb0 gray">
                    <?php
                    echo \Story\HTML::link(
                        action('\Project\Controllers\Admin\Users\Edit', array($user->id)),
                        $user_name
                    ) ?>
                </span>
            <br>
            <span class="i-envelope-o gray">
                    <?php echo \Story\HTML::link('mailto:' . $user->email, $user->email) ?>
                </span>
        <?php
        } else { ?>
            <span class="i-user mb0">
                    <?php echo $user_name ?>
                </span>
        <?php
        } ?>

    </div>
</div>
