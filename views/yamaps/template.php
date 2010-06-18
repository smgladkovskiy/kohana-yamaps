<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php echo HTML::script($yamap->map['api_url'])?>
<script type="text/javascript">
YMaps.jQuery(function () {

	// Initialize the YMap
	<?php echo $yamap->set_map()?>

	// Set map center
	<?php echo $yamap->set_center()?>

	// Set map icon style
	<?php echo $yamap->set_icon()?>

	// Show map controlls
	<?php echo $yamap->set_controlls()?>

	// Show map options
	<?php echo $yamap->set_options()?>

	// Show map points
	<?php echo $yamap->set_markers()?>
	<?php echo $yamap->set_geo_markers()?>
});
</script>
<!-- [START] Yandex map container -->
<div id="<?php echo $yamap->map['id']?>" style="width:<?php echo $yamap->map['style']['width']?>px;height:<?php echo $yamap->map['style']['height']?>px"></div>
<!-- [END] Yandex map container -->