<?php
// Plugin: Image Checker
// English

define('HEADING_TITLE', 'Image Checker');
define('TEXT_VERSION', 'version ');
define('TEXT_IMAGES_DIRECTORY', 'Images path: ' . DIR_FS_CATALOG_IMAGES);
define('TEXT_INTRO', '<p>"Image <em>Problems</em>": the product has an image defined, but that image is missing or the defined image is not the correct filetype.</p><p>Filters are enabled by default, to review only <em>enabled</em> categories/products.</p>');
define('TEXT_LIST_TYPE', 'Check:');
define('TEXT_CATEGORIES', 'Categories');
define('TEXT_PRODUCTS', 'Products');
define('TEXT_FILTERS', 'Filters:');
define('TEXT_LIST_ALL', 'Show All');
define('TEXT_LIST_DISABLED', 'Show Disabled items (with image problems)');
define('TEXT_LIST_NO_IMAGES', 'Show items with no images defined');
define('TEXT_ENTRIES_CHECKED', 'Items checked: ');
define('TEXT_IMAGE_PROBLEMS', 'Image problems: ');
define('TEXT_QUERY_LIMITED', 'Results limited to <b>%s</b> items.</b>');
define('TEXT_RESULTS_COUNT', '%1$s checked: %2$s.');

define('TEXT_NO_ERRORS_FOUND', 'There were no image errors found.<br>All entries with images defined refer to an existing image file, with the correct extension (the filetype) and of a common web image format.');

define('ERROR_NO_ERROR', 'ok');
define('ERROR_NO_IMAGE_DEFINED', 'No image defined');
define('ERROR_IMAGE_NOT_FOUND', 'Image not found');
define('ERROR_NOT_IMAGE', 'File is named as a <b>%s</b> but is not a valid image (as per getimagesize)');
define('ERROR_IMAGE_FORMAT', 'Image is named with a <b>%s</b> extension but is actually a <b>%s</b>!');
define('ERROR_NOT_COMMON_FORMAT', 'A <b>%s</b> file is not a type commonly used for web images (change to PNG/GIF/JPG or even BMP).');

define('TABLE_HEADING_ENTRY', 'Entry');
define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_IMAGE', 'Image');
define('TABLE_HEADING_RESULT', 'Result');

define('TEXT_EDIT_CATEGORY', 'Edit Category');
define('TEXT_EDIT_PRODUCT', 'Edit Product');
define('TEXT_NO_CATEGORIES_FOUND', 'No matching categories found.');
define('TEXT_NO_PRODUCTS_FOUND', 'No matching products found.');
