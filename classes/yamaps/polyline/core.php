<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Yandex Maps API integration.
 *
 * @package    Yamaps
 * @author     vit1251 <vit1251@gmail.com>
 * @author     avis <smgladkovskiy@gmail.com>
 */
class Yamaps_Polyline_Core {

	// Polyline points
	protected $points;

	// Polyline ID
	protected static $id = 0;

	// Polyline Options
	protected $options = array();
	protected $valid_options = array
	(
		'style',
	);

	/**
	 * Create a new YMap polyline.
	 *
	 * @param array $options marker options
	 * @return  void
	 */
	public function __construct($options = array())
	{
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

	/**
	 * Set the YMap polyline point.
	 *
	 * @param float $lat latitude
	 * @param float $lon longitude
	 * @return object
	 */
	public function add_point($lat, $lon)
	{
		$this->points[] = 'new YMaps.GeoPoint('.$lon.', '.$lat.')';

		return $this;
	}

	public function render($tabs = 0)
	{
		// Polyline ID
		$polyline = 'p'.++self::$id;

		$output[] = 'var '.$polyline.' = new YMaps.Polyline([';
		$output[] = join(',', $this->points);
		$output[] = '])';
		$output[] = 'map.addOverlay('.$polyline.');';

		return join("\n", $output);
	}

} // End Ymap Polyline
