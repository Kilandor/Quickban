<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.edit.tags
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
	require cot_incfile('quickban', 'plug', 'resources');
	$t->assign('USERS_EDIT_NAME', cot_rc($R['quickban_url_name'], array('name' => $t->get('USERS_EDIT_NAME'), 'url' => cot_url('plug', 'e=quickban&user='.$urr['user_name'].'&'.cot_xg()))));
	$t->assign('USERS_EDIT_LASTIP', cot_rc($R['quickban_url_lastip'], array('lastip' => $t->get('USERS_EDIT_LASTIP'), 'url' => cot_url('plug', 'e=quickban&ip='.$urr['user_lastip'].'&'.cot_xg()))));
}

?>
