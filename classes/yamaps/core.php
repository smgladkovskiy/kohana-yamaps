<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Yamaps Core
 *
 * @package Yamaps
 * @author avis <smgladkovskiy@gmail.com>
 */
class Yamaps_Core {

	public static $instances = array();

	public $icon;
	public $map = array();
	public $controlls = array();
	public $options = array();
	public $markers = array();
	public $baloons = array();

	protected $_config;
	protected $_types = array(
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
	 * @uses   icon   section in config by default
	 * @param  array  $optins
	 * @return object Yamaps
	 */
	public function icon($optins = NULL)
	{
		if($optins === NULL)
		{
			$this->icon = (isset($this->_config->icon['href']))
				? View::factory('yamaps/icon')->bind('icon', $this->_config->icon)
				: NULL;
		}
		else
		{
			$this->icon = View::factory('yamaps/icon')->bind('icon', $options);
		}

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
	public function marker_geo($info, $options = array())
	{
		$this->add_marker_geo($info, $options);
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
	 * Set map center coordinates
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
	 * Set controll object
	 *
	 * @param  mixed  $controll
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function controll($controll, $options = array())
	{
		$this->add_controll($controll, $options);
		
		return $this;
	}

	/**
	 * Set map option
	 *
	 * @param  mixed  $name
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function option($name, $options = array())
	{
		$this->add_option($name, $options);

		return $this;
	}

	/**
	 * Render map
	 *
	 * @return string Yamaps template view
	 */
	public function render()
	{
		if(! isset($this->map['style']))
			$this->style();

		if(! isset($this->icon))
			$this->icon();
		
		return View::factory('yamaps/template')
			->bind('yamap', $this);
	}

	/**
	 * Create controll object
	 *
	 * @todo   Add $options processing
	 * @param  mixed $controll
	 * @param  array $options
	 * @return void
	 */
	private function add_controll($controll, $options = array())
	{
		if(is_string($controll))
		{
			if(in_array($controll, $this->_controlls, TRUE))
				$this->controlls[] = 'map.addControl(new YMaps.' . $controll . '());';
		}

		if(is_array($controll))
		{
			foreach($controll as $controll_item)
			{
				$this->add_controll($controll_item, $options);
			}
		}
	}

	/**
	 * Create option object
	 *
	 * @todo   Add $options processing
	 * @param  mixed $name
	 * @param  array $options
	 * @return void
	 */
	private function add_option($name, $options = array())
	{
		if(is_string($name))
		{
			if(in_array($name, $this->_options, TRUE))
				$this->options[] = 'map.enable' . $name . '());';
		}

		if(is_array($name))
		{
			foreach($name as $option_item)
			{
				$this->add_controll($option_item);
			}
		}
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
		$info['id'] = substr(md5(rand(0, 1000)), 0, 4);
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
	private function add_marker_geo($info, $options)
	{
		$info['id'] = substr(md5(rand(0, 1000)), 0, 4);
		$this->markers[] = View::factory('yamaps/marker/geocoded')
			->bind('icon', $this->_config->icon)
			->bind('marker', $info);
	}

} // End Yamaps_core