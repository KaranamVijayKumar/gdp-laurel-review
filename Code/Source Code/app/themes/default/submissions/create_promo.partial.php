<?php if (has_access('submissions_create')) { ?>
    <div class="flag">

        <div class="flag__body">
            <h2 class="u-mv0"><?php echo _('New submission') ?></h2>
            <p class="note u-mt0">
                <?php echo  _('Upload a new submission.') ?>

            </p>
        </div>
    </div>
    <hr/>

    <?php
    echo \Story\HTML::link(
        action('\Project\Controllers\Submissions\Create'),
        _('New Submission'),
        array('class'=>'btn btn--alert u-1-of-1 u-mt-', 'title' => _('New submission'))
    ) ?>

    <?php
      }
