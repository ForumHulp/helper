<?php
/**
*
 * This file is part of the ForumHulp extension package.
 *
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
 * @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace forumhulp\helper\migrations\v31x;

use phpbb\db\migration\container_aware_migration;

/**
* Migration stage 1: Initial data changes to the files
*/
class m1_initial_file extends container_aware_migration
{
	/**
	* Add data to the files.
	*
	* @return array Array of files
	* @access public
	*/
	public function update_data()
	{
		$this->revert = false;
		return array(
			array('custom', array(array($this, 'update_acp_pages'))),
		);
	}

	public function revert_data()
	{
		$this->revert = true;
		return array(
			array('custom', array(array($this, 'update_acp_pages'))),
		);
	}

	/**
	* Update files on server
	*
	* @return null
	* @access public
	*/
	public function update_acp_pages()
	{
		$sitesplat_helper = new \forumhulp\helper\helper(
			$this->container->get('dbal.conn'),
			$this->config,
			$this->container->get('ext.manager'),
			$this->container->get('template'),
			$this->container->get('user'),
			$this->container->get('request'),
			$this->container->get('log'),
			$this->container->get('cache'),
			$this->phpbb_root_path
		);
		$sitesplat_helper->update_files($this->data(), $this->revert);		
	}
	
	protected function data()
	{
		$replacements = array(
			'files' => array(
				0 => '/adm/style/acp_ext_list.html',
				1 => '/includes/acp/acp_extensions.' . $this->php_ext,
				),
			'searches' => array(
				0 => array('{L_EXTENSIONS_ENABLED}</strong>'),
				1 => array(
						0 => '$phpbb_log, $cache',
						1 => '// Cancel action',
					),
				),
			'replaces' => array(
				0 => array('{L_EXTENSIONS_ENABLED}</strong><!-- EVENT disable_all -->'),
				1 => array(
					0 => '$phpbb_log, $cache, $phpbb_dispatcher',
					1 => '/**
		* Event to run a specific action on extension
		*
		* @event core.acp_extensions_run_action
		* @var	string	action			Action to run
		* @var	string	u_action		Url we are at
		* @since 3.1.9
		*/
		$u_action = $this->u_action;
		$vars = array(\'action\', \'u_action\');
		$phpbb_dispatcher->trigger_event(\'core.acp_extensions_run_action\', compact($vars));
		
		// Cancel action'
				)
			)
		);
		return $replacements;
	}
}
