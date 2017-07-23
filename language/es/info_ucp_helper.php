<?php
/**
*
* @package Forumhulp Helper
* @copyright (c) 2015 ForumHulp.com
* @license Proprietary
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
// ’ » “ ” …
//

$lang = array_merge(
	$lang, array(
		'UCP_IR'				=> 'Redimensionar imagen',
		'IMAGE_RESIZE'			=> 'Redimensionar imagen',
		'IMAGE_RESIZE_EXPLAIN'	=> 'Recorte su %1$s de la imagen y confirme.',
		// Your avatar will be saved with a height of %1$s pixels and a width of %2$s pixels.',

		'IMAGE_RESIZE_ERROR'	=> '¡Ninguna aplicación o archivo para enviar!',
		'IMAGERESIZE_NOTICE'	=> '<div class="phpinfo"><p class="entry">Este módulo está oculto y no tiene opciones de configuración. Sin embargo, le recuerdo que debe configurar la carga máxima en el PCA.</p></div>',
		'ERROR_IMAGERESIZE_DISABLE'	=> 'No puede inhabilitar la redimensión de imagen debido a las extensiones activas<br /><div class="phpinfo">» %s</div>',
	'FH_HELPER_NOTICE'				=> '¡La aplicación Forumhulp Helper no existe!<br />Descargue <a href="https://github.com/ForumHulp/helper" target="_blank">forumhulp/helper</a> y copie la carpeta helper en la carpeta de la extensión forumhulp.',
	)
);
