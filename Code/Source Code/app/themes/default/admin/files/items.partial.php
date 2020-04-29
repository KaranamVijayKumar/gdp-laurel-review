<div id="results">
    <?php
    if (!count($items)) { ?>
        <p><em><?php echo _('No results.') ?></em></p>
    <?php
    } else { ?>
        <p class="mb0">
            <em><?php echo sprintf(ngettext('%d file.', '%d files.', $total), $total) ?></em>
        </p>
        <ol class="item-list pt">
    <?php
        /** @var \Project\Models\PublicAsset $item */
        /** @var \Story\Pagination $pagination */
        foreach ($items as $item) { ?>
                <?php
                $url =  action('\Project\Controllers\Admin\Files\Edit', array($item->id));

                ?>
                <li class="item-list__item flag mb-">
                    <div class="flag__img ph-- <?php echo $item->status ? '' : 'gray'?>">
                        <?php
                        echo $item->getPreview(
                            'i-',
                            array(
                                'style' => 'max-width:28px;padding:2px;border-width:2px',
                                'class' =>'generic-img',
                                'title' => $item->name
                            )
                        ) ?>
                    </div>
                    <div class="flag__body">
                        <div class="media media--rev media--responsive">
                            <div class="media__img">
                                <span class=" <?php echo $item->status ? 'green' : 'red'?>">
                                    <?php echo $item->status ? _('Enabled') : _('Disabled') ?>
                                </span>
                            </div>
                            <div class="media__body item-list__title">
                                <?php
                                echo \Story\HTML::link(
                                    $url,
                                    h($item->name)
                                ) ?>
                            </div>

                        </div>
                        <div class="item-list__description">
                            <?php echo $item->getLink(array('class' => 'gray i-globe')) ?>
                        </div>
                    </div>
                </li>
    <?php
        } ?>

        </ol>
        <div class="c"><?php echo $pagination ?></div>
        <p class="mv gray c">
            <small>
                <?php
                echo _(
                    "To manage a file click on the filename. ".
                    "To view a file, select the <strong class=\"i-globe\"> globe icon</strong> under the name. ".
                    "Greyed out file icons are disabled files."
                ) ?>
            </small>
        </p>
    <?php
    } ?>
</div>
