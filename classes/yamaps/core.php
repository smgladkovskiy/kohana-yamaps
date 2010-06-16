<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Yandex Maps API integration.
 *
 * @package    Ymaps
 * @author     vit1251 <vit1251@gmail.com>
 * @author     avis <smgladkovskiy@gmail.com>
 */
class Yamaps_Core {

	// Map settings
	protected $id;
	protected $center;

	// Map types
	protected $types = array();
	protected $default_types = array
	(
		'Y_MAP','Y_SATELLITE','Y_HYBRID'
	);

	// Map markers
	protected $markers = array();

	// Map plylines
	protected $polylines = array();

	private static $_config;
	/**
	 * Set the YMap center point.
	 *
	 * @param string $id HTML map id attribute
	 * @return void
	 */
	public function __construct($id = 'map')
	{
		// Set map ID and options
		$this->id = $id;
		$this->_config = Kohana::config('yamaps');
	}

	/**
	 * Return GMap javascript url
	 *
	 * @param   string  API component
	 * @param   array   API parameters
	 * @return  string
	 */
	 public static function api_url($component = 'index.xml', $parameters = NULL, $separator = '&amp;')
	 {
		$config = Kohana::config('yamaps');
		if (empty($parameters['key']))
		{
			// Set the API key last
			$parameters['key'] = $config->api_key;
		}

		return 'http://'.$config->api_domain.'/'.$config->version.'/'.$component.'?'.http_build_query($parameters, '', $separator);
	 }


	/**
	 * Set the GMap center point.
	 *
	 * @chainable
	 * @param float $lat latitude
	 * @param float $lon longitude
	 * @param integer $zoom zoom level (1-7)
	 * @param string $type default map type
	 * @return object
	 */
	public function center($lon, $lat, $zoom = 6, $type = 'Y_MAP')
	{
		$zoom = max(0, min(17, abs($zoom)));
		$type = ($type != 'Y_MAP' AND in_array($type, $this->default_types, true)) ? $type : 'Y_MAP';

		// Set center location, zoom and default map type
		$this->center = array($lat, $lon, $zoom, $type);

		return $this;
	}

	/**
	 * Set the YMap marker point.
	 *
	 * @chainable
	 * @param float $lat latitude
	 * @param float $lon longitude
	 * @param string $html HTML for info window
	 * @param array $options marker options
	 * @return object
	 */
	public function add_marker($lat, $lon, $html = '', $options = array())
	{
		// Add a new marker
		$this->markers[] = new Yamaps_Marker($lat, $lon, $html, $options);

		return $this;
	}

	public function add_polyline($points, $options = array())
	{
		// Create new Polyline object
		$polyline = new Yamaps_Polyline( $options );

		// Add point
		foreach ($points as $point)
		{
			$polyline->add_point($point->lat, $point->lon);
		}

		// Add a new marker
		$this->polylines[] = $polyline;

		return $this;
	}

	/**
	 * Render the map into GMap Javascript.
	 *
	 * @param string $template template name
	 * @param array $extra extra fields passed to the template
	 * @return string
	 */
	public function render($template = 'yamaps/javascript', $extra = array())
	{
		// Latitude, longitude, zoom and default map type
		list ($lat, $lon, $zoom, $default_type) = $this->center;

		// Map
		$map = 'var map = new YMaps.Map(document.getElementById("'.$this->id.'"));';

		// Map centering
		$center = 'map.setCenter(new YMaps.GeoPoint('.$lon.', '.$lat.'), '.$zoom.');';

		$data = array_merge($extra, array
			(
				'map' => $map,
				'center' => $center,
				'markers' => $this->markers,
				'polylines' => $this->polylines,
			));

		// Render the Javascript
		return View::factory($template, $data);
	}

} // End Ymap
