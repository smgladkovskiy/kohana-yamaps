<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Yandex Maps Marker
 *
 * @package    Yamaps
 * @author     vit1251 <vit1251@gmail.com>
 * @author     avis <smgladkovskiy@gmail.com>
 */
class Yamaps_Marker_Core {

	// Marker HTML
	public $html;

	// Latitude and longitude
	public $latitude;
	public $longitude;
	
	// Marker ID
	protected static $id = 0;
	
	// Marker Options
	protected $options = array();
	protected $valid_options = array
	(
		'draggable',
		'style',
	);

	/**
	 * Create a new YMap marker.
	 *
	 * @param float $lat latitude
	 * @param float $lon longitude
	 * @param string $html HTML of info window
	 * @param array $options marker options
	 * @return  void
	 */
	public function __construct($lat, $lon, $html, $options = array())
	{
		if ( ! is_numeric($lat) OR ! is_numeric($lon))
			throw new Kohana_Exception('ymaps.invalid_marker', $lat, $lon);

		// Set the latitude and longitude
		$this->latitude = $lat;
		$this->longitude = $lon;

		$this->html = $html;

		if (count($options) > 0)
		{
			foreach ($options as $option => $value) 
			{
				// Set marker options
				if (in_array($option, $this->valid_options, true))
					$this->options[] = "$option:$value";
			}
		}
	}

	public function render($tabs = 0)
	{
		// Create the tabs
		$tabs = empty($tabs) ? '' : str_repeat("\t", $tabs);

		// Marker ID
		$marker = 'm'.++self::$id;

                $output[] = 'var '.$marker.' = new YMaps.Placemark(new YMaps.GeoPoint('.$this->longitude.', '.$this->latitude.'), {'.join(',', $this->options).'});';

		if (isset($this->options['title']) && $title = $this->options['title'])
		{
			$output[] = $marker.'.name = "'.$title.'";';
		}

		if ($html = $this->html)
		{
			$output[] = $marker.'.description = "'.$html.'";';
		}
		$output[] = 'map.addOverlay('.$marker.');';

		return implode("\n".$tabs, $output);
	}

} // End Gmap Marker