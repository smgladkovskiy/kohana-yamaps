<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php foreach($options as $options):?>
		map.enable<?php echo $option['name']?>();
<?php endforeach;?>