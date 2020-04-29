<?php
use Story\Form;
use Story\HTML;

?>
<div class="flag__body">

    <div class="flag flag--rev flag--editable pv--">
        <div class="flag__body gamma pt--">
            <?php echo \Story\HTML::link(action('\Project\Controllers\Admin\Submissions\Index'), _('Submissions')) ?>
            /
            <?php
            echo \Story\HTML::link(
                action('\Project\Controllers\Admin\Submissions\Show', array($submission->id)),
                h($submission->name)
            ) ?>
        </div>
    </div>
    <div class="flag flag--rev flag--editable flag--secondary pb--">
        <div class="flag__body">
            <?php
            if (has_access('admin_submissions_view_author')) { ?>
                <?php echo sprintf(_('by %s'), '<em class="pr-">' . $author_name . '</em>') ?>
            <?php
            } ?>
            <?php
            if ($status) { ?>
                <span class="btn btn--label"><?php echo h($status->name) ?></span>
            <?php
            } ?>
            <?php
            if ($category) { ?>
                <span class="btn btn--label"><?php echo h($category->name) ?></span>
            <?php
            } ?>

        </div>
    </div>
    <div class="flag flag--small flag--responsive ">
        <div class="flag__img mb0">
            <ul class="nav mv- actions">
                <?php
                if (has_access('admin_submissions_download') && $downloadLink) { ?>
                    <li class="">
                        <a href="<?php echo $downloadLink ?>" class="i-download"
                           title="<?php echo _('Download file') ?>"></a>

                    </li>
                    <li class="action__separator"></li>
                <?php
                } ?>

                <?php
                if (has_access('admin_submissions_email')) { ?>
                    <li class="">
                        <?php
                        echo HTML::link(
                            action('\Project\Controllers\Admin\Submissions\Email', array($submission->id)),
                            '',
                            array('class' => 'i-envelope', 'title' => _('Send email to the author'))
                        ) ?>
                    </li>
                    <li class="action__separator"></li>
                <?php
                } ?>

                <?php
                if (has_access('manage_admin_submissions_accept')) { ?>
                <?php
                    if ($canAcceptOrDecline) { ?>
                        <li>
                            <a href="<?php echo $acceptLink ?>" class="i-check-circle action--positive"
                               title="Accept"></a>
                        </li>
                        <li>
                            <a href="<?php echo $declineLink ?>" class="i-times-circle action--negative"
                               title="Decline"></a>
                        </li>
                        <li class="action__separator"></li>
                <?php
                    } else { ?>
                        <li>
                                <span
                                    class="i-check-circle btn--disabled <?php
                                    echo $status->slug === \Project\Models\SubmissionStatus::STATUS_ACCEPTED ||
                                    $status->slug === \Project\Models\SubmissionStatus::STATUS_SIGNED ?
                                        'action--positive' : 'btn--disabled action--inactive'
                                    ?>"></span>
                        </li>
                        <li>
                                <span
                                    class="i-times-circle btn--disabled <?php
                                    echo $status->slug === \Project\Models\SubmissionStatus::STATUS_DECLINED ?
                                        'action--negative btn--disabled' : 'action--inactive' ?>"></span>
                        </li>
                    <?php
                    } ?>
                <?php
                } ?>

                <?php
                if (has_access('manage_admin_submissions_like')) { ?>
                <?php
                    if ($like === 0) { ?>
                        <li>
                            <?php
                            echo HTML::link(
                                action('\Project\Controllers\Admin\Submissions\Like', array($submission->id)),
                                $likeCount['likes'],
                                array(
                                    'data-value' => '1',
                                    'class'      => 'actions__like i-thumbs-o-up confirm',
                                    'title'      => _('Like')
                                )
                            )
                            ?>
                        </li>
                        <li>
                        <?php
                        echo HTML::link(
                            action('\Project\Controllers\Admin\Submissions\Like', array($submission->id)),
                            $likeCount['dislikes'],
                            array(
                                'data-value' => '-1',
                                'class'      => 'actions__like i-thumbs-o-down confirm',
                                'title'      => _('Dislike')
                            )
                        )
                        ?>
                <?php
                    } else { ?>
                        <li>
                                <span
                                    class="i-thumbs-o-up <?php
                                    echo $like === '1' ?
                                        'action--positive btn--disabled' : 'btn--disabled action--inactive' ?>">
                                    <?php echo $likeCount['likes'] ?></span>
                        </li>
                        <li>
                                <span
                                    class="i-thumbs-o-down <?php
                                    echo $like === '-1' ?
                                        'action--negative btn--disabled' : 'btn--disabled action--inactive' ?>">
                                    <?php echo $likeCount['dislikes'] ?></span>
                        </li>
                    <?php
                    } ?>
                <?php
                } ?>

            </ul>
        </div>
        <div class="flag__body">
            <?php
            if (!isset($hide_tabs)) { ?>
                <?php echo count($errors) ? Form::hidden('openTab', 'tab3') : '' ?>
                <ul class="tabs mv-">
                <?php
                    if (isset($filePreview) && $filePreview) { ?>
                        <li class="tabs__item">
                            <a href="#tab1" class="tabs__link" accesskey="p"><?php echo _('Preview') ?></a>
                        </li>
                    <?php
                    } ?>

                <?php
                    if (has_access('admin_submissions_activity')) { ?>
                        <li class="tabs__item">
                            <a href="#tab2" class="tabs__link" accesskey="a"><?php echo _('Activity') ?></a>
                        </li>
                    <?php
                    } ?>

                <?php
                    if (has_access('admin_submissions_edit')) { ?>
                        <li class="tabs__item">
                            <a href="#tab3" class="tabs__link" accesskey="r"><?php echo _('Properties') ?></a>
                        </li>
                    <?php
                    } ?>
                <?php
                    if ($partial_withdrawn) { ?>

                        <li class="tabs__item">
                            <a href="#tab4" class="tabs__link" accesskey="w">
                                <span class="red"><?php echo _('Withdrawals') ?></span>
                            </a>
                        </li>

                    <?php
                    } ?>
                </ul>
            <?php
            } ?>
        </div>
    </div>
</div>
