<?php

declare(strict_types=1);

/**
 * Plugin: Image Checker
 * @link https://github.com/torvista/Zen_Cart-Image_Checker
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @updated 25/08/2024 torvista
 */

$define = [
    'HEADING_TITLE' => 'Image Checker',
    'TEXT_VERSION' => 'version ',
    'TEXT_IMAGES_DIRECTORY' => 'Images path: ' . DIR_FS_CATALOG_IMAGES,
    'TEXT_INTRO' => '<p>"Image <em>Problems</em>": the product has an image defined, but that image is missing or the defined image is not the correct filetype.</p><p>Filters are enabled by default, to review only <em>enabled</em> categories/products.</p>',
    'TEXT_LIST_TYPE' => 'Check:',
    'TEXT_CATEGORIES' => 'Categories',
    'TEXT_PRODUCTS' => 'Products',
    'TEXT_FILTERS' => 'Filters:',
    'TEXT_LIST_ALL' => 'Show All',
    'TEXT_LIST_DISABLED' => 'Show Disabled items (with image problems)',
    'TEXT_LIST_NO_IMAGES' => 'Show items with no images defined',
    'TEXT_ENTRIES_CHECKED' => 'Items checked: ',
    'TEXT_IMAGE_PROBLEMS' => 'Image problems: ',
    'TEXT_QUERY_LIMITED' => 'Results limited to <b>%s</b> items.</b>',
    'TEXT_RESULTS_COUNT' => '%1$s checked: %2$s.',
    'TEXT_NO_ERRORS_FOUND' => 'There were no image errors found.<br>All entries with images defined refer to an existing image file, with the correct extension (the filetype) and of a common web image format.',
    'ERROR_NO_ERROR' => 'ok',
    'ERROR_NO_IMAGE_DEFINED' => 'No image defined',
    'ERROR_IMAGE_NOT_FOUND' => 'Image not found',
    'ERROR_NOT_IMAGE' => 'File is named as a <b>%s</b> but is not a valid image (as per getimagesize)',
    'ERROR_IMAGE_FORMAT' => 'Image is named with a <b>%s</b> extension but is actually a <b>%s</b>!',
    'ERROR_NOT_COMMON_FORMAT' => 'A <b>%s</b> file is not a type commonly used for web images (change to PNG/GIF/JPG or even BMP).',
    'TABLE_HEADING_ENTRY' => 'Entry',
    'TABLE_HEADING_ID' => 'ID',
    'TABLE_HEADING_STATUS' => 'Status',
    'TABLE_HEADING_NAME' => 'Name',
    'TABLE_HEADING_IMAGE' => 'Image',
    'TABLE_HEADING_RESULT' => 'Result',
    'TEXT_EDIT_CATEGORY' => 'Edit Category',
    'TEXT_EDIT_PRODUCT' => 'Edit Product',
    'TEXT_NO_CATEGORIES_FOUND' => 'No matching categories found.',
    'TEXT_NO_PRODUCTS_FOUND' => 'No matching products found.',
];

return $define;
