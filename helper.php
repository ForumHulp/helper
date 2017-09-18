<?php
/**
 * This file is part of the ForumHulp extension package.
 *
* @copyright (c) 2015 John Peskens (http://ForumHulp.com)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace forumhulp\helper;

use phpbb\db\driver\driver_interface;
use phpbb\config\config;
use phpbb\extension\manager;
use phpbb\template\template;
use phpbb\user;
use phpbb\request\request;
use phpbb\log\log;
use phpbb\cache\service;

class helper 
{
	protected $db;
	protected $config;
	protected $phpbb_extension_manager;
	protected $template;
	protected $user;
	protected $request;
	protected $log;
	protected $cache;
	protected $root_path;

	public function __construct(driver_interface $db, config $config, manager $phpbb_extension_manager, template $template, user $user, request $request, log $log, service $cache, 
		$root_path)
	{
		$this->db			= $db;
		$this->config		= $config;
		$this->ext_manager	= $phpbb_extension_manager;
		$this->template		= $template;
		$this->user			= $user;
		$this->request		= $request;
		$this->log			= $log;
		$this->cache		= $cache;
		$this->root_path	= $root_path;

		$this->user->add_lang(array('install', 'acp/extensions', 'migrator'));
	}

	public function detail($ext_name)
	{
		$md_manager = ((version_compare($this->config['version'], '3.2.0', '<')) ? 
						new \phpbb\extension\metadata_manager($ext_name, $this->config, $this->ext_manager, $this->template, $this->user, $this->root_path) :
						((version_compare($this->config['version'], '3.2.1*', '<')) ? 
						new \phpbb\extension\metadata_manager($ext_name, $this->config, $this->ext_manager, $this->root_path) :
						$this->ext_manager->create_extension_metadata_manager($ext_name)));
		try
		{
			$this->metadata = $md_manager->get_metadata('all');
		}
		catch(\phpbb\extension\exception $e)
		{
			$message = call_user_func_array(array($this->user, 'lang'), array_merge(array($e->getMessage()), $e->get_parameters()));
			trigger_error($message, E_USER_WARNING);
		}

		(version_compare($this->config['version'], '3.2.1*', '<')) ? $md_manager->output_template_data($this->template) : $this->output_metadata_to_template($this->metadata);

		if (isset($this->user->lang['ext_details']))
		{
			foreach($this->user->lang['ext_details'] as $key => $value)
			{
				foreach($value as $desc)
				{
					$this->template->assign_block_vars($key, array('DESCRIPTION' => $desc));
				}
			}
		}
		
		try
		{
			$updates_available = $this->version_check($md_manager, $this->request->variable('versioncheck_force', false));

			$this->template->assign_vars(array(
				'S_UP_TO_DATE'		=> empty($updates_available),
				'S_VERSIONCHECK'	=> true,
				'UP_TO_DATE_MSG'	=> $this->user->lang(empty($updates_available) ? 'UP_TO_DATE' : 'NOT_UP_TO_DATE', $md_manager->get_metadata('display-name')),
			));

			foreach ($updates_available as $branch => $version_data)
			{
				$this->template->assign_block_vars('updates_available', $version_data);
			}
		}
		catch (\RuntimeException $e)
		{
			$this->template->assign_block_vars('note', array(
				'DESCRIPTION'		=> $this->user->lang('VERSIONCHECK_FAIL'),
				'S_VERSIONCHECK'	=> true,
			));
			if ($e->getCode())
			{
				$this->template->assign_block_vars('note', array(
					'DESCRIPTION'		=> $e->getCode(),
					'S_VERSIONCHECK'	=> true,
				));
				$this->template->assign_block_vars('note', array(
					'DESCRIPTION'		=> ($e->getMessage() !== $this->user->lang('VERSIONCHECK_FAIL')) ? $e->getMessage() : '',
					'S_VERSIONCHECK'	=> true,
				));
			}
		}
		
		$this->template->assign_vars(array('IS_AJAX' => $this->request->is_ajax()));
	}

	/**
	* Outputs extension metadata into the template
	*
	* @param array $metadata Array with all metadata for the extension
	* @return null
	*/
	public function output_metadata_to_template($metadata)
	{
		$this->template->assign_vars(array(
			'META_NAME'			=> $metadata['name'],
			'META_TYPE'			=> $metadata['type'],
			'META_DESCRIPTION'	=> (isset($metadata['description'])) ? $metadata['description'] : '',
			'META_HOMEPAGE'		=> (isset($metadata['homepage'])) ? $metadata['homepage'] : '',
			'META_VERSION'		=> $metadata['version'],
			'META_TIME'			=> (isset($metadata['time'])) ? $metadata['time'] : '',
			'META_LICENSE'		=> $metadata['license'],

			'META_REQUIRE_PHP'		=> (isset($metadata['require']['php'])) ? $metadata['require']['php'] : '',
			'META_REQUIRE_PHP_FAIL'	=> (isset($metadata['require']['php'])) ? false : true,

			'META_REQUIRE_PHPBB'		=> (isset($metadata['extra']['soft-require']['phpbb/phpbb'])) ? $metadata['extra']['soft-require']['phpbb/phpbb'] : '',
			'META_REQUIRE_PHPBB_FAIL'	=> (isset($metadata['extra']['soft-require']['phpbb/phpbb'])) ? false : true,

			'META_DISPLAY_NAME'	=> (isset($metadata['extra']['display-name'])) ? $metadata['extra']['display-name'] : '',
		));

		foreach ($metadata['authors'] as $author)
		{
			$this->template->assign_block_vars('meta_authors', array(
				'AUTHOR_NAME'		=> $author['name'],
				'AUTHOR_EMAIL'		=> (isset($author['email'])) ? $author['email'] : '',
				'AUTHOR_HOMEPAGE'	=> (isset($author['homepage'])) ? $author['homepage'] : '',
				'AUTHOR_ROLE'		=> (isset($author['role'])) ? $author['role'] : '',
			));
		}
	}

	/**
	* Check the version and return the available updates.
	*
	* @param \phpbb\extension\metadata_manager $md_manager The metadata manager for the version to check.
	* @param bool $force_update Ignores cached data. Defaults to false.
	* @param bool $force_cache Force the use of the cache. Override $force_update.
	* @return string
	* @throws RuntimeException
	*/
	protected function version_check(\phpbb\extension\metadata_manager $md_manager, $force_update = false, $force_cache = false)
	{
		$meta = $md_manager->get_metadata('all');

		if (!isset($meta['extra']['version-check']))
		{
			throw new \RuntimeException($this->user->lang('NO_VERSIONCHECK'), 1);
		}

		$version_check = $meta['extra']['version-check'];

		$version_helper = (version_compare($this->config['version'], '3.1.1', '>')) ? 
						new \phpbb\version_helper($this->cache, $this->config, new \phpbb\file_downloader(), $this->user) :
						new \phpbb\version_helper($this->cache, $this->config, $this->user);

		$version_helper->set_current_version($meta['version']);
		$version_helper->set_file_location($version_check['host'], $version_check['directory'], $version_check['filename']);
		$version_helper->force_stability($this->config['extension_force_unstable'] ? 'unstable' : null);

		return $updates = $version_helper->get_suggested_updates($force_update, $force_cache);
	}

	/**
	* Update files on server
	*
	* @return null
	* @access public
	*/
	public function update_files($replacements = array(), $revert)
	{
		$this->replacements = $replacements;
		$files = $this->replacements['files'];
		$searches = ($revert) ? $this->replacements['replaces'] : $this->replacements['searches'];
		$replace = ($revert) ? $this->replacements['searches'] : $this->replacements['replaces'];
		$i = $j = 0;
		$files_changed = array();
		foreach($files as $key => $file)
		{
			phpbb_chmod($this->root_path . $file, CHMOD_WRITE);
			if (is_writable($this->root_path . $file))
			{
				$fp = @fopen($this->root_path . $file , 'r' );
				if ($fp === false)
				{
					continue;
				}
				$content = fread( $fp, filesize($this->root_path . $file) );
				(!$revert) ? copy($this->root_path . $file, $this->root_path . $file . '.bak') : null;
				fclose($fp);
				foreach($searches[$key] as $key2 => $search)
				{
					if ($revert || strpos($content, $replace[$key][$key2]) === false)
					{
						$content = str_replace($search, $replace[$key][$key2], $content);
						($key2 == 0) ? $i++ : $i;
					}
				}
				if ($i != $j)
				{
					$new_file = $files[$key];
					$fp = @fopen($this->root_path . $new_file , 'w' );
					if ($fp === false)
					{
						continue;
					}
					$fwrite = fwrite($fp, $content);
					fclose($fp);
					if ($fwrite !== false)
					{
						$j = $i;
						$files_changed[] = $new_file;
					}
				}
			}
			phpbb_chmod($this->root_path . $file, CHMOD_READ);
		}

		if (sizeof($files) == sizeof($files_changed))
		{
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, (($revert) ? 'LOG_CORE_DEINSTALLED' : 'LOG_CORE_INSTALLED'), time(), array());
		} else
		{
			$not_updated = array_diff($files, $files_changed);
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, (($revert) ? 'LOG_CORE_NOT_REPLACED' : 'LOG_CORE_NOT_UPDATED'), time(), array(implode('<br />', $not_updated)));
		}
	}

	/**
	* Move module position by $steps up/down, 3.1 compatabillity
	*/
	function move_module_by($module_row, $module_class, $action = 'move_up', $steps = 1)
	{
		/**
		* Fetch all the siblings between the module's current spot
		* and where we want to move it to. If there are less than $steps
		* siblings between the current spot and the target then the
		* module will move as far as possible
		*/
		$sql = 'SELECT module_id, left_id, right_id, module_langname
			FROM ' . MODULES_TABLE . "
			WHERE module_class = '" . $this->db->sql_escape($module_class) . "'
				AND parent_id = " . (int) $module_row['parent_id'] . '
				AND ' . (($action == 'move_up') ? 'right_id < ' . (int) $module_row['right_id'] . ' 
				ORDER BY right_id DESC' : 'left_id > ' . (int) $module_row['left_id'] . ' ORDER BY left_id ASC');
		$result = $this->db->sql_query_limit($sql, $steps);

		$target = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$target = $row;
		}
		$this->db->sql_freeresult($result);

		if (!sizeof($target))
		{
			// The module is already on top or bottom
			return false;
		}

		/**
		* $left_id and $right_id define the scope of the nodes that are affected by the move.
		* $diff_up and $diff_down are the values to substract or add to each node's left_id
		* and right_id in order to move them up or down.
		* $move_up_left and $move_up_right define the scope of the nodes that are moving
		* up. Other nodes in the scope of ($left_id, $right_id) are considered to move down.
		*/
		if ($action == 'move_up')
		{
			$left_id = (int) $target['left_id'];
			$right_id = (int) $module_row['right_id'];

			$diff_up = (int) ($module_row['left_id'] - $target['left_id']);
			$diff_down = (int) ($module_row['right_id'] + 1 - $module_row['left_id']);

			$move_up_left = (int) $module_row['left_id'];
			$move_up_right = (int) $module_row['right_id'];
		}
		else
		{
			$left_id = (int) $module_row['left_id'];
			$right_id = (int) $target['right_id'];

			$diff_up = (int) ($module_row['right_id'] + 1 - $module_row['left_id']);
			$diff_down = (int) ($target['right_id'] - $module_row['right_id']);

			$move_up_left = (int) ($module_row['right_id'] + 1);
			$move_up_right = (int) $target['right_id'];
		}

		// Now do the dirty job
		$sql = 'UPDATE ' . MODULES_TABLE . "
			SET left_id = left_id + CASE
				WHEN left_id BETWEEN {$move_up_left} AND {$move_up_right} THEN -{$diff_up}
				ELSE {$diff_down}
			END,
			right_id = right_id + CASE
				WHEN right_id BETWEEN {$move_up_left} AND {$move_up_right} THEN -{$diff_up}
				ELSE {$diff_down}
			END
			WHERE module_class = '" . $this->db->sql_escape($module_class) . "'
				AND left_id BETWEEN {$left_id} AND {$right_id}
				AND right_id BETWEEN {$left_id} AND {$right_id}";
		$this->db->sql_query($sql);
	}
}