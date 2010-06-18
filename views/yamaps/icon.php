<?php defined('SYSPATH') or die('No direct access allowed.');?>
		// Create Style
		var s_<?php echo $icon['name']?> = new YMaps.Style();

		// Create Style of point image
		s_<?php echo $icon['name']?>.iconStyle = new YMaps.IconStyle();
		<?php if(isset($icon['href'])):?>s_<?php echo $icon['name']?>.iconStyle.href = "<?php echo $icon['href']?>";<?php endif;?>
		<?php if(isset($icon['size'])):?>s_<?php echo $icon['name']?>.iconStyle.size = new YMaps.Point(<?php echo implode(', ', $icon['size'])?>);<?php endif;?>
		<?php if(isset($icon['offset'])):?>s_<?php echo $icon['name']?>.iconStyle.offset = new YMaps.Point(<?php echo implode(', ', $icon['offset'])?>);<?php endif;?>

<?php if(isset($icon['shadow']) and ! empty($icon['shadow'])):?>
		s_<?php echo $icon['name']?>.iconStyle.shadow = new YMaps.IconShadowStyle();
		<?php if(isset($icon['shadow']['href'])):?>s_<?php echo $icon['name']?>.iconStyle.shadow.href = "<?php echo $icon['shadow']['href']?>";<?php endif;?>
		<?php if(isset($icon['shadow']['size'])):?>s_<?php echo $icon['name']?>.iconStyle.shadow.size = new YMaps.Point(<?php echo implode(', ', $icon['shadow']['size'])?>);<?php endif;?>
		<?php if(isset($icon['shadow']['offset'])):?>s_<?php echo $icon['name']?>.iconStyle.shadow.offset = new YMaps.Point(<?php echo implode(', ', $icon['shadow']['offset'])?>);<?php endif;?>
<?php endif;?>