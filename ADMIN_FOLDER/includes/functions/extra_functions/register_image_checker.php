<?php
// Plugin: Image Checker
/* Image Checker Admin Page Registration.
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * heavily based on CEON Conor Kerr code
 */
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

$this_mod_name = "Image Checker";
$this_mod_box_tools_define = 'BOX_TOOLS_IMAGE_CHECKER';
$this_mod_filename_define = 'FILENAME_IMAGE_CHECKER';
$this_mod_page_key = "toolsImageChecker";
$this_mod_file_list = array(
    "image_checker.php",
    "includes/extra_datafiles/image_checker.php",
    "includes/languages/english/image_checker.php",
    "includes/languages/english/extra_definitions/image_checker.php"
);
$this_file = "includes/functions/extra_functions/register_image_checker.php";

$style_error = "background: pink; border: 1px solid red; margin: 1em; padding: 0.4em;";
$style_success = "background: palegreen; border: 1px solid black; margin: 1em; padding: 0.4em;";

// This file should normally only need to be run once, but if the user hasn't installed the software properly it
// may need to be run again. This flag tracks the situation
$can_autodelete = false;//set to true to autodelete

if (function_exists('zen_register_admin_page')) {

    if (!zen_page_key_exists($this_mod_page_key)) { //check it hasn't already got a configuration record
        print '<p style="' . $style_success . '">Processing ' . $this_mod_name . ' Admin Page Registration' . "</p>\n";
        //check the existence of the files necessary for this mod
        $error_messages = array();
        foreach ($this_mod_file_list as $file) {
            if (@!file_exists($file)) {
                $error_messages[] = $this_mod_name . " file: $file NOT found";
                $can_autodelete = false;
            } else { //debug
                //$error_messages[] = $this_mod_name . " file: $file WAS found";
            }
        }

        if (sizeof($error_messages) > 0) {
            // Let the user know that there are problem(s) with the installation
            foreach ($error_messages as $error_message) {
                print '<p style="' . $style_error . '">Error: ' . $error_message . "</p>\n";
            }
        } else {
            // Necessary files are in place
            //get the next position in the tools menu
            $max_sort_order = $db->Execute("SELECT MAX(sort_order) +1 AS next_menu_item FROM " . TABLE_ADMIN_PAGES . " WHERE menu_key = 'tools'");
            $next_menu_item = $max_sort_order->fields['next_menu_item'];

            //register the admin page and have the menu item created	
            zen_register_admin_page($this_mod_page_key, $this_mod_box_tools_define, $this_mod_filename_define, '',
                'tools', 'Y', $next_menu_item);
            print '<p style="' . $style_success . '">All installation files found: ' . $this_mod_name . " has been added to the Tools menu</p>\n";
        }
    } else {
        //print '<p style="' . $style_error . '">Error: Configuration table record for ' . $this_mod_name . " already exists</p>\n";
    }
}

if ($can_autodelete) {
    // Either the new menu item has been registered, or it was already registered.
    //Stop the wasteful process of having this script run again by having it delete itself
    $autodeleted = @unlink($this_file);
    print($autodeleted ? '<p style="' . $style_success . '">' . $this_file . " has been removed (now not required)</p>\n" : '<p style="' . $style_error . '">Unable to delete ' . $this_file . "</p>\n");
}
