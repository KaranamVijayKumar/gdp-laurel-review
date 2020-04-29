<h4 class="content-hero"><?php echo _('Project') ?></h4>
<div id="info">
    <table class="1/1 table--striped">
        <tbody>
        <tr>
            <td class="1/4"><?php echo _('Version') ?></td>
            <td><?php echo $item['version'] ?></td>
        </tr>
        <tr>
            <td><?php echo _('Project Name') ?></td>
            <td><?php echo $item['name'] ?></td>
        </tr>
        <tr>
            <td><?php echo _('License') ?></td>
            <td><?php echo $item['license'] ?></td>
        </tr>
        </tbody>
    </table>

    <?php
    if (isset($item['authors']) && $item['authors']) { ?>

        <h5><?php echo _('Authors') ?></h5>
        <table class="1/1 table--striped">
        <?php
            foreach ($item['authors'] as $author) { ?>
                <tr>
                    <th colspan="3" class="text--left"><?php echo h($author['name']) ?></th>
                </tr>
            <?php unset($author['name']) ?>
            <?php
                    foreach ($author as $key => $value) { ?>
                        <tr>
                            <td class="1/4"><?php echo ucwords($key) ?></td>
                            <td><?php echo auto_link($value) ?></td>
                        </tr>
            <?php
                    } ?>
        <?php
            } ?>
        </table>
    <?php
    } ?>
    <h5><?php echo _('Support') ?></h5>
    <table class="1/1 table--striped">
        <tbody>
        <?php
        foreach ($item['support'] as $key => $value) { ?>
            <tr>
                <td class="1/4"><?php echo ucwords($key) ?></td>
                <td><?php echo auto_link($value) ?></td>
            </tr>
        <?php
        } ?>
        </tbody>
    </table>
</div>
