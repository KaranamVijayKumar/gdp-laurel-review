<!-- templates -->
<ul class="js-issues-toc__templates  issues-toc__templates">

    <!-- header -->
    <li class="js-issues-toc__templates-header issues-toc__templates-header 1/1" data-id="">
        <input name="toc-order[%]" type="hidden" class="js-issues-toc__order" value="0" disabled/>
        <input name="toc-is_header[%]" type="hidden" value="1" disabled/>

        <div class="flag flag--small">
            <div class="flag__img issues-toc-actions">
                <a href="#" class="js-issues-toc__moveUpEntry issues-toc-action i-chevron-up"
                   title="<?php echo _('Move up heading') ?>"></a>
                <a href="#" class="js-issues-toc__moveDownEntry issues-toc-action i-chevron-down"
                   title="<?php echo _('Move down heading') ?>"></a>
                <a href="#" class="js-issues-toc__removeEntry issues-toc-action i-times red confirm"
                   title="<?php echo _('Remove heading') ?>"></a>
            </div>
            <div class="flag__body">
                <input type="text" name="toc-content[%]" disabled="disabled" class="text-input text-input--large 1/1"
                       placeholder="<?php echo _('Heading') ?>"/>

            </div>
        </div>

    </li>

    <!-- content -->
    <li class="js-issues-toc__templates-content  issues-toc__templates-content 1/1" data-id="">
        <input name="toc-order[%]" type="hidden" class="js-issues-toc__order" disabled/>
        <input name="toc-is_header[%]" type="hidden" value="0" disabled/>

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
                        <input type="text" name="toc-content[%]" disabled class="text-input 1/1"
                               placeholder="<?php echo _('Author') ?>"/>

                    </div>
                    <div class="media__body">
                        <ul class="js-issues-toc__titles nav 1/1 m0">
                            <li class="js-issues-toc__title 1/1" data-id="">
                                <input name="toc_titles-order[%][%%]" type="hidden" class="js-issues-toc__title-order"
                                       value="0" disabled/>

                                <div class="flag flag--small">
                                    <div class="flag__img issues-toc-actions issues-toc-actions--small">
                                        <a href="#" class="js-issues-toc__moveUpTitle issues-toc-action i-chevron-up"
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
                                            <input type="text" name="toc_titles-content[%][%%]" disabled
                                                   class="text-input 1/1" placeholder="<?php echo _('Title') ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </li>
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
</ul>
