Yamaps - is a module for Kohana v3 framework to wor with Yandex maps API.

Some demo in Controller_Yamaps

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