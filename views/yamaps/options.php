<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php foreach($options as $option):?>
		map.enable<?php echo $option['name']?>();
<?php endforeach;?>