<?php

declare(strict_types=1);

/**
 * Plugin: Image Checker
 * @link https://github.com/torvista/Zen_Cart-Image_Checker
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @updated 03 August 2025 torvista
 *
 */
// build a path to the jquery_tablesorter fileset
// sourced from https://github.com/Mottie/tablesorter
$pathLocalPlugin = $pluginManager->getPluginVersionDirectory('ImageChecker', $installedPlugins);
$pathLocalPlugin = '../' . strstr($pathLocalPlugin, 'zc_plugins');
$tablesorterJs = $pathLocalPlugin . 'admin/includes/javascript/tablesorter-2.32.0/dist/js/jquery.tablesorter.js';
?>
<script title="jquery Tablesorter: load" src="<?= $tablesorterJs ?>"></script>
<script title="jquery Tablesorter: execute">
    $(document).ready(function () {
        $("table").tablesorter({
            widgets: ["zebra"],
            widgetOptions: {
                zebra: ["normal-row", "alt-row"]
            }});
    });
</script>
