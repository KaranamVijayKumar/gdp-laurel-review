<h4><?php echo _('Status') ?></h4>
<?php echo \Story\Form::label('status', _('Status')) ?>
<?php
echo \Story\Form::select(
    'status',
    array('1' => _('Enabled'), '0' => _('Disabled')),
    $page->status,
    array('id' => 'status', 'class' => 'palm-1/1')
) ?>
<p class="gray">
    <small>
        <?php
        echo _(
            "You need to enable the page in order to be visible on the site. ".
            "You can still edit a disabled page in the admin interface."
        ) ?>

    </small>
</p>
<h4><?php echo _('Metadata') ?></h4>
<div class="js-multiple mb">
    <?php
    foreach ($page->meta as $index => $meta) { ?>

        <div class="media mv-- media--small media--responsive">
            <div class="media__img 1/4 palm-3/4">
                <?php echo \Story\Form::label('meta_name_' . ($index + 1), _('Name')) ?>

                <?php
                echo \Story\Form::text(
                    'meta_name[' . $meta->id .']',
                    $meta->name,
                    array(
                        'id' => 'meta_name_' . ($index + 1),
                        'class' => 'text-input 1/1 mb0',
                        "placeholder" => "description, keywords, robots"
                    )
                ) ?>

            </div>
            <div class="media__body">
                <div class="flag flag--small flag--rev">
                    <a href="#" class="flag__img pt i-times i--middle red js-multiple-remove"></a>
                    <div class="flag__body">
                        <?php echo \Story\Form::label('meta_content_' . ($index + 1), _('Content')) ?>

                        <?php
                        echo \Story\Form::text(
                            'meta_content[' . $meta->id .']',
                            $meta->value,
                            array(
                                'id' => 'meta_content_' . ($index + 1),
                                'class' => 'text-input 1/1 mb0',
                                "placeholder" => _('Short description of the page or comma separated words')
                            )
                        ) ?>

                    </div>
                </div>
            </div>
        </div>

    <?php
    } ?>

    <template class="hidden">
        <div class="media mv-- media--small media--responsive">
            <div class="media__img 1/4 palm-3/4">
                <label for="name_%"><?php echo _('Name') ?></label>
                <input type="text" name="meta_name[]" id="name_%" class="text-input 1/1 mb0"
                       placeholder="description, keywords, robots"/>
            </div>
            <div class="media__body">
                <div class="flag flag--small flag--rev">
                    <a href="#" class="flag__img pt i-times i--middle red js-multiple-remove"></a>
                    <div class="flag__body">
                        <label for="content_%"><?php echo _('Content') ?></label>
                        <input type="text" name="meta_content[]" id="content_%" class="text-input 1/1 mb0"
                               placeholder="<?php echo _('Short description of the page or comma separated words') ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <a href="#" class="i-plus green js-multiple-add" title="<?php echo _('Add a new meta tag') ?>"></a>

</div>
<h5 class="mb--"><?php echo _('Metadata examples') ?></h5>
<table class="">
    <thead>
    <tr>
        <th><?php echo _('Name') ?></th>
        <th><?php echo _('Content') ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>description</td>
        <td><em><?php echo _('Enter a short description of the page with a maximum 200 characters.') ?></em></td>
    </tr>
    <tr>
        <td>keywords</td>
        <td>laurel review, contact, submission</td>
    </tr>
    <tr>
        <td>robots</td>
        <td>noindex, nofollow</td>
    </tr>
    </tbody>
</table>
<p>
    <?php
    echo sprintf(
        _('To learn more about search engine optimizations visit %s.'),
        \Story\HTML::link(
            'https://en.wikipedia.org/wiki/Meta_element#Meta_element_used_in_search_engine_optimization',
            'Meta element used in search engine optimization',
            array('target' => '_blank')
        )
    ) ?>
</p>

