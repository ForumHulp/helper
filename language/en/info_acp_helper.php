<?php
/**
*
* @package Forumhulp Helper
* @copyright (c) 2015 ForumHulp.com
* @license Proprietary
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'FH_DISABLE_ALL'		=> 'Disable all extensions', 

	'LOG_CORE_INSTALLED'	=> '<strong>Forumhulp Helper</strong><br />» Files succesfully changed',
	'LOG_CORE_DEINSTALLED'	=> '<strong>Forumhulp Helper</strong><br />» Files succesfully restored',
	'LOG_CORE_NOT_REPLACED'	=> '<strong>Forumhulp Helper</strong><br />» Could not replaced file(s)<br />» %s',
	'LOG_CORE_NOT_UPDATED'	=> '<strong>Forumhulp Helper</strong><br />» Could not update file(s)<br />» %s',

	'ERROR_DISABLE'			=> 'You can not disable ForumHulp Helper because of active ForumHulp extensions<br /><div style="margin: 0px auto; width: 50%%; text-align: left;">» %s</div>'
));
