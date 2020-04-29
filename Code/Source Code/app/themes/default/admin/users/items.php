<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d user.', '%d users.', $total), $total) ?></em></p>
        <ol class="item-list  pt">
        <?php
            foreach ($items as $item) { ?>
            <?php
                $url = action('\Project\Controllers\Admin\Users\Edit', array($item->id));
                $name = h($item->profile->findBy('name', 'name')->value) ?: $item->email;
                $last_login = $item->last_login ? \Carbon\Carbon::createFromTimestamp($item->last_login) : false;
                ?>
                <li class="item-list__item flag">
                    <div class="flag__img pl0 pr-">
                        <?php echo \Story\HTML::gravatar($item->email, 32, $name, 'mm') ?>
                    </div>
                    <div class="flag__body">
                        <?php echo \Story\HTML::link($url, $name, array('class' => 'item-list__title')) ?>
                        <p class="item-list__description">
                            <?php
                            echo sprintf(
                                _('Email: %s'),
                                \Story\HTML::link('mailto:' . $item->email, h($item->email))
                            ) ?>,
                            <span title="<?php echo $item->last_login ? $last_login->toDayDateTimeString() : '' ?>">
                                <?php
                                echo sprintf(
                                    _('Last login: %s'),
                                    $item->last_login ? $last_login->diffForHumans() : _('Never')
                                ) ?></span>,
                            <?php echo $item->active ? _('Active') : _('Inactive') ?>
                        </p>
                    </div>
                </li>
            <?php
            } ?>
        </ol>
        <div class="c"><?php echo $pagination ?></div>
    <?php
    } ?>
</div>
