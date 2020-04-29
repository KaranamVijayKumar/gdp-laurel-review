<?php if (has_access('manage_admin_submissions_comment')) { ?>
    <div class="submission__comment">
        <?php
        echo \Story\Form::open(
            array(
                'action' => action('\Project\Controllers\Admin\Submissions\Comment', array($submission->id)),
                'errors' => $errors,
                'id'     => 'activity_form',
            )
        ) ?>
        <h4 class="content-hero"><?php echo _('Post a comment') ?></h4>
        <div class="layout ph--">
            <div class="layout__item pb-">
                <?php echo \Story\Form::label('comment', _('Comment')) ?>

                <?php
                echo \Story\Form::textarea(
                    'comment',
                    '',
                    array(
                        'rows'                     => '5',
                        'class'                    => 'text-input 1/1 text-input--redactor',
                        'id'                       => 'comment',
                        'placeholder'              => _('Insert comment here...'),
                    )
                ) ?>
            </div>
            <div class="cf"></div>
            <div class="layout__item 1/5 palm-1/1">
                <?php
                echo \Story\Form::button(
                    _('Post'),
                    array('class' => 'btn 1/1 btn--positive', 'type' => 'submit')
                ) ?>
            </div><!--
         --><div class="layout__item 1/5 palm-1/1"></div>
        </div>
        <?php echo \Story\Form::close() ?>
    </div>
    <?php
      } ?>

<h4 class="content-hero">
    <?php
    echo sprintf(
        ngettext('History (updated every %s second)', 'History (updated every %s seconds)', 15),
        15
    ) ?>
</h4>
<div class="submission__activity"
     data-url="<?php echo action('\Project\Controllers\Admin\Submissions\Activity', array($submission->id, 'html')) ?>">
    <p class="gray"><em><?php echo _('Loading &hellip;') ?></em></p>
</div>
