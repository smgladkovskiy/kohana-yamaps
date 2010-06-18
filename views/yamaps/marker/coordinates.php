<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php foreach($markers as $marker):?>
			var placemark_<?php echo $marker['id']?> = new YMaps.Placemark(new YMaps.GeoPoint(<?php echo implode(', ', $marker['geo'])?>)
				<?php if(isset($icon['name'])):?>, {style: s_<?php echo $icon['name']?>}<?php endif;?>
			);
			placemark_<?php echo $marker['id']?>.name = '<?php echo $marker['name']?>';
			placemark_<?php echo $marker['id']?>.description = '<?php echo $marker['description']?>';

			map.addOverlay(placemark_<?php echo $marker['id']?>);
<?php endforeach;?>