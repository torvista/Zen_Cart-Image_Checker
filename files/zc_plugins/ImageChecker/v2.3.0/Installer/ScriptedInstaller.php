<?php

declare(strict_types=1);

/**
 * Plugin: Image Checker
 * @link https://github.com/torvista/Zen_Cart-Image_Checker
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @updated 03 August 2025 torvista
 */

use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{
    protected function executeInstall(): void
    {
        zen_deregister_admin_pages(['toolsImageChecker']);
        zen_register_admin_page('toolsImageChecker', 'BOX_TOOLS_IMAGE_CHECKER', 'FILENAME_IMAGE_CHECKER', '', 'tools', 'Y');
    }

    protected function executeUninstall()
    {
        zen_deregister_admin_pages(['toolsImageChecker']);
    }
}
