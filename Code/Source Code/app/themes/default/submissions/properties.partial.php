
<h3>Information</h3>
<table class="table table--striped table--borderless u-1-of-1 u-mt">
    <tbody>
    <?php
    if ($category) { ?>
        <tr>
            <td><?php echo _('Category') ?></td>
            <td>
                <strong><?php echo h($category->name) ?></strong>
            </td>
        </tr>
    <?php
    } ?>
    <tr>
        <td><?php echo _('Status') ?></td>
        <td><strong><?php echo _($status->name) ?></strong></td>
    </tr>
    <tr>
        <td><?php echo _('Created') ?></td>
        <td>
            <strong title="<?php echo $submission->created ?>">
                <?php printf(_('%s (%s)'), $submission->created->diffForHumans(), $submission->created) ?>
            </strong>
        </td>
    </tr>

    <tr>
        <td><?php echo _('Filename') ?></td>
        <td><strong><?php echo h($file->name) ?></strong></td>
    </tr>
    </tbody>
</table>
