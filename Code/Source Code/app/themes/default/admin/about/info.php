<h4 class="content-hero"><?php echo $key ?></h4>
<table class="1/1 table--striped">
    <tbody>
    <?php
    foreach ($item as $data) { ?>
        <?php
            if ($data) { ?>
                <tr>
                    <td class="1/4">
                        <?php
                        echo isset($data['url']) ? \Story\HTML::link(
                            $data['url'],
                            $data['name']
                        ) : $data['name'] ?></td>

                    <td><?php echo $data['description'] ?></td>
                </tr>
        <?php
            } ?>
    <?php
    } ?>
    </tbody>
</table>

