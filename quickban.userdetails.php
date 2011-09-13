<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.details.tags
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
	$t->assign('USERS_DETAILS_TITLE', cot_rc($R['quickban_url_title'], array('title' => $t->get('USERS_DETAILS_TITLE'), 'url' => cot_url('plug', 'e=quickban&user='.$urr['user_name'].'&'.cot_xg()))));
}

?>