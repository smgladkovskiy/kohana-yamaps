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
			->center(37.64, 55.76)
			->controlls(array('Zoom', 'TypeControl'))
			->options(array('ScrollZoom'))
			;

		$map->geo_marker(array(
				'address' => 'Москва',
				'name' => 'Moscow city',
				'description' => 'Russian Federation capital',
			));
		
		$map->marker(array(
				'geo' => array(37.60, 55.70),
				'name' => 'Some place to visit',
				'description' => 'Damn! I forgot what it is!',
			));

		$this->request->response = $map->render();
	}

} // End Controller_Yamaps