<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
/**
 * You should set your own API key in application/config/ymaps.php
 * This API key is usable for http://localhost/*
 */

'api_key' => '****==',

/**
 * Using a localised google domain gives more accurated results on geolocation
 * For example, searches for "Toledo" will return different results within the domain of Spain (http://maps.google.es) 
 * specified by a country code of "es" than within the default domain within the United States (http://maps.google.com).
 */

'api_domain' => 'api-maps.yandex.ru',


/**
 * This is used version of API.
 */

'version' => '1.1',
);