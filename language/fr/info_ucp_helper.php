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
		'UCP_IR'				=> 'Redimensionnement d’image',
		'IMAGE_RESIZE'			=> 'Redimensionner l’image',
		'IMAGE_RESIZE_EXPLAIN'	=> 'Permet de redimensionner l’image de votre avatar de %1$s, puis de confirmer.',
		// Your avatar will be saved with a height of %1$s pixels and a width of %2$s pixels.',

		'IMAGE_RESIZE_ERROR'	=> 'Aucun fichier envoyé !',
		'IMAGERESIZE_NOTICE'	=> '<div class="phpinfo"><p class="entry">Ce module est masqué et ne possède aucune option de configuration. Par ailleurs, merci de configuration la « Taille maximale d’un avatar » depuis le panneau d’administration.</p></div>',
		'ERROR_IMAGERESIZE_DISABLE'	=> 'Il n’est pas possible de désactiver le redimensionnement d’image car des extensions sont actives <br /><div class="phpinfo">» %s</div>',
	'FH_HELPER_NOTICE'				=> 'L’extension « Forumhulp helper » n’est pas installée !<br />Merci de télécharger l’extension : « <a href="https://github.com/ForumHulp/helper" target="_blank">forumhulp/helper</a> », puis de l’installer.',
	)
);
