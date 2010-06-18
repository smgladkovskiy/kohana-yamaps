<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Yamaps Core
 *
 * @package Yamaps
 * @author avis <smgladkovskiy@gmail.com>
 */
class Yamaps_Core {

	public static $instances = array();

	// Map data
	public $icon        = NULL;
	public $map         = array();
	public $controlls   = array();
	public $options     = array();
	public $markers     = array();
	public $geo_markers = array();
	public $baloons     = array();
	public $polylines   = array();

	// configs
	protected $_config  = NULL;
	protected $_types   = array(
		'Y_MAP','Y_SATELLITE','Y_HYBRID'
	);
	protected $_controlls = array(
		'TypeControl', 'ToolBar', 'Zoom', 'MiniMap', 'ScaleLine', 'SearchControl',
	);
	protected $_options = array(
		'ScrollZoom', //@todo: describe all options
	);

	/**
	 * Yamaps class instance builder
	 *
	 * @param  mixed  $id
	 * @return object Yamaps
	 */
	public static function instance($id = 1)
	{
		if( ! isset(self::$instances[$id]) OR ! is_object(self::$instances[$id]))
		{
			self::$instances[$id] = new Yamaps($id);
		}

		return self::$instances[$id];
	}

	/**
	 * Yamaps constructor
	 *
	 * @param  mixed $id
	 * @return void
	 */
	public function __construct($id)
	{
		$this->map['id'] = 'Ymap-' . $id;
		$this->_config = Kohana::config('yamaps');

		$this->map['api_url'] = 'http://'.$this->_config->api_domain.'/'.$this->_config->version.'/index.xml?key=' . $this->_config->api_key;
	}

	/**
	 * Set map zoom
	 *
	 * @param  integer $zoom
	 * @return object  Yamaps
	 */
	public function zoom($zoom = 6)
	{
		$this->map['zoom'] = (int) $zoom;
		return $this;
	}

	/**
	 * Set map type
	 *
	 * @param  string $type_name
	 * @return object Yamaps
	 */
	public function type($type_name = 'Y_MAP')
	{
		$this->map['type'] = arr::extract($this->_types, $type_name, 'Y_MAP');
		return $this;
	}

	/**
	 * Set dimensions of the map div
	 *
	 * @param  integer $width
	 * @param  integer $height
	 * @return object  Yamaps
	 */
	public function style($width = 600, $height = 600)
	{
		$this->map['style'] = array(
			'width' => (int) $width,
			'height' => (int) $height,
		);
		return $this;
	}

	/**
	 * Set icon styles
	 *
	 * @todo   Add $options processing
	 * @param  array  $optins
	 * @return object Yamaps
	 */
	public function icon($optins = NULL, $options = array())
	{
		$this->icon = $options;

		return $this;
	}

	/**
	 * Create marker based on geo coordinates
	 *
	 * @param  array  $info
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function marker($info, $options = array())
	{
		$this->add_marker($info, $options);
		
		return $this;
	}

	/**
	 * Create marker based on address
	 *
	 * @param  array  $info
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function geo_marker($info, $options = array())
	{
		$this->add_geo_marker($info, $options);
		
		return $this;
	}

	/**
	 * Balloon creating dummy
	 */
	public function baloon()
	{
		return $this;
	}

	/**
	 * Polyline creating dummy
	 */
	public function polyline()
	{
		return $this;
	}

	/**
	 * Set map center based on geo coordinates
	 *
	 * @param  integer $lat
	 * @param  integer $lon
	 * @return object  Yamaps
	 */
	public function center($lat, $lon)
	{
		$this->map['center'] = array($lat, $lon);
		
		return $this;
	}

	/**
	 * Set map center based on geo address
	 *
	 * @param  integer $lat
	 * @param  integer $lon
	 * @return object  Yamaps
	 */
	public function geo_center($address)
	{
		$this->map['center'] = HTML::chars($address);

		return $this;
	}

	/**
	 * Set controll object
	 *
	 * @param  mixed  $controll
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function controll($controll, $options = array())
	{
		$this->controlls[] = array(
			'name'    => $controll,
			'options' => $options
		);
		
		return $this;
	}

	/**
	 * Set map option
	 *
	 * @param  mixed  $option
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function option($option, $options = array())
	{
		$this->options = array(
			'name'    => $option,
			'options' => $options
		);

		return $this;
	}

	/**
	 * Render map
	 *
	 * @return string Yamaps template view
	 */
	public function render()
	{
		// Force map style setting
		if(! isset($this->map['style']))
			$this->style();

		// Force icon setting
		if($this->icon === NULL)
			$this->get_icon();

		return View::factory('yamaps/template')
			->bind('yamap', $this);
	}

	/**
	 * Initialize the YMap js object
	 *
	 * @return string
	 */
	public function set_map()
	{
		return 'var map = new YMaps.Map(YMaps.jQuery("#' . $this->map['id'] .'")[0]);';
	}

	/**
	 * Set map center
	 *
	 * @return string
	 */
	public function set_center()
	{
		if(is_array($this->map['center']))
		{
			return 'map.setCenter(new YMaps.GeoPoint(' . implode(', ', $this->map['center']) . '), ' . $this->map['zoom'] .');';
		}
		else
		{
			$center = '
		var geocenter = new YMaps.Geocoder(\'' . $this->map['center']. '\');
		YMaps.Events.observe(geocenter, geocenter.Events.Load, function () {
			if (this.length()) {
				map.setCenter(this.get(0).getGeoPoint(), ' . $this->map['zoom'] .');
			}
		})
				';
			return $center;
		}
	}

	/**
	 * Set icon style
	 *
	 * @return string Yamaps icon view
	 */
	public function set_icon()
	{
		if($this->icon === NULL)
		{
			$icon = NULL;
		}
		else
		{
			$icon = View::factory('yamaps/icon')->bind('icon', $this->icon);
		}

		return $icon;
	}

	/**
	 * Set controll object
	 *
	 * @return string/NULL Yamaps controlls view
	 */
	public function set_controlls()
	{
		return ( ! empty($this->controlls))
			? View::factory('yamaps/controlls')
				->bind('controlls', $this->controlls)
			: NULL;
	}

	/**
	 * Set option object
	 *
	 * @return string/NULL Yamaps options view
	 */
	public function set_options()
	{
		return ( ! empty($this->options))
			? View::factory('yamaps/options')
				->bind('options', $this->options)
			: NULL;
	}

	/**
	 * Set marker objects
	 *
	 * @return string/NULL Yamaps coordinate markers view
	 */
	public function set_markers()
	{
		return (! empty($this->markers))
			? View::factory('yamaps/marker/coordinates')
				->bind('markers', $this->markers)
				->bind('icon', $this->icon)
			: NULL;
	}

	/**
	 * Set geo marker objects
	 *
	 * @return string/NULL Yamaps geocoded markers view
	 */
	public function set_geo_markers()
	{
		return (! empty($this->geo_markers))
			? View::factory('yamaps/marker/geocoded')
				->bind('markers', $this->geo_markers)
				->bind('icon', $this->icon)
			: NULL;
	}

	/**
	 * Create marker object using coordinates
	 *
	 * @todo   Add $options processing
	 * @param  array $info
	 * @param  array $options
	 * @return void
	 */
	private function add_marker($info, $options)
	{
		$id = Arr::get($info, 'id', substr(md5(rand(0, 1000)), 0, 4));
		$info = Arr::unshift($info, 'id', $id);
		$this->markers[] = View::factory('yamaps/marker/coordinates')
			->bind('icon', $this->_config->icon)
			->bind('marker', $info);
	}

	/**
	 * Create marker object using geocoding
	 *
	 * @todo   Add $options processing
	 * @param  array $info
	 * @param  array $options
	 * @return void
	 */
	private function add_geo_marker($info, $options)
	{
		$id = Arr::get($info, 'id', substr(md5(rand(0, 1000)), 0, 4));
		$info = Arr::unshift($info, 'id', $id);
		$this->geo_markers[] = $info;
	}

	/**
	 * Populate icon information
	 * 
	 * @return void
	 */
	private function get_icon()
	{
		if($this->icon === NULL AND ! empty($this->_config->icon))
			$this->icon = $this->_config->icon;
	}
} // End Yamaps_core