<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0"><em><?php echo sprintf(ngettext('%d template.', '%d templates.', $total), $total) ?></em></p>
        <ol class="item-list  pt">
        <?php
            foreach ($items as $item) { ?>
                <?php
                $url = action('\Project\Controllers\Admin\Templates\Edit', array($item->id));
                $name = h($item->description);

                ?>
                <li class="item-list__item flag">
                    <div class="flag__body">
                <?php
                        if ($item->locked) { ?>
                            <span class="i-lock gray" title="<?php echo _('Default template') ?>">&nbsp;</span>
                <?php
                        } else { ?>
                            <span class="i-unlock green" title="<?php echo _('Custom template') ?>"></span>
                        <?php
                        } ?>
                        <?php echo \Story\HTML::link($url, $name, array('class' => 'item-list__title')) ?>
                        <div class="item-list__description">
                            <em>
                                <?php echo sprintf(_('ID: %s'), $item->type .'.' .$item->name) ?>
                            </em>
                            <br>
                            <span class="btn btn--tiny btn--secondary btn--disabled"><?php echo $item->type ?></span>

                        </div>

                    </div>
                </li>
            <?php
            } ?>
        </ol>
        <div class="c"><?php echo $pagination ?></div>
    <?php
    } ?>
</div>
