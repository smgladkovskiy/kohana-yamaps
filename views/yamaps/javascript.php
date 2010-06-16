<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

// Initialize the YMap
<?php echo $map, "\n" ?>
<?php echo $center, "\n" ?>

// Show map points
<?php foreach($markers as $marker): ?>
<?php echo $marker->render(1), "\n" ?>
<?php endforeach ?>
