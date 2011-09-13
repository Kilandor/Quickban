<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
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

(defined('COT_CODE') || defined('COT_PLUG')) or die('Wrong URL.');

//Require Plugin Admin Access
cot_block($usr['isadmin']);
require_once cot_incfile('forms');
require_once cot_incfile('users', 'module');

$active['forums'] = cot_module_active('forums');
$active['page'] = cot_module_active('page');
$active['pfs'] = cot_module_active('pfs');
$active['pm'] = cot_module_active('pm');

$active['banlist'] = cot_plugin_active('banlist');
$active['comments'] = cot_plugin_active('comments');
$active['ipsearch'] = cot_plugin_active('ipsearch');
$active['userimage'] = cot_plugin_active('userimages');

if($active['forums'])
	require_once cot_langfile('forums', 'module');
if($active['comments'])
	require_once cot_langfile('comments');

$out['subtitle'] = $L['quickban_subtitle'];

//Import Information
$a = cot_import('a', 'G', 'TXT');
$ip = cot_import('ip', 'G', 'TXT');
$user = cot_import('user', 'G','TXT', 100);

/* === Hook === */
foreach (cot_getextplugins('quickban.first') as $pl)
{
	include $pl;
}
/* ===== */

if(!empty($ip))
	$sql_users = $db->query("SELECT * FROM $db_users where user_lastip = '".$db->prep($ip)."'");
elseif(!empty($user))
	$sql_users = $db->query("SELECT * FROM $db_users where user_name = '".$db->prep($user)."' LIMIT 1");
else
	cot_error('quickban_noinput', 'ip/user');

if(!cot_error_found())
{
	if($a == 'confirm')
	{
		cot_check_xg();
		
		$options['userdata'] = cot_import('userdata', 'P', 'BOL');
		$options['pfs'] = cot_import('pfs', 'P', 'BOL');
		$options['userimage'] = cot_import('userimage', 'P', 'BOL');
		$options['banlist'] = cot_import('banlist', 'P', 'BOL');
		$options['forums'] = cot_import('forums', 'P', 'BOL');
		$options['page'] = cot_import('page', 'P', 'BOL');
		$options['comments'] = cot_import('comments', 'P', 'BOL');
		$options['pm'] = cot_import('pm', 'P', 'BOL');
		
		if(!empty($ip))
			cot_log('Banned IP '.$ip, 'adm');
		elseif(!empty($user))
			cot_log('Banned User '.$user, 'adm');
		
		foreach ($sql_users->fetchAll() as $row)
		{
			$counts['QUICKBAN_COUNT_USERS']++;
			
			//Set Main/Sub group to banned
			$db->delete($db_groups_users, 'gru_userid='.$row['user_id']);
			$db->update($db_users, array('user_maingrp' => COT_GROUP_BANNED), 'user_id='.$row['user_id']);
			$db->insert($db_groups_users, array('gru_userid' => $row['user_id'], 'gru_groupid' => COT_GROUP_BANNED));
			
			if($options['userdata'])
			{
				//Null any extrafield data and delete any possible files
				foreach($cot_extrafields[$db_users] as $row_extf) 
				{ 
					cot_extrafield_unlinkfiles($row['user_'.$row_extf['field_name']], $row_extf);
					$user_data['user_'.$row_extf['field_name']] = '';
				}
				//Clear Signature
				$user_data['user_text'] = '';
				$db->update($db_users, $user_data, 'user_id='.$row['user_id']);
			}
			
			//Delete all PFS Files
			if($active['pfs'] && $options['pfs'])
			{
				require_once cot_incfile('pfs', 'module');
				$counts['QUICKBAN_COUNT_FILES'] += cot_pfs_deleteall($row['user_id']);
			}
			
			//Delete all User Images
			if($active['userimage'] && $options['userimage'])
			{
				require_once cot_incfile('userimages', 'plug');
				$userimg_cfg = cot_userimages_config_get(true);
				foreach($userimg_cfg as $code => $settings)
				{
					$sql = $db->query("SELECT user_".$db->prep($code)." FROM $db_users WHERE user_id=".$row['user_id']);
					if($filepath = $sql->fetchColumn())
					{
						if (file_exists($filepath))
						{
							unlink($filepath);
							$counts['QUICKBAN_COUNT_FILES'] += 1;
						}
						$sql = $db->update($db_users, array('user_'.$db->prep($code) => ''), "user_id=".$row['user_id']);
					}
				}
			}
			
			//IP Ban and Email ban
			if($active['banlist'] && $options['banlist'])
			{
				$db->insert($db_banlist, array(
				'banlist_ip' => $row['user_lastip'],
				'banlist_email' => $row['user_email'],
				'banlist_reason' => $L['quickban_reason'],
				'banlist_expire' => 0
				));
			}
			
			//Remove all forum posts
			if($active['forums'] && $options['forums'])
			{
				require_once cot_incfile('forums', 'module');
				$counts['QUICKBAN_COUNT_TOPICS'] += $db->delete($db_forum_topics, 'ft_firstposterid='.$row['user_id']);
				$counts['QUICKBAN_COUNT_POSTS'] += $db->delete($db_forum_posts, 'fp_posterid='.$row['user_id']);
				
				$sql_sync = $db->query("SELECT structure_code FROM $db_structure WHERE structure_area='forums'");
				foreach ($sql->fetchAll() as $row_sync)
				{
					$sync_cat = $row_sync['structure_code'];
					cot_forums_sectionsetlast($sync_cat, "fs_postcount", "fs_topiccount");
					$sync_items = cot_forums_sync($cat);
					$db->update($db_structure, array("structure_count" => (int)$sync_items), "structure_code='".$db->prep($sync_cat)."' AND structure_area='forums'");
				}
			}
			//Remove all pages
			if($active['page'] && $options['page'])
			{
				require_once cot_incfile('page', 'module');
				$counts['QUICKBAN_COUNT_PAGES'] += $db->delete($db_pages, 'page_ownerid='.$row['user_id']);
				
				$sql_sync = $db->query("SELECT structure_code FROM $db_structure WHERE structure_area='page'");
				foreach ($sql->fetchAll() as $row_sync)
				{
					$sync_cat = $row_sync['structure_code'];
					$sync_items = cot_page_sync($cat);
					$db->update($db_structure, array("structure_count" => (int)$sync_items), "structure_code='".$db->prep($sync_cat)."' AND structure_area='page'");
				}
			}
			//Remove all Comments
			if($active['comments'] && $options['comments'])
			{
				require_once cot_incfile('comments', 'plug');
				$counts['QUICKBAN_COUNT_COMMENTS'] += $db->delete($db_com, 'com_authorid='.$row['user_id']);
			}
			
			//Remove all Private messages
			if($active['pm'] && $options['pm'])
			{
				require_once cot_incfile('pm', 'module');
				$counts['QUICKBAN_COUNT_PM'] += $db->delete($db_pm, 'pm_fromuserid='.$row['user_id']);
			}
			
			/* === Hook === */
			foreach (cot_getextplugins('quickban.cleanup') as $pl)
			{
				include $pl;
			}
			/* ===== */
		}
		$t->assign($counts);
		
		/* === Hook === */
		foreach (cot_getextplugins('quickban.cleanup.tags') as $pl)
		{
			include $pl;
		}
/* ===== */
		
		$t->parse('MAIN.COMPLETE');
	}
	else
	{
		foreach ($sql_users->fetchAll() as $row)
		{
			$t->assign(array(
				'USER_IPSEARCH' => $active['ipsearch'] ? cot_build_ipsearch($row['user_lastip']) : ''
			));
			$row['user_hideemail'] = false; //Override to show, we are admins we can see it anyways
			$t->assign(cot_generate_usertags($row, 'USER_'));
			
			/* === Hook === */
			foreach (cot_getextplugins('quickban.confirm.loop') as $pl)
			{
				include $pl;
			}
			/* ===== */
			
			$t->parse('MAIN.CONFIRM.USERS');
		}
		
		$url_ip = (!empty($ip)) ? '&ip='.$ip : '';
		$url_user = (!empty($user)) ? '&user='.$user : '';
		$t->assign(array(
			'QUICKBAN_URL' => cot_url('plug', 'e=quickban&a=confirm'.$url_ip.$url_user.'&'.cot_xg()),
			'QUICKBAN_USERDATA' => cot_checkbox(true, 'userdata'),
			'QUICKBAN_PFS' => cot_checkbox(true, 'pfs'),
			'QUICKBAN_USERIMAGE' => cot_checkbox(true, 'userimgage'),
			'QUICKBAN_BANLIST' => cot_checkbox(true, 'banlist'),
			'QUICKBAN_FORUMS' => cot_checkbox(true, 'forums'),
			'QUICKBAN_PAGE' => cot_checkbox(true, 'page'),
			'QUICKBAN_COMMENTS' => cot_checkbox(true, 'comments'),
			'QUICKBAN_PM' => cot_checkbox(true, 'pm')
		));
		
		/* === Hook === */
		foreach (cot_getextplugins('quickban.tags') as $pl)
		{
			include $pl;
		}
		/* ===== */
		
		$t->parse('MAIN.CONFIRM');
	}
	$sql_users->closeCursor();
}

?>