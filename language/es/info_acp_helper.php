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

	'LOG_CORE_INSTALLED'	=> '<strong>Forumhulp Helper</strong><br />» Archivos cambiados correctamente',
	'LOG_CORE_DEINSTALLED'	=> '<strong>Forumhulp Helper</strong><br />» Archivos restaurados correctamente',
	'LOG_CORE_NOT_REPLACED'	=> '<strong>Forumhulp Helper</strong><br />» No se pudo reemplazar el/los archivo(s)<br />» %s',
	'LOG_CORE_NOT_UPDATED'	=> '<strong>Forumhulp Helper</strong><br />» No se pudo reemplazar el/los archivo(s)<br />» %s',

	'ERROR_DISABLE'			=> 'No se puede deshabilitar ForumHulp Helper debido a que hay extensiones de ForumHulp habilitadas<br /><div style="margin: 0px auto; width: 50%%; text-align: left;">» %s</div>'
));
