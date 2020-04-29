<li class="js-issues-toc__templates-content  issues-toc__templates-content js-issues-toc__entry 1/1"
    data-id="<?php echo $item->id ?>">
    <input name="toc-order[<?php echo $item->id ?>]" type="hidden" class="js-issues-toc__order"
           value="<?php echo $item->order ?>"/>
    <input name="toc-is_header[<?php echo $item->id ?>]" type="hidden" value="0"/>

    <div class="flag flag--small">
        <div class="flag__img issues-toc-actions">
            <a href="#" class="js-issues-toc__moveUpEntry issues-toc-action i-chevron-up"
               title="<?php echo _('Move up author') ?>"></a>
            <a href="#" class="js-issues-toc__moveDownEntry issues-toc-action i-chevron-down"
               title="<?php echo _('Move down author') ?>"></a>
            <a href="#" class="js-issues-toc__removeEntry issues-toc-action i-times red confirm"
               title="<?php echo _('Remove author') ?>"></a>
        </div>
        <div class="flag__body">
            <div class="media media--responsive">
                <div class="media__img 1/4 palm-1/1 lap-1/1">
                    <input type="text" name="toc-content[<?php echo $item->id ?>]" class="text-input 1/1"
                           placeholder="<?php echo _('Author') ?>" value="<?php echo h($item->content) ?>"/>
                </div>
                <div class="media__body">
                    <ul class="js-issues-toc__titles nav 1/1 m0">
                        <?php
                        if (count($item->titles)) { ?>

                            <?php
                                foreach ($item->titles as $issue_content_title) { ?>

                                    <li class="js-issues-toc__title 1/1" data-id="<?php echo $issue_content_title->id ?>">
                                        <input name="toc_titles-order[<?php echo $item->id ?>][<?php echo $issue_content_title->id ?>]"
                                               type="hidden" class="js-issues-toc__title-order"
                                               value="<?php echo $issue_content_title->order ?>"/>

                                        <div class="flag flag--small">
                                            <div class="flag__img issues-toc-actions issues-toc-actions--small">
                                                <a href="#"
                                                   class="js-issues-toc__moveUpTitle issues-toc-action i-chevron-up"
                                                   title="<?php echo _('Move up title') ?>"></a>
                                                <a href="#"
                                                   class="js-issues-toc__moveDownTitle issues-toc-action i-chevron-down"
                                                   title="<?php echo _('Move down title') ?>"></a>
                                                <a href="#"
                                                   class="js-issues-toc__removeTitle issues-toc-action i-times red confirm"
                                                   title="<?php echo _('Remove title') ?>"></a>
                                            </div>
                                            <div class="flag__body">
                                                <div class="media media--small media--responsive">
                                                    <input type="text"
                                                           name="toc_titles-content[<?php echo $item->id ?>][<?php echo $issue_content_title->id ?>]"
                                                           class="text-input 1/1" placeholder="<?php echo _('Title') ?>"
                                                           value="<?php echo h($issue_content_title->content) ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                            <?php
                                } ?>

                        <?php
                        } ?>
                    </ul>
                    <div class="flag flag--small">
                        <div class="flag__img issues-toc-actions issues-toc-actions--small pr-">
                            <a href="#" class="js-issues-toc__addTitle i-plus"
                               title="<?php echo _('Add another title') ?>"></a>
                        </div>
                        <div class="flag__body">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
