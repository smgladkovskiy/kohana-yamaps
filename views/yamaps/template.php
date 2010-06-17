<?php defined('SYSPATH') or die('No direct access allowed.');?>
<script src="<?php echo $yamap->map['api_url']?>" type="text/javascript"></script>
<script src="http://www.json.org/json2.js" type="text/javascript"></script>
<script type="text/javascript">
	YMaps.jQuery(function () {

		// Initialize the YMap
		var map = new YMaps.Map(YMaps.jQuery("#<?php echo $yamap->map['id']?>")[0]);

		// Set map center
		map.setCenter(new YMaps.GeoPoint(<?php echo implode(', ', $yamap->map['center'])?>), <?php echo $yamap->map['zoom']?>);

		// Set map icon style
		<?php echo ($yamap->icon !== NULL) ? $yamap->icon : '// default';?>

		// Show map controlls
		<?php if(! empty($yamap->controlls))
		foreach($yamap->controlls as $controll)
		{
			echo $controll . "\n";
		}
		?>

		// Show map options
		<?php if(! empty($yamap->options))
		foreach($yamap->options as $option)
		{
			echo $option;
		}?>

		// Show map points
<?php if(! empty($yamap->markers))
		foreach($yamap->markers as $marker)
		{
			echo $marker . "\n";
		}?>
	});
</script>

<div id="<?php echo $yamap->map['id']?>" style="width:<?php echo $yamap->map['style']['width']?>px;height:<?php echo $yamap->map['style']['height']?>px"></div>