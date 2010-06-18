<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
/**
 * You should set your own API key in application/config/ymaps.php
 * This API key is usable for http://localhost/*
 */
'api_key' => '****==',

/**
 * Your own icon on the map
 */
'icon' => array(
//	'name' => 'someIconName',
//	'href'   => "/images/up.png",
//	'size'   => array(18, 29),
//	'offset' => array(-9, -29),
//	'shadow' => array(
//		'href' => "/images/up.png",
//		'size' => array(18, 29),
//		'offset' => array(-9, -29),
//	),
),

/**
 * Using a localised yandex domain gives more accurated results on geolocation
 */
'api_domain' => 'api-maps.yandex.ru',


/**
 * This is used version of API.
 */
'version' => '1.1',
);