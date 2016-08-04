<?php
/*
 * Missing Images Checker for ZenCart
 * By Paul Williams (retched)
 * additions by Zen4All and torvista
 * @copyright Portions Copyright 2004-2014 Zen Cart Team
****************************************************************************
    Copyright (C) 2014  Paul Williams/IWL Entertainment

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/
// This file reads the image link path associated with each product and
// a) checks if anything is defined
// b) if defined, checks if the file referenced exists, if it is an image, if it is named correctly and if it is a common file type.
// 
// The script reports any discrepancies
// To get the most of this file, you should disable PHP's safe mode as this file
// may take an excessively long time if you're running this script on a LARGE 
// database. Large being anywhere near 2000 or more products. In addition,
// you should also probably run this on a dedicated server or a local testing
// server as opposed to online.
//
// Also note **NO CHANGES ARE MADE TO YOUR DATABASE**. This script is read only.

$limit = 20;//digit (no quotation marks) or false/0. Limit the results for easier debugging.

define('MISSING_IMAGES_VERSION', '2.0');

//////////////////////////////////////////////////////////////
require('includes/application_top.php');

//getimagesize possible returned values
$getimagesize_types = array(
    0 => 'UNKNOWN',
    1 => 'GIF',
    2 => 'JPEG',
    3 => 'PNG',
    4 => 'SWF',
    5 => 'PSD',
    6 => 'BMP',
    7 => 'TIFF_II',
    8 => 'TIFF_MM',
    9 => 'JPC',
    10 => 'JP2',
    11 => 'JPX',
    12 => 'JB2',
    13 => 'SWC',
    14 => 'IFF',
    15 => 'WBMP',
    16 => 'XBM',
    17 => 'ICO',
    18 => 'COUNT'
);
/*php constants
IMAGETYPE_GIF=1
IMAGETYPE_JPEG=2
IMAGETYPE_PNG=3
IMAGETYPE_BMP=6
*/
///////////////////////////////////////////////////////////////////////////

//get value of checkbox to show all products or only those with image issues
$list_all_products = (isset ($_POST['listAllProducts']) || isset ($_GET['listAllProducts']) ? true : false);

// Get an array of all products in the database: product name, product id, product model and product image fields.
// The product image and product id are stored in TABLE_PRODUCTS.
// The product names are stored in the TABLE_PRODUCTS_DESCRIPTION table. So a LEFT JOIN will be used.
$limit_clause = ($limit ? ' LIMIT ' . (int)$limit : '');//for debugging a smaller result set
$products_query = "SELECT p.products_id, p.products_model, p.products_image, p.products_status, pd.products_name
                 FROM " . TABLE_PRODUCTS . " p
                 LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id
                 WHERE pd.language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY p.products_id" . $limit_clause;

$products_result = $db->Execute($products_query);
//$products_result = '';//test no products

//echo '<pre>';echo print_r($products_result);'</pre>';
$products_info = array();
foreach ($products_result as $row) {
    $products_info[] = array(
        "id" => $row['products_id'],
        "model" => $row['products_model'],
        "image" => $row['products_image'],
        "status" => $row['products_status'],
        "name" => $row['products_name']
    );
}
//echo __LINE__ . ': <pre>';echo print_r($products_info);echo '</pre>';
/*
* iterate over each product:
 *      1. Determine if the file exists. (file_exists($file_path))
 *      2. Determine if the image retrieved is actually an image.
 *         (getimagesize($path))
 *      3. Determine if the image is stored in the correct format. (An image with 
 *         a .PNG should register as a PNG.)
 */

foreach ($products_info as $key => &$product) {//add $product['image_status'] and $product['error'] into array
//$product['image_status']
//0 - file exists and is good
//1 - no file defined
//2 - file exists but not browser friendly
//3 - file exists but real file type does not match extension
//4 - file missing

    $file = DIR_FS_CATALOG_IMAGES . $product['image'];

    if ($product['image'] == '') {
        $product['image_status'] = 1;
        $product['error'] = ERROR_NO_IMAGE_DEFINED;
    } elseif (!file_exists($file)) {
        $product['image_status'] = 4;
        $product['error'] = ERROR_IMAGE_NOT_FOUND;
    } else {

        $image_check = getimagesize($file); // getimagesize returns 0 => height, 1 => width, and 2 => type.
        $image_type = $image_check[2];//third element of array

        //echo $product['image'] . ', $image_type=' . $image_type . '<br />';

        $file_ext = strtolower(substr(strrchr($file, '.'), 1));  // Retrieve the file extension, convert it to lower case.

//check image-naming (extensions) against actual file type
        if (!$image_type) {//getimagesize does not recognise this as an image
            $product['error'] = sprintf(ERROR_NOT_IMAGE, $file_ext);
            $product['image_status'] = 3;

        } elseif (!in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {//The image found is not one of the common web types
            $product['error'] = sprintf(ERROR_NOT_COMMON_FORMAT, $file_ext);
            $product['image_status'] = 2;

        } elseif ($file_ext == "gif" && $image_type != IMAGETYPE_GIF) {//image extension = GIF, but it isn't
            $product['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
            $product['image_status'] = 3;

        } elseif ((in_array($file_ext, array("jpg", "jpeg", "jpe", "jfif", "jif")) && $image_type != IMAGETYPE_JPEG)) {//image extension = JPEG-type, but it isn't
            $product['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
            $product['image_status'] = 3;

        } elseif ($file_ext == "png" && $image_type != IMAGETYPE_PNG) {//image extension = PNG, but it isn't
            $product['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
            $product['image_status'] = 3;

        } elseif ($file_ext == "bmp" && $image_type != IMAGETYPE_BMP) {//image extension = BMP, but it isn't
            $product['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
            $product['image_status'] = 3;

        } else {//its valid
            if (!$list_all_products) {
                unset ($products_info[$key]);
            }
            $product['error'] = ERROR_NO_ERROR;
            $product['image_status'] = 0;
        }
    }

    if (!ini_get('safe_mode'))
        set_time_limit(30); // If you're not running PHP in Safe Mode, reset the timer to 30 seconds and start again. This is to allow for larger databases to be run. Will work on a better fix to this in a later update. Will likely involve a bit of refreshing and sending to the $_POST.
}
//echo __LINE__ . ': <pre>';echo print_r($products_info);echo '</pre>';
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo 'Admin - ' . HEADING_TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
    <script type="text/javascript" src="includes/menu.js"></script>
    <script type="text/javascript" src="includes/general.js"></script>
    <script type="text/javascript">
        <!--
        function init() {
            cssjsmenu('navbar');
            if (document.getElementById) {
                var kill = document.getElementById('hoverJS');
                kill.disabled = true;
            }
        }
        // -->
    </script>
    <style type="text/css">
        #body {
            padding: 0 1%;
        }

        #resultsTable {
            width: 100%;
            margin: auto;
            /*border-collapse: collapse;*/
            line-height: 1.7em;
        }

        #resultsTable th, #resultsTable td {
            padding: 2px 8px;
        }

        #resultsTable .tableHeading {
            text-align: left;
            font-weight: bold;
            color: #FFFFFF;
            background: #000000;
        }

        #resultsTable .rowEven {
            background: white;
        }

        #resultsTable .rowOdd {
            background: #EFEFEF;
        }

        #resultsTable .rowEven:hover, #resultsTable .rowOdd:hover {
            background: #DADADA;
        }

        .valid {
            /*padding: 0 0 0 50px;
            color: #009900 !important;
            font-weight: bold;*/
        }

        .mismatch, .uncommon { /*filetype not an image or not as per extension*/
            background: khaki;
            font-style: italic;
        }

        .undefined {
            /*font-weight: bold;*/
        }

        .missing {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body onLoad="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
    <div><h1><?php echo HEADING_TITLE . ' - ' . TEXT_VERSION . MISSING_IMAGES_VERSION; ?></h1>
        <p><?php echo TEXT_IMAGES_DIRECTORY; ?></p>

        <?php if (sizeof($products_info) > 0) { ?>

        <?php if ($limit) { ?>
            <p class="messageStackCaution"><?php echo sprintf(TEXT_QUERY_LIMITED, $limit); ?></p>
        <?php } ?>
    </div>
    <div>
        <?php echo zen_draw_form('set_list_all', FILENAME_MISSING_IMAGES, zen_get_all_get_params(array('listAllProducts'), 'get')); ?>
        <label for="listAllProducts">List all products</label>
        <?php echo zen_draw_checkbox_field('listAllProducts', '1', $list_all_products, '', 'id="listAllProducts" onchange="this.form.submit();"'); ?>
        </form>
    </div>
    <div>
        <table id="resultsTable">
            <tr class="tableHeading">
                <th class="center"><?php echo TABLE_HEADING_ENTRY; ?></th>
                <th class="center"><?php echo TABLE_HEADING_ID; ?></th>
                <th colspan="2"><?php echo TABLE_HEADING_STATUS; ?></th>
                <th><?php echo TABLE_HEADING_MODEL; ?></th>
                <th><?php echo TABLE_HEADING_NAME; ?></th>
                <th><?php echo TABLE_HEADING_IMAGE; ?></th>
                <th class="center"><?php echo TABLE_HEADING_RESULT; ?></th>
            </tr>

            <?php
            // Initialise row counters
            $count = 0;
            $error_count = 0;
            //echo __LINE__ . ': <pre>';echo print_r($products_info);echo '</pre>';

            foreach ($products_info as &$product) {
                $count++;
                //echo __LINE__ . ': <pre>';echo print_r($product);echo '</pre>';

                switch ($product['image_status']) {
                    case (0)://image found and extension matches type
                        $errorclass = "valid";
                        break;
                    case (1)://no image defined
                        $errorclass = "undefined";
                        $error_count++;
                        break;
                    case (2)://image exists but type not browser friendly
                        $errorclass = "uncommon";
                        $error_count++;
                        break;
                    case (3)://image exists but does not match extension
                        $errorclass = "mismatch";
                        $error_count++;
                        break;
                    case (4)://image missing
                        $errorclass = "missing";
                        $error_count++;
                        break;
                    default:
                        $errorclass = "";
                } ?>

                <tr class="<?php echo($count % 2 === 0 ? "rowEven" : "rowOdd"); ?>">
                    <td class="center"><?php echo $count; ?> </td>
                    <td class="center"><?php echo $product['id']; ?> </td>
                    <td class="center" style="padding-right: 0"><?php
                        echo($product['status'] == '1' ?
                            zen_image(DIR_WS_IMAGES . 'icon_green_on.gif', IMAGE_ICON_STATUS_ON) :
                            zen_image(DIR_WS_IMAGES . 'icon_red_on.gif', IMAGE_ICON_STATUS_OFF))
                        ?>
                    </td>
                    <td class="center" style="padding-left: 0">
                        <?php echo '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . zen_get_product_path((int)$product['id']) . '&amp;product_type=1&amp;pID=' . (int)$product['id']) . '&amp;action=new_product" title="' . TEXT_EDIT_PRODUCT . '" target="_blank">' . zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_EDIT) . '</a>'; ?>
                    </td>
                    <td style="white-space: nowrap"><?php echo $product['model']; ?></td>
                    <td><?php echo $product['name']; ?> </td>
                    <td><?php echo $product['image']; ?> </td>
                    <td class="center <?php echo $errorclass; ?>"><?php echo $product['error']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div>
        <p><?php echo TEXT_PRODUCTS_CHECKED . count($products_result); ?></p>
        <p><?php echo TEXT_IMAGE_PROBLEMS . $error_count; ?></p>

        <?php } else { ?>
            <p><?php echo TEXT_NO_PRODUCTS_FOUND; ?></p>
        <?php } ?>
    </div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>