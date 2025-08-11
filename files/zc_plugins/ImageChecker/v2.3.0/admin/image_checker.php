<?php

declare(strict_types=1);

/**
 * Plugin: Image Checker
 * @link https://github.com/torvista/Zen_Cart-Image_Checker
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @updated 03 August 2025 torvista
 */

/** directives for phpStorm code inspector
 ** @var queryFactory $db
 */

const IMAGE_CHECKER_VERSION = '2.3.0';

//for faster debugging: restrict the search to use a smaller result set
$limit_search = 0; // Integer or 0 for normal operation. Ignored when list all selected

/* originally based on
 * Missing Images Checker for ZenCart
 * By Paul Williams (retched) with additions by Zen4All
 *...but radically butchered by torvista
 */

// This file reads the image link path associated with each product and
// a) checks if anything is defined
// b) if defined, checks if the file referenced exists, if it is an image, if it is named correctly, and if it is a common file type.
//
// The script reports any discrepancies.
// To get the most of this file, you should disable PHP's safe mode as this file
// may take an excessively long time if you're running this script on a LARGE
// database. Large being anywhere near 2000 or more products. In addition,
// you should also probably run this on a dedicated server or a local testing
// server as opposed to online.
//
// Also note that **NO CHANGES ARE MADE TO YOUR DATABASE**. This script is read-only.

//////////////////////////////////////////////////////////////
require('includes/application_top.php');

$allowed_image_types = [
    IMAGETYPE_BMP,
    IMAGETYPE_GIF,
    IMAGETYPE_JPEG,
    IMAGETYPE_PNG,
    IMAGETYPE_WEBP,
];

///////////////////////////////////////////////////////////////////////////

/* getimagesize possible returned constant values
    [IMAGETYPE_GIF] => 1
    [IMAGETYPE_JPEG] => 2
    [IMAGETYPE_PNG] => 3
    [IMAGETYPE_SWF] => 4
    [IMAGETYPE_PSD] => 5
    [IMAGETYPE_BMP] => 6
    [IMAGETYPE_TIFF_II] => 7
    [IMAGETYPE_TIFF_MM] => 8
    [IMAGETYPE_JPC] => 9
    [IMAGETYPE_JP2] => 10
    [IMAGETYPE_JPX] => 11
    [IMAGETYPE_JB2] => 12
    [IMAGETYPE_SWC] => 13
    [IMAGETYPE_IFF] => 14
    [IMAGETYPE_WBMP] => 15
    [IMAGETYPE_JPEG2000] => 9
    [IMAGETYPE_XBM] => 16
    [IMAGETYPE_ICO] => 17
    [IMAGETYPE_WEBP] => 18
    [IMAGETYPE_AVIF] => 19
    [IMAGETYPE_UNKNOWN] => 0
    [IMAGETYPE_COUNT] => 20
 */
$getimagesize_types = [
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
    18 => 'WEBP',
    19 => ' AVIF',
    20 => 'COUNT'
];
// get the value of checkbox to show all products or only those with image issues
//echo '$_GET[\'listAllProducts\']=' . $_GET['listAllProducts'] . '<br>';
//echo '$_POST[\'listAllProducts\']=' . $_POST['listAllProducts'] . '<br>';

$list_categories = ((isset($_GET['listType']) && $_GET['listType'] === 'categories'));
//$list_categories = false;//override
$list_products = !$list_categories;

$list_all = isset($_GET['listAll']);
//$list_all = true;//override

// get the value of checkbox to show disabled products too (if not a full listing)
$list_disabled = isset($_GET['listDisabled']);

// get the value of checkbox to show disabled products too (if not a full listing)
$list_no_images = isset($_GET['listNoImages']);

// parse the database and images
$process = !empty($_GET['process']);
if ($process) {
//echo __LINE__ . ': $list_categories=' . $list_categories . ', $list_products=' . $list_products . ', $list_all=' . $list_all . ', $list_disabled=' . $list_disabled . ', $list_no_images=' . $list_no_images . '<br>';
    $list_disabled_clause = '';
    $list_no_images_clause = '';

    if ($list_products && !$list_all) { // products: filter by product status=1
        $list_disabled_clause = ($list_disabled ? '' : ' AND p.products_status = 1');//if checkbox ticked, no filter
        $list_no_images_clause = ($list_no_images ? '' : ' AND p.products_image >""');//if checkbox ticked, no filter
    }

    if ($list_categories && !$list_all) {//if only errors selected, allow filtering by product status
        $list_disabled_clause = ($list_disabled ? '' : ' AND c.categories_status = 1');//if checkbox ticked, no filter
        $list_no_images_clause = ($list_no_images ? '' : ' AND c.categories_image >""');//if checkbox ticked, no filter
    }
    $limit_clause = ($limit_search && !$list_all ? ' LIMIT ' . $limit_search : '');//disable if pagination likely

    if ($list_categories) {
        $sql_query_raw = "SELECT c.categories_id, c.categories_image, c.categories_status, cd.categories_name
                 FROM " . TABLE_CATEGORIES . " c
                 LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON c.categories_id = cd.categories_id
                 WHERE cd.language_id = " . (int)$_SESSION['languages_id'] . $list_disabled_clause . $list_no_images_clause . " ORDER BY cd.categories_name" . $limit_clause;
    } else {
        $sql_query_raw = "SELECT p.products_id, p.products_model, p.products_image, p.products_status, pd.products_name
                 FROM " . TABLE_PRODUCTS . " p
                 LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id
                 WHERE pd.language_id = " . (int)$_SESSION['languages_id'] . $list_disabled_clause . $list_no_images_clause . " ORDER BY p.products_model" . $limit_clause;
    }

    if ($list_all) { // no filtering/show all results
        $limit_clause = '';//for debugging only, remove for split page results, which adds its own limit
        if (isset($_GET['page']) && ($_GET['page'] > 1)) {
            $rows = ((int)$_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS) - MAX_DISPLAY_SEARCH_RESULTS;// todo $rows????
        }
        $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $sql_query_raw, $sql_query_numrows);
        //$results_counter = (($_GET['page'] - 1) * MAX_DISPLAY_SEARCH_RESULTS);
    } else {
        // $results_counter = 1;
    }
//echo __LINE__ . ': $sql_query_raw=' . $sql_query_raw . '<br>';
    $results = $db->Execute($sql_query_raw);
    $results_info = [];
    if ($list_categories) {
        foreach ($results as $result) {
            //$results_counter++;
            $results_info[] = [
                // "entry" => $results_counter,
                "id" => $result['categories_id'],
                //"model" => $result['products_model'],
                "image" => $result['categories_image'],
                "status" => $result['categories_status'],
                "name" => $result['categories_name']
            ];
        }
    } else {
        foreach ($results as $result) {
            $results_info[] = [
                // "entry" => $results_counter,
                "id" => $result['products_id'],
                "model" => $result['products_model'],
                "image" => $result['products_image'],
                "status" => $result['products_status'],
                "name" => $result['products_name']
            ];
            //$results_counter++;
        }
    }

//echo __LINE__ . ': Before Processing<pre>$results_info<br>';mv_printVar($results_info);
    /*
    * iterate over each product:
     *      1. Determine if the file exists. (file_exists($file_path))
     *      2. Determine if the image retrieved is actually an image.
     *         (getimagesize($path))
     *      3. Determine if the image is stored in the correct format. (An image with
     *         a .PNG should register as a PNG.)
     */
//echo __LINE__ . ' <pre>$results_info';print_r($results_info);echo '</pre><hr />';

    $error_count = 0;
    foreach ($results_info as $key => $value) {//add $results_info['image_status'] and $results_info['error'] into array
        //echo __LINE__ . ': In Processing<pre>$value<br>';print_r($value);echo '</pre>';

//$results_info['image_status']
//0 - file exists and is good
//1 - no file defined
//2 - file exists but not browser friendly
//3 - file exists, but the real file type does not match extension
//4 - file missing

        $file = DIR_FS_CATALOG_IMAGES . $value['image'];

        if ($value['image'] === null || trim($value['image']) === '') {
            $results_info[$key]['image_status'] = 1;
            $results_info[$key]['error'] = ERROR_NO_IMAGE_DEFINED;
            $error_count++;
        } elseif (file_exists($file)) {
            $image_check = getimagesize($file); // getimagesize returns 0 => height, 1 => width, and 2 => type.
            $image_type = $image_check[2];//third element of the array

            //echo $results_info[$key]['image'] . ', $image_type=' . $image_type . '<br>';

            $file_ext = strtolower(
                substr(
                    strrchr($file, '.'),
                    1
                )
            );  // Retrieve the file extension, convert it to lowercase.

//check image-naming (extensions) against the actual file type
            if (!$image_type) {//getimagesize does not recognise this as an image
                $results_info[$key]['error'] = sprintf(ERROR_NOT_IMAGE, $file_ext);
                $results_info[$key]['image_status'] = 3;
                $error_count++;
            } elseif (!in_array($image_type, $allowed_image_types, true)) {//The image found is not one of the common web types
                $results_info[$key]['error'] = sprintf(ERROR_NOT_COMMON_FORMAT, $file_ext);
                $results_info[$key]['image_status'] = 2;
                $error_count++;
            } elseif ($file_ext === "gif" && $image_type !== IMAGETYPE_GIF) {//image extension = GIF, but it isn't
                $results_info[$key]['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
                $results_info[$key]['image_status'] = 3;
                $error_count++;
            } elseif ($image_type !== IMAGETYPE_JPEG && (in_array($file_ext, [
                    "jpg",
                    "jpeg",
                    "jpe",
                    "jfif",
                    "jif"
                ]))) {//image extension = JPEG-type, but it isn't
                $results_info[$key]['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
                $results_info[$key]['image_status'] = 3;
                $error_count++;
            } elseif ($file_ext === "png" && $image_type !== IMAGETYPE_PNG) {//image extension = PNG, but it isn't
                $results_info[$key]['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
                $results_info[$key]['image_status'] = 3;
                $error_count++;
            } elseif ($file_ext === "bmp" && $image_type !== IMAGETYPE_BMP) {//image extension = BMP, but it isn't
                $results_info[$key]['error'] = sprintf(ERROR_IMAGE_FORMAT, $file_ext, $getimagesize_types[$image_type]);
                $results_info[$key]['image_status'] = 3;
                $error_count++;
            } elseif ($list_all) {
                $results_info[$key]['error'] = ERROR_NO_ERROR;
                $results_info[$key]['image_status'] = 0;
            } else {
                unset ($results_info[$key]);
            }
        } else {
            $results_info[$key]['image_status'] = 4;
            $results_info[$key]['error'] = ERROR_IMAGE_NOT_FOUND;
            $error_count++;
        }
        set_time_limit(30);
// If you're not running PHP in Safe Mode, reset the timer to 30 seconds and start again.
// This is to allow for larger databases to be parsed.
// TODO: a better solution. Will likely involve a bit of refreshing and sending to the $_POST.
    }
}
?>
<!doctype html>
<html <?= HTML_PARAMS ?>>
<head>
    <?php
    require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>

</head>

<body>
<!-- header //-->
<?php
require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
    <div><h1><?= HEADING_TITLE ?></h1>
        <p><?= HEADER_TITLE_VERSION . ': ' . IMAGE_CHECKER_VERSION ?></p>
        <?= TEXT_IMAGES_DIRECTORY ?>
        <?php
        if ($limit_search > 0) { ?>
            <br><p class="messageStackError"><?= '$limit_search=' . $limit_search ?></p>
            <?php
        } ?>
    </div>
    <div>
        <?= TEXT_INTRO ?>
        <?= zen_draw_form('options', FILENAME_IMAGE_CHECKER, zen_get_all_get_params(['listType', 'listAll', 'listDisabled', 'listNoImages']), 'get', 'id="options"') ?>
        <fieldset>
            <legend><?= TEXT_LIST_TYPE ?></legend>
            <label for="listTypeCategories"><?= TEXT_CATEGORIES; ?></label>
            <?php
            echo zen_draw_radio_field('listType', 'categories', $list_categories, '', 'id="listTypeCategories" onchange="this.form.submit();"'); ?>
            <label for="listTypeProducts"><?= TABLE_HEADING_PRODUCTS ?></label>
            <?php
            echo zen_draw_radio_field('listType', 'products', $list_products, '', 'id="listTypeProducts" onchange="this.form.submit();"'); ?>
        </fieldset>
        <fieldset>
            <legend><?= TEXT_FILTERS ?></legend>
            <label for="listAll"><?= TEXT_LIST_ALL ?></label>
            <?php
            echo zen_draw_checkbox_field('listAll', '1', $list_all, '', 'id="listAll" onchange="this.form.submit();"'); ?>
            &nbsp;
            <?php
            if (!$list_all) {//do not show filter if listing all products anyway ?>
                <label for="listDisabled"><?= TEXT_LIST_DISABLED ?></label>
                <?= zen_draw_checkbox_field('listDisabled', '1', $list_disabled, '', 'id="listDisabled" onchange="this.form.submit();"') ?>&nbsp;
                <label for="listNoImages"><?= TEXT_LIST_NO_IMAGES ?></label>
                <?php
                echo zen_draw_checkbox_field('listNoImages', '1', $list_no_images, '', 'id="listNoImages" onchange="this.form.submit();"');
            } ?>
            <!--
        <label for="limitResults">Limit search</label>
        <?php
            //echo zen_draw_input_field('limitSearch', $limit_search, ($limit_search ? 'class="messageStackCaution"' : ''), 'id="limitSearch" onblur="this.form.submit();"'); ?>-->
        </fieldset>
        <?php
        echo zen_draw_hidden_field('process', 1);
        echo '<button type="submit" class="btn btn-primary">' . IMAGE_GO . '</button>';
        echo '</form>' ?>
    </div>

    <?php
    if (isset($results) && $results->RecordCount() > 0) { ?>
        <p><?= sprintf(($list_categories ? TEXT_RESULTS_CATEGORIES_COUNT : TEXT_RESULTS_PRODUCTS_COUNT), $sql_query_numrows ?? $results->RecordCount()) ?></p>

        <?php
        if ($list_all) { ?>
            <div style="float: right;">
                <?php
                echo $products_split->display_count(
                    $sql_query_numrows,
                    MAX_DISPLAY_SEARCH_RESULTS,
                    $_GET['page'],
                    ($list_categories ? TEXT_DISPLAY_NUMBER_OF_CATEGORIES : TEXT_DISPLAY_NUMBER_OF_PRODUCTS)
                );
                echo $products_split->display_links(
                    $sql_query_numrows,
                    MAX_DISPLAY_SEARCH_RESULTS,
                    MAX_DISPLAY_PAGE_LINKS,
                    $_GET['page'],
                    zen_get_all_get_params(['page'])
                ); ?>
            </div>
            <?php
        }

        if ($error_count > 0 || $list_all) {
            ?>
            <div><p class="missing"><?= TEXT_IMAGE_PROBLEMS . $error_count ?></p></div>
            <div>
                <table id="resultsTable">
                    <thead>
                    <tr class="dataTableHeadingRow">
                        <th class="center minWidth"><?= TABLE_HEADING_ID ?></th>
                        <th class="minWidth" colspan="2"><?= TABLE_HEADING_STATUS ?></th>
                        <?php
                        if ($list_products) { ?>
                            <th class="minWidth"><?= TABLE_HEADING_MODEL ?></th>
                            <?php
                        } ?>
                        <th><?= TABLE_HEADING_NAME ?></th>
                        <th><?= TABLE_HEADING_IMAGE ?></th>
                        <th><?= TABLE_HEADING_RESULT ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($results_info as $key => $value) {
                        switch ($value['image_status']) {
                            case (0)://image found and extension matches type
                                $errorclass = "valid";
                                break;
                            case (1)://no image defined
                                $errorclass = "undefined";
                                break;
                            case (2)://image exists but type not browser friendly
                                $errorclass = "uncommon";
                                break;
                            case (3)://image exists but does not match extension
                                $errorclass = "mismatch";
                                break;
                            case (4)://image missing
                                $errorclass = "missing";
                                break;
                            default:
                                $errorclass = "";
                        } ?>

                        <tr class="dataTableRow">
                            <td class="center"><?= $value['id'] ?></td>
                            <td class="center" style="padding-right: 0;"><?= ($value['status'] === '1' ?
                                    zen_image(DIR_WS_IMAGES . 'icon_green_on.gif', IMAGE_ICON_STATUS_ON) :
                                    zen_image(DIR_WS_IMAGES . 'icon_red_on.gif', IMAGE_ICON_STATUS_OFF))
                                ?>
                            </td>
                            <td class="center" style="padding-left: 0;">
                                <?php
                                if ($list_categories) {
                                    //echo 'zen_get_path($value[\'id\']) ='.zen_get_path($value['id']).'<br>';
                                    //echo 'zen_generate_category_path($value[\'id\']) ='; echo '<pre>'; print_r(zen_generate_category_path($value['id'])); echo '</pre>';
                                    //echo 'zen_output_generated_category_path($value[\'id\']) ='.zen_output_generated_category_path($value['id']).'<br>';

                                    $categories_path_array = zen_generate_category_path($value['id']);
                                    $parent_cPath = $categories_path_array[0][0]['id'];

                                    echo '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $parent_cPath . '&amp;cID=' . (int)$value['id']) . '&amp;action=edit_category" title="' . TEXT_EDIT_CATEGORY . '" target="_blank">' . zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_EDIT) . '</a>';
                                } else { // is product
                                    echo '<a href="' . zen_href_link(FILENAME_PRODUCT, 'cPath=' . zen_get_product_path((int)$value['id']) . '&amp;product_type=1&amp;pID=' . (int)$value['id']) . '&amp;action=new_product" title="' . TEXT_EDIT_PRODUCT . '" target="_blank">' . zen_image(
                                            DIR_WS_IMAGES . 'icon_edit.gif',
                                            ICON_EDIT
                                        ) . '</a>';
                                } ?>
                            </td>
                            <?php
                            if ($list_products) { ?>
                                <td style="white-space: nowrap;"><?= $value['model'] ?></td>
                                <?php
                            } ?>
                            <td><?= ($list_categories ? zen_output_generated_category_path($value['id']) . '<br>' : $value['name']) ?></td>
                            <td><?= $value['image'] ?></td>
                            <td class="<?= $errorclass ?>"><?= $value['error'] ?></td>
                        </tr>
                        <?php
                    } ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        if ($error_count === 0) { ?>
            <br><p class="messageStackSuccess"><?= TEXT_NO_ERRORS_FOUND ?></p>
            <?php
        }

        if ($list_all) { ?>
            <div style="float: right;">
                <?php
                echo $products_split->display_count(
                    $sql_query_numrows,
                    MAX_DISPLAY_SEARCH_RESULTS,
                    $_GET['page'],
                    TEXT_DISPLAY_NUMBER_OF_PRODUCTS
                );
                echo $products_split->display_links(
                    $sql_query_numrows,
                    MAX_DISPLAY_SEARCH_RESULTS,
                    MAX_DISPLAY_PAGE_LINKS,
                    $_GET['page'],
                    zen_get_all_get_params(['page'])
                ); ?>
            </div>
            <?php
        }
    } elseif (isset($results)) {
        echo($list_categories ? TEXT_NO_CATEGORIES_FOUND : TEXT_NO_PRODUCTS_FOUND);
    } ?>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php
require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
