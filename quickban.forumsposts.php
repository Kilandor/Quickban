<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.loop
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
	$t->assign('FORUMS_POSTS_ROW_POSTERIP', cot_rc($R['quickban_url_lastip'], array('lastip' => $t->get('FORUMS_POSTS_ROW_POSTERIP'), 'url' => cot_url('plug', 'e=quickban&ip='.$row['fp_posterip'].'&'.cot_xg()))));
}

?>