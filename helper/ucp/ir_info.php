<?php
/**
*
* This file is part of the ForumHulp extension package.
*
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace forumhulp\helper\ucp;

class ir_info
{
	function module()
	{
		return array(
			'filename'	=> '\forumhulp\helper\ucp\ir_module',
			'title'     => 'UCP_IR',
			'version'   => '1.0.0',
			'modes'     => array(
				'ir'	=> array(
						'title'		=> 'UCP_IR',
						'auth'		=> 'ext_forumhulp/helper',
						'display'	=> 0,
						'cat'		=> array('UCP_PROFILE')
				),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
