<?php
// Plugin: Image Checker
// Spanish

define('HEADING_TITLE', 'Comprobar Imágenes');
define('TEXT_VERSION', 'versión ');
define('TEXT_IMAGES_DIRECTORY','Carpeta Imágenes: '. DIR_FS_CATALOG_IMAGES);

define('TEXT_LIST_ALL_PRODUCTS', 'Mostrar Todos los Productos:');
define('TEXT_LIST_DISABLED_PRODUCTS', 'Mostrar los Productos Deshabilitados También:');
define('TEXT_PRODUCTS_CHECKED', 'Productos comprobados: ');
define('TEXT_IMAGE_PROBLEMS', 'Problemas con imágenes: ');
define('TEXT_QUERY_LIMITED', 'Resultados limitados a <b>%s</b> productos.</b>');

define('TEXT_NO_PRODUCTS_FOUND', 'No se ha encontrado productos en este base de datos.');
define('TEXT_NO_ERRORS_FOUND', 'There were no image errors found. All products with images defined refer to a valid image file, with the correct extension and of a common web image format.');

define('ERROR_NO_ERROR', 'ok');
define('ERROR_NO_IMAGE_DEFINED', 'Imagen no definido');
define('ERROR_IMAGE_NOT_FOUND', 'No encontrado');
define('ERROR_NOT_IMAGE', 'Archivo tiene una extensión <b>%s</b> pero no es una imagen válida (dice getimagesize)');
define('ERROR_IMAGE_FORMAT', 'Imagen tiene una extensión <b>%s</b> pero en realidad es un <b>%s</b>!');
define('ERROR_NOT_COMMON_FORMAT', 'Un archivo <b>%s</b> no es un formato común en página web (mejor PNG/GIF/JPG o BMP).');

define('TABLE_HEADING_ENTRY', 'Entrada');
define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_MODEL', 'Modelo');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_NAME', 'Nombre');
define('TABLE_HEADING_IMAGE', 'Imagen');
define('TABLE_HEADING_RESULT', 'Resultado');
