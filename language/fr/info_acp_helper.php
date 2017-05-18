<?php
/**
*
* Forumhulp Helper extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com)
*
* @copyright (c) 2017 phpBB ForumHulp <http://www.forumhulp.com>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'LOG_CORE_INSTALLED'	=> '<strong>Aide Forumhulp</strong><br />» Fichiers modifiés avec succès',
	'LOG_CORE_DEINSTALLED'	=> '<strong>Aide Forumhulp</strong><br />» Fichiers restaurés avec succès',
	'LOG_CORE_NOT_REPLACED'	=> '<strong>Aide Forumhulp</strong><br />» Fichier(s) n’ayant pu être remplacé(s)<br />» %s',
	'LOG_CORE_NOT_UPDATED'	=> '<strong>Aide Forumhulp</strong><br />» Fichier(s) n’ayant pu être mis à jour<br />» %s',

	'ERROR_DISABLE'			=> 'Il n’est pas possible de désactiver l’extension « ForumHulp Helper » car au moins une autre extension du même auteur « ForumHulp » est active<br /><div style="margin: 0px auto; width: 50%%; text-align: left;">» %s</div>'
));
