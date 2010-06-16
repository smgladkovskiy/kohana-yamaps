<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<meta http-equiv="refresh" content="30">
	<title>Yandex Maps JavaScript API Example</title>
	<script src="<?php echo $api_url ?>" type="text/javascript"></script>
	<script type="text/javascript">
    window.onload = function () {
	<?php echo $map ?>
    }
	</script>
	<style type="text/css">
	body, html { width:100%; height:100%; margin:0px}
	</style> 
</head>

<body>

<div id="map" style="width: 600px; height: 600px;"></div>

</body>
</html>