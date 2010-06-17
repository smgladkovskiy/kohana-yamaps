<?php defined('SYSPATH') or die('No direct access allowed.');?>
			// Placemark
			var geocoder_<?php echo $marker['id']?> = new YMaps.Geocoder('<?php echo $marker['geo']?>'
			<?php if(isset($icon['name'])):?>, {style: s_<?php echo $icon['name']?>}<?php endif;?>
			);

			// Baloon
			geocoder_<?php echo $marker['id']?>.name = '<?php echo $marker['name']?>';
			geocoder_<?php echo $marker['id']?>.description = '<?php echo $marker['description']?>';

			// Adding Placemark on map
			map.addOverlay(geocoder_<?php echo $marker['id']?>);
