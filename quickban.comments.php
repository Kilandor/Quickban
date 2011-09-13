<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=comments.loop
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
	$t->assign('COMMENTS_ROW_AUTHOR', cot_rc($R['quickban_url_name'], array('name' => $t->get('COMMENTS_ROW_AUTHOR'), 'url' => cot_url('plug', 'e=quickban&user='.$row['com_author'].'&'.cot_xg()))));
}

?>