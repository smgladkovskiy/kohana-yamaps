<?php defined('SYSPATH') or die('No direct access allowed.');?>
<?php foreach($controlls as $controll):?>
		map.addControl(new YMaps.<?php echo $controll['name']?>());
<?php endforeach;?>