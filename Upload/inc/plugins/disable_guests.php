<?php

/***************************************************************************
 *
 *   OUGC Disable Guests plugin (/inc/plugins/disable_guests.php)
 *	 Author: Omar Gonzalez
 *   Copyright: © 2012 Omar Gonzalez
 *   
 *   Website: http://community.mybb.com/user-25096.html
 *
 *   This plugin will allow you to block access to spesific files for guests.
 *
 ***************************************************************************
 
****************************************************************************
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
****************************************************************************/

// Die if IN_MYBB is not defined, for security reasons.
defined('IN_MYBB') or die('Direct initialization of this file is not allowed.');

// PLUGINLIBRARY
defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT.'inc/plugins/pluginlibrary.php');

// Add our hook
defined('IN_ADMINCP') or $plugins->add_hook('global_end', 'disable_guests_run', -999);

// Plugin API
function disable_guests_info()
{
	global $lang;
	disable_guests_loadlang();

	return array(
		'name'			=> 'OUGC Disable Guests',
		'description'	=> $lang->disable_guests_d. (($message = disable_guests_plappend(true)) ? '<div id="flash_message" class="error">'.$message.'</div>' : ''),
		'website'		=> 'http://mods.mybb.com/view/disable-guests',
		'author'		=> 'Omar G.',
		'authorsite'	=> 'http://community.mybb.com/user-25096.html',
		'version'		=> '1.1',
		'guid'			=> '4b2cd540a65b03926a0e5bd9ab2feba6',
		'compatibility' => '16*'
	);
}

// Activate plugin
function disable_guests_activate()
{
	disable_guests_plappend();

	global $PL, $lang;
	disable_guests_loadlang();

	$PL->settings('disable_guests', $lang->disable_guests_sg, $lang->disable_guests_sg_d, array(
		'portal'	=> array(
		   'title'			=> $lang->disable_guests_s_portal,
		   'optionscode'	=> 'yesno',
		),
		'index'	=> array(
		   'title'			=> $lang->disable_guests_s_index,
		   'optionscode'	=> 'yesno',
		),
		'forumdisplay'	=> array(
		   'title'			=> $lang->disable_guests_s_forumdisplay,
		   'optionscode'	=> 'yesno',
		),
		'showthread'	=> array(
		   'title'			=> $lang->disable_guests_s_showthread,
		   'optionscode'	=> 'yesno',
		),
		'archive'	=> array(
		   'title'			=> $lang->disable_guests_s_archive,
		   'optionscode'	=> 'yesno',
		),
		'misc'	=> array(
		   'title'			=> $lang->disable_guests_s_misc,
		   'optionscode'	=> 'yesno',
		),
		'printthread'	=> array(
		   'title'			=> $lang->disable_guests_s_printthread,
		   'optionscode'	=> 'yesno',
		),
		'showteam'	=> array(
		   'title'			=> $lang->disable_guests_s_showteam,
		   'optionscode'	=> 'yesno',
		),
		'online'	=> array(
		   'title'			=> $lang->disable_guests_s_online,
		   'optionscode'	=> 'yesno',
		),
		'stats'	=> array(
		   'title'			=> $lang->disable_guests_s_stats,
		   'optionscode'	=> 'yesno',
		),
		'announcements'	=> array(
		   'title'			=> $lang->disable_guests_s_announcements,
		   'optionscode'	=> 'yesno',
		),
		'reputation'	=> array(
		   'title'			=> $lang->disable_guests_s_reputation,
		   'optionscode'	=> 'yesno',
		),
		'pollsr'	=> array(
		   'title'			=> $lang->disable_guests_s_pollsr,
		   'optionscode'	=> 'yesno',
		),
	));
}

// Install the plugin
function disable_guests_install()
{
	disable_guests_plappend();
}

// Chech installation
function disable_guests_is_installed()
{
	global $settings;

	return isset($settings['disable_guests_portal']);
}

// Uninstall plugin
function disable_guests_uninstall()
{
	disable_guests_plappend();

	global $PL;

	$PL->settings_delete('disable_guests');
}

// This functions runs when the hook is called.
function disable_guests_run()
{
	global $mybb;

	if(!$mybb->user['uid'] && (
	($mybb->settings['disable_guests_portal'] && THIS_SCRIPT == 'portal.php') ||
	($mybb->settings['disable_guests_index'] && THIS_SCRIPT == 'index.php') ||
	($mybb->settings['disable_guests_forumdisplay'] && THIS_SCRIPT == 'forumdisplay.php') ||
	($mybb->settings['disable_guests_showthread'] && THIS_SCRIPT == 'showthread.php') ||
	($mybb->settings['disable_guests_archive'] && THIS_SCRIPT == 'archive.php') ||
	($mybb->settings['disable_guests_misc'] && THIS_SCRIPT == 'misc.php') ||
	($mybb->settings['disable_guests_printthread'] && THIS_SCRIPT == 'printthread.php') ||
	($mybb->settings['disable_guests_online'] && THIS_SCRIPT == 'online.php') ||
	($mybb->settings['disable_guests_showteam'] && THIS_SCRIPT == 'showteam.php') ||
	($mybb->settings['disable_guests_stats'] && THIS_SCRIPT == 'stats.php') ||
	($mybb->settings['disable_guests_announcements'] && THIS_SCRIPT == 'announcements.php') ||
	($mybb->settings['disable_guests_reputation'] && THIS_SCRIPT == 'reputation.php') ||
	($mybb->settings['disable_guests_pollsr'] && THIS_SCRIPT == 'polls.php')
	))
	{
		error_no_permission();
	}
}

function disable_guests_plappend($bool=false)
{
	global $PL;

	$pldownurl = 'http://mods.mybb.com/view/pluginlibrary';
	$rv = 11;

	if(!file_exists(PLUGINLIBRARY))
	{
		global $lang;
		disable_guests_loadlang();

		$message = $lang->sprintf($lang->disable_guests_plreq, $pldownurl, $rv);

		if($bool)
		{
			return $message;
		}

		flash_message($message, 'error');
		admin_redirect('index.php?module=config-plugins');
	}

	$PL or require_once PLUGINLIBRARY;

	if($PL->version < $rv)
	{
		global $lang;
		disable_guests_loadlang();

		$message = $lang->sprintf($lang->disable_guests_plold, $PL->version, $rv, $pldownurl);

		if($bool)
		{
			return $message;
		}

		flash_message($message, 'error');
		admin_redirect('index.php?module=config-plugins');
	}

	if($bool)
	{
		return '';
	}
}

function disable_guests_loadlang()
{
	global $lang;

	isset($lang->disable_guests) or $lang->load('disable_guests');
}