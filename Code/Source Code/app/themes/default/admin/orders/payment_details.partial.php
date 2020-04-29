<table class="1/1">

    <tbody>

    <?php foreach($history->paymentSummary() as $name => $value) { ?>

        <tr>
            <td class="1/3"><span class="label"><?php echo _($name) ?></span></td>
            <td><?php echo $value ?></td>
        </tr>


    <?php } ?>

    </tbody>

</table>


