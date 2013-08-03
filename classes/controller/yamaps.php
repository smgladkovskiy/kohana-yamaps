<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Yamaps module demo controller. This controller should NOT be used in production.
 * It is for demonstration purposes only!
 *
 * @package    Yamaps
 * @author avis <smgladkovskiy@gmail.com>
 */
class Controller_Yamaps extends Controller {

	// Do not allow to run in production
	const ALLOW_PRODUCTION = FALSE;

	public function action_index()
	{
		// Create a new Ymap
		$map = Yamaps::instance()
			->zoom(10)
			->center(55.76, 37.64)
			->controls(array(Yamaps::ZOOMCTRL, Yamaps::TYPESELECT))
			->options(array(Yamaps::SCROLLZOOM))
			;

		$map->geo_marker(array(
				'address' => 'Москва',
				'header' => 'Moscow city',
				'body' => 'Russian Federation capital',
			));

		$map->marker(array(
				'geo' => array(55.70, 37.60),
				'header' => 'Some place to visit',
				'body' => 'Damn! I forgot what it is!',
			));

		$body = $map->render();
		if(class_exists('StaticJs'))
			$body .= StaticJs::instance()->get_all();

		$this->response->body($body);
	}

} // End Controller_Yamaps