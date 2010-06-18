<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php foreach($markers as $marker):?>
		var geocoder = new YMaps.Geocoder('<?php echo $marker['address']?>', {results: 1, boundedBy: map.getBounds()});
		YMaps.Events.observe(geocoder, geocoder.Events.Load, function () {
			if (this.length()) {
				var placemark_<?php echo $marker['id']?> = new YMaps.Placemark(this.get(0).getGeoPoint()
					<?php if( ! empty($icon)):?>, {style: s_<?php echo $icon['name']?>}<?php endif;?>
				);
				placemark_<?php echo $marker['id']?>.name = '<?php echo $marker['name']?>';
				placemark_<?php echo $marker['id']?>.description = '<?php echo $marker['description']?>';

				map.addOverlay(placemark_<?php echo $marker['id']?>);
			}
		})
<?php endforeach;?>