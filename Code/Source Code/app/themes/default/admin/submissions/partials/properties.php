<?php
if (has_access('admin_submissions_view_author')) { ?>
    <h4 class="mb- mt content-hero"><?php echo _('Author') ?></h4>
    <div class="flag">
        <div class="flag__img">
            <?php echo \Story\HTML::gravatar($author->email, 48, $author_name, 'mm') ?>
        </div>
        <div class="flag__body">
        <?php
            if (has_access('admin_users_edit')) { ?>
                <span class="i-user mb0 gray">
                    <?php
                    echo \Story\HTML::link(
                        action('\Project\Controllers\Admin\Users\Edit', array($author->id)),
                        $author_name
                    ) ?>
                </span>
                <br>
                <span class="i-envelope-o gray">
                    <?php echo \Story\HTML::link('mailto:' . $author->email, $author->email) ?>
                </span>
        <?php
            } else { ?>
                <span class="i-user mb0">
                    <?php echo $author_name ?>
                </span>
        <?php
            } ?>

        </div>
    </div>

    <h4 class="mb- content-hero"><?php echo _('Coverletter') ?></h4>
    <div class="submission__coverletter">
        <?php
        echo $coverletter ? $coverletter->content : '<em class="orange">' . _(
            'No cover letter supplied.'
        ) . '</em>'; ?>
    </div>
    <?php
} ?>

<h4 class=" content-hero"><?php echo _('Properties') ?></h4>
<?php
echo \Story\Form::open(
    array(
        'action' => action('\Project\Controllers\Admin\Submissions\Edit', array($submission->id)),
        'errors' => $errors,
        'enctype' => 'multipart/form-data'
    )
) ?>

<div class="layout ph-- mb">
    <div class="layout__item 2/3 palm-1/1">
        <?php echo \Story\Form::label('name', _('Name')) ?>
        <?php
        echo \Story\Form::text(
            'name',
            $submission->name,
            array('class' => 'text-input 1/1', 'id' => 'name')
        ) ?>

    </div><!--
 --><div class="layout__item 1/3 palm-1/1">
        <?php echo \Story\Form::label('status', _('Status')) ?>
        <?php echo \Story\Form::select('status', $statuses, $status->id, array('id' => 'status')) ?>
    </div>
    <div class="layout__item pt 1/2 palm-1/1">
        <?php echo \Story\Form::label('category', _('Category')) ?>
        <?php
        echo \Story\Form::select(
            'category',
            array('' => '') + $categories,
            $category ? $category->id : '',
            array('id' => 'category')
        ) ?>
    </div><!--
 --><div class="layout__item  pt 1/2 palm-1/1">
        <?php echo \Story\Form::label('file', _('Replace submission file')) ?>
        <?php echo \Story\Form::file('file', array('id' => 'file')) ?>
        <p class="gray">
            <small>
                <?php
                printf(
                    _('You can upload %1$s files with the maximum size of %2$s.'),
                    '<strong>' . implode(', ', \Project\Models\Submission::$fileTypes) . '</strong>',
                    get_file_size(max_upload_size())
                ); ?>
                <br>
                    <span class="red">
                        <?php echo _('Uploading a file will replace the existing submission file!') ?>
                    </span>
            </small>
        </p>
    </div>
    <div class="layout__item">
        <table class="1/1 mb">
            <tbody>
            <tr>
                <td class="1/4"><?php echo _('Created') ?></td>
                <td>
                    <?php
                    echo $submission->created->diffForHumans(
                    ) ?> <?php echo '(' . $submission->created->toDayDateTimeString() . ')' ?>
                </td>
            </tr>
            <?php
            if ($submission->attributes['modified']) { ?>
                <tr>
                    <td class="1/4"><?php echo _('Last updated') ?></td>
                    <td>
                        <?php echo $submission->modified->diffForHumans() ?>
                        <?php echo '(' . $submission->created->toDayDateTimeString() . ')' ?>
                    </td>
                </tr>
            <?php
            } ?>
            </tbody>
        </table>
    </div>
    <div class="cf"></div>
    <div class="layout__item 1/5 palm-1/1">
        <?php echo \Story\Form::button(_('Save'), array('class' => 'btn 1/1', 'type' => 'submit')) ?>
    </div>
</div>
<?php
echo \Story\Form::close();
