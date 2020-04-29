<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <ol class="item-list pt">
            <?php
                foreach ($items as $item) { ?>
                <?php $url = action('\Project\Controllers\Admin\Roles\Edit', array($item->id)); ?>
                    <li class="item-list__item flag">
                        <div class="flag__body">
                            <div class="media media--responsive">
                                <div class="media__img">
                                    <span title="<?php
                                    echo _($item->locked ? 'Default role' : 'Custom role') ?>" class="<?php
                                    echo $item->locked ? 'i-lock' : 'i-unlock green' ?>"></span>
                                </div>
                                <?php
                                echo \Story\HTML::link(
                                    $url,
                                    h($item->name),
                                    array('class' => 'item-list__title media__body')
                                ) ?>
                            </div>
                            <p class="item-list__description"></p>
                        </div>
                    </li>
            <?php
                } ?>
        </ol>
        <div class="c"><?php echo $pagination ?></div>
    <?php
    } ?>
</div>
