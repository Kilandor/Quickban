<?php

defined('COT_CODE') or die('Wrong URL');

$R['quickban_url_name'] = '{$name} &nbsp; <a href="{$url}" style="vertical-align:middle;"><img src="'.$cfg['plugins_dir'].'/quickban/images/quickban.png" alt="" /></a>';
$R['quickban_url_lastip'] = '{$lastip} &nbsp; <a href="{$url}" style="vertical-align:middle;"><img src="'.$cfg['plugins_dir'].'/quickban/images/quickban.png" alt="" /></a>';
$R['quickban_url_title'] = '{$title} &nbsp; <a href="{$url}" style="vertical-align:middle;"><img src="'.$cfg['plugins_dir'].'/quickban/images/quickban.png" alt="" /></a>';

?>