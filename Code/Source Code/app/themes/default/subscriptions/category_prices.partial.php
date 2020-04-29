<?php if (count($categories)) { ?>
    <h3 class=""><?php echo _('Subscription Categories') ?></h3>
    <hr/>
    <table class="u-1-of-1 table--borderless table--striped table--list">
        <thead>
        <tr>
            <th><?php echo _('Category') ?></th>
            <th class="text--right"><?php echo _('Price') ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
            foreach ($categories as $category) { ?>
                <tr>
                    <td>
                        <?php echo h($category->name) ?>
                    </td>
                    <td class="text--right">
                        <?php echo money_format('%n', $category->amount) ?>
                    </td>
                </tr>

            <?php
            } ?>
        </tbody>
    </table>
    <hr/>
<?php
} ?>
