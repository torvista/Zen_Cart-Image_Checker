<?php
// Plugin: Image Checker
// English

define('HEADING_TITLE', 'Images Checker');
define('TEXT_VERSION', 'version ');
define('TEXT_IMAGES_DIRECTORY','Images path: '. DIR_FS_CATALOG_IMAGES);

define('TEXT_LIST_TYPE', 'Check:');
define('TEXT_CHECK_CATEGORIES', 'Categories');
define('TEXT_CHECK_PRODUCTS', 'Products');
define('TEXT_FILTERS', 'Filters:');
define('TEXT_LIST_ALL', 'List all:');
define('TEXT_LIST_DISABLED', 'List disabled:');
define('TEXT_LIST_NO_IMAGES', 'List entries with no images defined:');
define('TEXT_ENTRIES_CHECKED', 'Entries checked: ');
define('TEXT_IMAGE_PROBLEMS', 'Image problems: ');
define('TEXT_QUERY_LIMITED', 'Results limited to <b>%s</b> entries.</b>');

define('TEXT_NO_ERRORS_FOUND', '<p>There were no image errors found.<br />All entries with images defined refer to a valid image file, with the correct extension and of a common web image format.</p>');

define('ERROR_NO_ERROR', 'ok');
define('ERROR_NO_IMAGE_DEFINED', 'No image defined');
define('ERROR_IMAGE_NOT_FOUND', 'Image not found');
define('ERROR_NOT_IMAGE', 'File is named as a <b>%s</b> but is not a valid image (as per getimagesize)');
define('ERROR_IMAGE_FORMAT', 'Image is named with a <b>%s</b> extension but is actually a <b>%s</b>!');
define('ERROR_NOT_COMMON_FORMAT', 'A <b>%s</b> file is not a type commonly used for web images (change to PNG/GIF/JPG or even BMP).');

define('TABLE_HEADING_ENTRY', 'Entry');
define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_IMAGE', 'Image');
define('TABLE_HEADING_RESULT', 'Result');

define('TEXT_EDIT_CATEGORY', 'Edit Category');
define('TEXT_EDIT_PRODUCT', 'Edit Product');