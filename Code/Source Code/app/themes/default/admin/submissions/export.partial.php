<?php
if (has_access('manage_admin_submissions_export')) {
    ?>
    <hr class="mb0"/>
    <div class="content-hero pv--">
        <form action="<?php
        echo action('\Project\Controllers\Admin\Submissions\Export') ?>" method="post" class="filter">

            <ul class="nav actions mv0 1/2 lap-1/1 palm-1/1">
                <li class="1/3 lap-1/4 palm-1/2">
                    <?php
                    echo \Story\Form::select(
                        'exporter',
                        get_exporters('Submission'),
                        null,
                        array('class' => '1/1')
                    ) ?>
                </li>
                <li class="1/3 lap-1/4 palm-1/2">
                    <?php
                    echo \Story\Form::select(
                        'quantity',
                        array('all' => _('All'), 'current' => _('Current page')),
                        null,
                        array('class' => '1/1')
                    ) ?>
                </li>
                <li class="1/3 lap-1/4 palm-1/2">
                    <button class="btn btn--secondary btn--small ph- i-archive" name="action" type="submit">
                        <?php echo _('Export') ?>
                    </button>
                </li>
            </ul>
            <p class="mb0 mt-- gray">
                <small>
                    <?php
                    echo sprintf(
                        _('Due to large amount of submission related data, these exports are limited to %s submissions.'),
                        \Project\Services\Exporter\Exporters\SubmissionExporter::LIMIT
                    ) ?>
                </small>
            </p>
            <?php echo \Story\Form::hidden('status', $selectedStatus) ?>
            <?php echo \Story\Form::hidden('category', $selectedCategory) ?>
            <?php echo \Story\Form::hidden('query', get('q', isset($query) ? $query : '')) ?>
            <?php echo \Story\Form::hidden('page', $current_page) ?>
        </form>
    </div>
    <hr class="mt0"/>
    <?php
}
