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
			->controll('Zoom')
			;

		$map->marker(array(
				'geo' => array(37.64, 55.76),
				'name' => 'Tsest',
				'description' => 'Test description',
			));
		$map->marker(array(
				'geo' => array(37.60, 55.70),
				'name' => 'Tsest2',
				'description' => 'Test2 description',
			));

		$this->request->response = $map->render();
	}

} // End Controller_Yamaps