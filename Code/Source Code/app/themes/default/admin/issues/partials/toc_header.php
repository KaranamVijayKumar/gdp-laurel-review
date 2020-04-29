<li class="js-issues-toc__templates-header issues-toc__templates-header js-issues-toc__entry 1/1"
    data-id="<?php echo $item->id ?>">
    <input name="toc-order[<?php echo $item->id ?>]" type="hidden" class="js-issues-toc__order"
           value="<?php echo (int)$item->order ?>"/>
    <input name="toc-is_header[<?php echo $item->id ?>]" type="hidden" value="1"/>
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
            <input type="text" name="toc-content[<?php echo $item->id ?>]" class="text-input text-input--large 1/1"
                   placeholder="<?php echo _('Heading') ?>" value="<?php echo h($item->content) ?>"/>

        </div>
    </div>

</li>
