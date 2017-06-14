<?php
/**
*
 * This file is part of the ForumHulp extension package.
 *
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
 * @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace forumhulp\helper\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class acp_listener implements EventSubscriberInterface
{
	protected $phpbb_extension_manager;
	protected $helper;
	protected $template;

	/**
	* Constructor
 	*/
	public function __construct(\phpbb\extension\manager $phpbb_extension_manager, \phpbb\controller\helper $helper, \phpbb\template\template $template)
	{
		$this->extension_manager = $phpbb_extension_manager;
		$this->helper = $helper;
		$this->template = $template;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_extensions_run_action'	=> 'run_action'
		);
	}

	public function run_action($event)
	{
		$this->template->assign_vars(array(
			'DISABLE_ALL_FH' => (!$this->extension_manager->is_enabled('sitesplat/BCore')) ? $event['u_action'] . '&action=disable-all' : '',
		));

		if ($event['action'] == 'disable-all')
		{
			$safe_time_limit = (ini_get('max_execution_time') / 2);
			$start_time = time();
			$enabled_extensions = $this->extension_manager->all_enabled();
			unset($enabled_extensions['forumhulp/helper'], $enabled_extensions['sitesplat/BBCore']);
			foreach($enabled_extensions as $ext_name => $value)
			{
				while ($this->extension_manager->disable_step($ext_name))
				{
					if ((time() - $start_time) >= $safe_time_limit)
					{
						meta_refresh(0, $this->u_action . '&amp;action=disable-all');
					}
				}
			}
		}
	}
}
