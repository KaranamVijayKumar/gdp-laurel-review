<li class="issues-toc__templates-content 1/1">
    <div class="flag flag--small">
        <div class="flag__body">
            <div class="media media--responsive">
                <div class=" 1/1 pv--">
                    <strong>
                        <?php echo $item->content ?>
                    </strong>
                </div>
                <div class="1/1">
                    <ul class="nav 1/1 nav--striped nav--stacked m0">

                        <?php if (count($item->titles)) { ?>

                            <?php foreach ($item->titles as $chapbook_content_title) { ?>

                                <li class="1/1 p--">

                                    <div class="flag flag--small flag--rev">
                                        <div class="flag__img nowrap">
                                            <?php if ($chapbook_content_title->linked_content) { ?>

                                                <?php echo \Story\Form::open(array('method' => 'delete', 'class' => 'filter', 'action' => action('\Project\Controllers\Admin\Chapbooks\ContentEdit', array($chapbook->id, $chapbook_content_title->linked_content->id)))) ?>
                                                <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Chapbooks\ContentEdit', array($chapbook->id, $chapbook_content_title->linked_content->id)), '', array('class' => 'btn btn--small i-pencil btn--secondary ph-', 'title' => _('Edit content'))) ?>
                                                    <?php echo \Story\Form::button('', array('type' => 'submit', 'class' => 'btn btn--small btn--secondary  i-trash-o confirm ph- action--negative', 'title' => _('Remove content'), 'data-confirm' => _('This will remove the content associated with this title.'))) ?>
                                                <?php echo \Story\Form::close() ?>

                                            <?php } else { ?>

                                                <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Chapbooks\ContentCreate', array($chapbook->id, $chapbook_content_title->id)), '', array('class' => 'btn btn--small btn--secondary i-edit ph-', 'title' => _('Create content'))) ?>


                                            <?php } ?>
                                        </div>
                                        <div class="flag__body">
                                            <div class="media media--small media--responsive">
                                                <?php if ($chapbook_content_title->linked_content && $chapbook_content_title->linked_content->highlight) { ?>
                                                    <span class="i-tag orange" title="<?php echo _('Included in the hightlights') ?>"></span>
                                                <?php } else { ?>
                                                    <span class="i-tag gray" title="<?php echo _('Not included in the hightlights') ?>"></span>
                                                <?php } ?>
                                                <?php echo $chapbook_content_title->content ?>
                                            </div>

                                        </div>
                                    </div>
                                </li>

                            <?php } ?>

                        <?php } ?>

                    </ul>

                </div>
            </div>

        </div>
    </div>

</li>
