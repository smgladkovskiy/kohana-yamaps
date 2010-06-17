<?php defined('SYSPATH') or die('No direct access allowed.');?>
		// Создает стиль
		var s_<?php echo $icon['name']?> = new YMaps.Style();

		// Создает стиль значка метки
		s_<?php echo $icon['name']?>.iconStyle = new YMaps.IconStyle();
		s_<?php echo $icon['name']?>.iconStyle.href = "<?php echo $icon['href']?>";
		s_<?php echo $icon['name']?>.iconStyle.size = new YMaps.Point(<?php echo implode(', ', $icon['size'])?>);
		s_<?php echo $icon['name']?>.iconStyle.offset = new YMaps.Point(<?php echo implode(', ', $icon['offset'])?>);

<?php if(isset($icon['shadow']) and ! empty($icon['shadow'])):?>
		s_<?php echo $icon['name']?>.iconStyle.shadow = new YMaps.IconShadowStyle();
		s_<?php echo $icon['name']?>.iconStyle.shadow.href = "<?php echo $icon['shadow']['href']?>";
		s_<?php echo $icon['name']?>.iconStyle.shadow.size = new YMaps.Point(<?php echo implode(', ', $icon['shadow']['size'])?>);
		s_<?php echo $icon['name']?>.iconStyle.shadow.offset = new YMaps.Point(<?php echo implode(', ', $icon['shadow']['offset'])?>);
<?php endif;?>