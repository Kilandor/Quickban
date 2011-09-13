<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=usertags.main
[END_COT_EXT]
==================== */

/**
 * Quickly ban and cleanup users
 *
 * @package quickban
 * @version 1.0
 * @author Jason Booth(Kilandor)
 * @copyright Copyright (c) Jason Booth(Kilandor) 2007-2011
 * @license BSD
 */
 
defined('COT_CODE') or die("Wrong URL.");

if(cot_auth('plug', 'quickban', 'A'))
{
	if(cot_import('e', 'G', 'ALP') != 'quickban')
		if(is_array($user_data) && $user_data['user_id'] > 0 && !empty($user_data['user_name']))
		{
			require cot_incfile('quickban', 'plug', 'resources');
			$temp_array['NAME'] = cot_rc($R['quickban_url_name'], array('name' => $temp_array['NAME'], 'url' => cot_url('plug', 'e=quickban&user='.$user_data['user_name'].'&'.cot_xg())));
			$temp_array['LASTIP'] = cot_rc($R['quickban_url_lastip'], array('lastip' => $temp_array['LASTIP'], 'url' => cot_url('plug', 'e=quickban&ip='.$user_data['user_lastip'].'&'.cot_xg())));
		}
}


?>
