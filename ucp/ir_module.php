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

use phpbb\config\config;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use phpbb\controller\helper;

class ir_module
{
	public $u_action;
	public $page_title;
	public $tpl_name;
	private $config;
	private $user;
	private $template;
	private $request;
	private $helper;

	/**
	 * {@inheritdoc}
	 */
	private function setup(config $config, user $user, template $template, request_interface $request, helper $helper)
	{
		$this->config = $config;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->helper = $helper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function main($id, $mode)
	{
		global $config, $user, $template, $request, $phpbb_container;

		$user->add_lang_ext('forumhulp/helper', 'info_ucp_helper');
		$this->setup($config, $user, $template, $request, $phpbb_container->get('controller.helper'));

		$this->createPage();
	}

	/**
	 * {@inheritdoc}
	 */
	private function createPage()
	{
		$this->tpl_name = 'ucp_image_resize';

		$app = urldecode($this->request->variable('app', ''));
		$imagename = urldecode($this->request->variable('file', ''));

		if ($app && $imagename && file_exists($imagename))
		{
			$this->user->add_lang_ext('forumhulp/bb' . $app . 'resize',  $app . 'resize');
			$imginfo = getimagesize($imagename);
			$this->page_title = $this->user->lang(strtoupper($app) . '_RESIZE');
	
			$this->template->assign_vars(array(
				'L_IMAGE_RESIZE'			=> $this->page_title,
				'L_IMAGE_RESIZE_EXPLAIN'	=> $this->user->lang(strtoupper($app) . '_RESIZE_EXPLAIN', $app, $this->config['avatar_max_height'], $this->config['avatar_max_width']),
				'IMAGENAME'					=> str_replace('tmp_', '' , $imagename),
				'UPLOADED_IMAGE'			=> 'data:' . $imginfo['mime'] . ';base64,' . base64_encode(file_get_contents($imagename)),
				'MIN_SIZE'					=> $this->config['avatar_min_width'] . ',' . $this->config['avatar_min_height'],
				'U_ACTION'					=> $this->helper->route('forumhulp_bb' . $app . 'resize_controller', array('route' => 'index.html')),
			));
			
			unlink($imagename);
		} else
		{
			$this->template->assign_vars(array(
				'ERROR'	=> $this->user->lang['IMAGE_RESIZE_ERROR'] . '<br />' . sprintf($this->user->lang['RETURN_PAGE'], '<a href="javascript:window.history.back();">', '</a>')
			));
		}
	}
}
