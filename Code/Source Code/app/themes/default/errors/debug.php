<div class="debug">


    <table class="u-1-of-1 table--striped table--debug">
        <tr>
            <th class="1/4">Execution Time</th>
            <th colspan="4">Memory Usage</th>
        </tr>

        <tr>
            <td><?php print round((microtime(true) - START_TIME), 5); ?> seconds</td>
            <td><?php print number_format(memory_get_usage() - START_MEMORY_USAGE); ?> bytes</td>
            <td><?php print number_format(memory_get_usage()); ?> bytes (process)</td>
            <td><?php print number_format(memory_get_peak_usage(true)); ?> bytes (process peak)</td>
        </tr>


        <tr>
            <th>Permission name</th>
            <th>Locale</th>
            <th>Timezone</th>
            <th></th>
        </tr>
        <tr>
            <td>
                <code><?php print getenv('PERMISSION_NAME'); ?></code>
            </td>
            <td>
                <?php print Locale::getDefault(); ?>
            </td>
            <td>
                <?php print date_default_timezone_get(); ?>
            </td>
            <td></td>
        </tr>


        <?php
        if (class_exists('\Story\DB', false)) {


            foreach (\Story\DB::$queries as $type => $queries) {

                echo '<tr class="header"><th colspan="4">' . $type . ' (' . count($queries) . ' queries)</th></tr>';
                foreach ($queries as $data) {
                    print '<tr class="visuallyhidden"><td>' . round(($data[0] * 1000), 4) .' ms</td><td colspan="3"><code>'. trim($data[1])
                         . '</code></td></tr>';
                }
            }

            if (\Story\Error::$found) {
                echo '<tr class="header"><th colspan="4">Last Query</th></tr>';
                echo '<tr><td colspan="4"><code>' . \Story\DB::$last_query .'</code></td></tr>';
            }
        }
        ?>
        <tr class="header">
            <th colspan="4">URL Path</th>
        </tr>
        <tr class="visuallyhidden">
            <td colspan="4">
                <?php print PATH; ?>
            </td>
        </tr>
        <tr class="header">
            <th colspan="4">Session Data</th>
        </tr>
        <tr class="visuallyhidden">
            <td colspan="4">
                <pre class="m0"><code><?php print app('session') ? var_export(app('session')->all()) : ''; ?></code></pre></td>
        </tr>

    <?php if (!empty($_POST)) { ?>
            <tr class="header">
                <th colspan="4">$_POST Data</th>
            </tr>
            <tr class="visuallyhidden">
                <td colspan="4"><pre class="m0"><code><?php print var_dump($_POST); ?></code></pre></td>
            </tr>

    <?php } ?>

    <?php if (!empty($_GET)) { ?>
            <tr  class="header">
                <th colspan="4">$_GET Data</th>
            </tr>
            <tr class="visuallyhidden">
                <td colspan="4"><pre class="m0"><code><?php print var_dump($_GET); ?></code></pre></td>
            </tr>
    <?php } ?>

        <tr class="header">
            <th colspan="4">
                <?php $included_files = get_included_files(); ?>
                <?php print count($included_files); ?> PHP Files Included
            </th>
        </tr>
        <?php foreach ($included_files as $file) {
            print '<tr class="visuallyhidden"><td colspan="4"><code>' . str_replace(SP, '', $file) . "</td></tr>";
        } ?>
        <tr class="header">
            <th colspan="4">
                Server Info
            </th>
        </tr>
            <?php foreach ($_SERVER as $name => $value) { ?>
                <tr class="visuallyhidden">
                    <td colspan="2" class="1/4">
                        <?php echo $name ?>
                    </td>
                    <td colspan="2">
                        <pre class="m0 p0" style="border-width:0;background:transparent"><?php echo is_string($value) ? trim(chunk_split($value), 80) : trim(chunk_split(var_export($value, true)), 80) ?></pre>
                    </td>
                </tr>
            <?php } ?>

        </table>
    <script>
        $('body').on('click', '.table--debug tr.header', function (e) {
            $(this).nextUntil('tr.header').toggleClass('visuallyhidden');
        });
    </script>
</div>
