<?php
/**
*
 * This file is part of the ForumHulp extension package.
 *
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
 * @license GNU General Public License, version 2 (GPL-2.0)
*
*/

// this file is not really needed, when empty it can be omitted
// however you can override the default methods and add custom
// installation logic

namespace forumhulp\helper;

class ext extends \phpbb\extension\base
{
	/**
	* Single disable step that does nothing
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	public function disable_step($old_state)
	{
		$this->extensions = $this->container->get('ext.manager')->all_enabled();
		foreach($this->extensions as $key => $value)
		{
			if (strpos($value, 'forumhulp') === false)
			{
				unset($this->extensions[$key]);	
			}
		}
		
		unset($this->extensions['forumhulp/helper']);	
		if (sizeof($this->extensions))
		{
			$this->user = $this->container->get('user');
			trigger_error(sprintf($this->user->lang['ERROR_DISABLE'], implode('<br />» ', array_keys($this->extensions))), E_USER_WARNING);
		}
		
		return parent::disable_step($old_state);
	}
}
