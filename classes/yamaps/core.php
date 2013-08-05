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
	public $controls    = array();
	public $options     = array();
	public $markers     = array();
	public $geomarkers = array();
	public $baloons     = array();
	public $polylines   = array();

	// Controls
	const ZOOMCTRL      = 'zoomControl';
	const SMALLZOOMCTRL = 'smallZoomControl';
	const SEARCHCTRL    = 'searchControl';
	const TRAFFCTRL     = 'trafficControl';
	const SCALELINE     = 'scaleLine';
	const MINIMAP       = 'miniMap';
	const TYPESELECT    = 'typeSelector';
	const ROUTEEDTR     = 'routeEditor';

	const MAP             = 'map';
	const SATELLITE       = 'satellite';
	const HYBRID          = 'hybrid';
	const PUBLICMAP       = 'publicMap';
	const PUBLICMAPHYBRID = 'publicMapHybrid';

	const SCROLLZOOM      = 'scrollZoom';

	// configs
	protected $_config  = NULL;
	protected $_types   = array(
		self::MAP,
		self::SATELLITE,
		self::HYBRID,
		self::PUBLICMAP,
		self::PUBLICMAPHYBRID,
	);
	protected $_controls = array(
		self::ROUTEEDTR,
		self::TYPESELECT,
		self::ZOOMCTRL,
		self::SMALLZOOMCTRL,
		self::SCALELINE,
		self::MINIMAP,
		self::SEARCHCTRL,
		self::TRAFFCTRL,
	);



	protected $_options = array(
		self::SCROLLZOOM
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
			self::$instances[$id] = new Yamaps($id);

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
		$this->map['id'] = '#yaMap-' . $id;
		$this->_config = Kohana::$config->load('yamaps');

		$options = array();
		foreach($this->_config->options as $id => $val)
		{
			$options[] = $id.'='.$val;
		}

		$options = implode('&', $options);

		$this->map['api_url'] = $this->_config->api_protocol.'://'.$this->_config->api_domain.'/'.$this->_config->api_version.'/?lang='.$this->_config->api_lang.'&'.$options;
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
	public function type($type_name = 'map')
	{
		$this->map['type'] = 'yandex#'.Arr::get($this->_types, $type_name, 'map');
		return $this;
	}

	/**
	 * Set dimensions of the map div
	 *
	 * @param  integer $width
	 * @param  integer $height
	 * @return object  Yamaps
	 */
	public function style( array $style)
	{
		if(!Arr::get($this->map, 'style'))
			$this->map['style'] = array();

		$this->map['style'] += $style;

		return $this;
	}
	/**
	 * Set dimensions of the map div
	 *
	 * @param  integer $width
	 * @param  integer $height
	 * @return object  Yamaps
	 */
	public function map_size($width = 600, $height = 600)
	{
		if( ! Arr::get($this->map, 'style'))
			$this->map['style'] = array();

			$this->map['style'] += array(
				'width'  => intval($width).'px',
				'height' => intval($height).'px',
			);

		return $this;
	}

	/**
	 * Set icon styles
	 *
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function icon($options = array())
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
		$this->add_marker($info);

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
		$this->add_marker($info, 'geo');

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
	 * Set controll objects
	 *
	 * @param  array $controls
	 * @param  array  $options
	 *
	 * @return object Yamaps
	 */
	public function controls($controls, $options = array())
	{
		$valid_controls = Arr::extract($this->_controls, $controls, NULL);

		foreach($valid_controls as $name => $options)
			$this->controls[] = $name;

		return $this;
	}

	/**
	 * Set map options
	 *
	 * @param  array  $options_arr
	 * @param  array  $options
	 * @return object Yamaps
	 */
	public function options($options_arr, $options = array())
	{
		$valid_options = Arr::extract($this->_options, $options_arr, NULL);

		foreach($valid_options as $name => $options)
			$this->options[] = $name;

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
		if(! Arr::path($this->map, 'style.width'))
			$this->map_size();

		$style = array();
		foreach($this->map['style'] as $var => $val)
		{
			$style[] = $var.': '.$val;
		}

		if( !isset($this->map['type']))
			$this->type();

		$map  = HTML::script($this->map['api_url']);
		$map .= $this->set_map();
		$map .= '<div id="'.$this->map['id'].'" style="'.implode('; ', $style).'"></div>';

		return $map;
	}

	/**
	 * Initialize the YMap js object
	 *
	 * @return string
	 */
	public function set_map()
	{
		$staticJS = class_exists('StaticJs');
		$map = NULL;

		if(!$staticJS)
			$map = '<script type="text/javascript">';

		$map .= 'var map, placemarks = []; ymaps.ready(function () {';
		if(is_array($this->map['center'])) // Заданы координаты
		{
			$map .= 'map = new ymaps.Map ("'.$this->map['id'].'", {
			    center: ['.implode(', ', $this->map['center']).'],
			    zoom: '.$this->map['zoom'].',
			    type: "'.$this->map['type'].'"
			});';
			$map .= $this->set_controls();
			$map .= $this->set_options();
			$map .= $this->set_markers();
			$map .= $this->set_geo_markers();
		}
		elseif(is_string($this->map['center'])) // Задано название города
		{
			$map .= 'var geocoder = ymaps.geocode("'.$this->map['center'].'", {results: 1});
			geocoder.then(
			    function (res) {
			        map = new ymaps.Map ("'.$this->map['id'].'", {
			            center: res.geoObjects.get(0).geometry.getCoordinates(),
			            zoom: '.$this->map['zoom'].',
			            type: "'.$this->map['type'].'"
		            });'.
				$this->set_controls().
				$this->set_options().
				$this->set_markers().
				$this->set_geo_markers().
				'},
			    function (err) {
			        alert("Ошибка определение координат города")
			    }
			);';
		}
		$map .= '});';

		if(!$staticJS)
		{
			$map .= '</script>';
		}
		else
		{
			StaticJS::instance()->add_inline($map);
			$map = NULL;
		}

		return $map;
	}

	/**
	 * Initialize the YMap js object
	 *
	 * @return string
	 */
	public function set_type()
	{
		return "map.setType('".$this->map['type']."');";
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
			$icon = '{preset: "twirl#blueStretchyIcon"}';
		}
		else
		{
			$icon = '{
			    iconImageHref:         "'.Arr::get($this->icon, 'image_href').'", // картинка иконки
			    iconImageSize:         ['.implode(', ', Arr::get($this->icon, 'image_size', array(0,0))).'], // размеры картинки
				iconImageOffset:       ['.implode(', ', Arr::get($this->icon, 'image_offset', array(0,0))).'], // смещение картинки
				iconShadow:            "'.Arr::get($this->icon, 'shadow', 'false').'", // наличие тени
				iconShadowImageHref :  "'.Arr::get($this->icon, 'shadow_href').'", // картинка иконки
				iconShadowImageSize:   ['.implode(', ', Arr::get($this->icon, 'shadow_size', array(0,0))).'], // смещение картинки
				iconShadowImageOffset: ['.implode(', ', Arr::get($this->icon, 'shadow_offset', array(0,0))).'], // наличие тени
			}';
		}

		return $icon;
	}

	public function set_balloon($marker)
	{
		$balloon = "{
            balloonContentHeader: '".Arr::get($marker, 'header')."',
            balloonContentBody:   '".Arr::get($marker, 'body')."',
            balloonContentFooter: '".Arr::get($marker, 'footer')."'
		}";

		return $balloon;
	}

	/**
	 * Set controll object
	 *
	 * @return string/NULL Yamaps controls view
	 */
	public function set_controls()
	{
		$controls = array();
		if( ! empty($this->controls))
		{
			foreach($this->controls as $control)
				$controls[] = "map.controls.add('".$control."');";
		}
		return implode("\n", $controls);
	}

	/**
	 * Set option object
	 *
	 * @return string/NULL Yamaps options view
	 */
	public function set_options()
	{
		$options = array();
		if( ! empty($this->options))
		{
			foreach($this->options as $option)
				$options[] = 'map.behaviors.enable("'.$option.'");';
		}
		return implode("\n", $options);
	}

	/**
	 * Set marker objects
	 *
	 * @param $type
	 *
	 * @return string
	 */
	public function set_markers($type = NULL)
	{
		$makers_name = $type.'markers';
		$markers     = array();
		if($this->{$makers_name})
		{
			foreach($this->{$makers_name} as $marker)
			{
				$placemark_name = 'placemarks['.$marker['id'].']';

				$marker_js = NULL;
				if($type)
				{
					$marker_js .= '
						var myGeocoder = ymaps.geocode(
						    "'.$marker['address'].'"
//						  , {boundedBy: map.getBounds(),strictBounds: true, results: 1}
						);
						myGeocoder.then(function(res){
							if (res.geoObjects.getLength()) {
					            // point - первый элемент коллекции найденных объектов
					            var coordinates_'.$marker['id'].' = res.geoObjects.get(0).geometry.getCoordinates();
					        }
						';
				}
				else
				{
					$marker_js .= 'var coordinates_'.$marker['id'].' = ['.implode(', ', $marker['geo']).'];';
				}

				$marker_js .= '
					'.$placemark_name.' = new ymaps.Placemark(
					    coordinates_'.$marker['id'].'
//					  , {hintContent: "'.$marker['header'].'"
					  , '.$this->set_balloon($marker).'
					  , '.$this->set_icon().'
					  , {draggable: false/*, hideIconOnBalloonOpen: false*/}
					);
					map.geoObjects.add('.$placemark_name.');';

				if($type)
				{
					$marker_js .= '});';
				}

				$markers[] = $marker_js;
			}
		}

		return implode("\n", $markers);
	}

	/**
	 * Set geo marker objects
	 *
	 * @return string/NULL Yamaps geocoded markers view
	 */
	public function set_geo_markers()
	{
		return $this->set_markers('geo');
	}

	/**
	 * Create marker object using coordinates
	 *
	 * @param array  $info
	 * @param string $type
	 */
	private function add_marker($info, $type = '')
	{
		$maker_name = $type.'markers';
		$id         = Arr::get($info, 'id', substr(md5(rand(0, 1000)), 0, 4));
		$info       = Arr::unshift($info, 'id', $id);

		$this->{$maker_name}[] = $info;
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