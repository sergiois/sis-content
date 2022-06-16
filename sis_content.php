<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.SIS_Content
 *
 * @copyright	Copyright © 2021 SergioIglesiasNET - All rights reserved.
 * @license		GNU General Public License v2.0
 * @author 		Sergio Iglesias (@sergiois)
 */

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

class PlgContentSIS_Content extends CMSPlugin
{	
	protected $app;
	protected $position;
	
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$app = Factory::getApplication();
		$tmpl = Factory::getApplication()->input->get('tmpl');
		if ($app->isClient('administrator') || $app->get('offline', '0'))
		{
			return;
		}

		if(!$this->params->get('showinprint', 0) && $tmpl == 'component')
		{
			return;
		}

		$this->position = $this->params->get('position', 2);
	}

	function onContentAfterDisplay($context, &$article, &$params, $page = 0)
	{
		if (($this->position  == 2 || $this->position == 4) && $context != 'com_content.category')
		{
			return $this->getSISContent($article);
		}
	}	
	
	public function onContentAfterTitle($context, &$article, &$params, $page = 0)
	{
		if ($this->position == 3 && $context != 'com_content.category') 
		{
			return $this->getSISContent($article);
		}
	}
		
	function onContentBeforeDisplay($context, &$article, &$params, $page=0)
	{
		if (($this->position == 1 || $this->position == 4) && $context != 'com_content.category')
		{
			return $this->getSISContent($article);
		}
	}
	
	function getSISContent($article)
	{
		if (!isset($article->catid))
		{
			$article->catid = 0;
		}
		
		$categories 		= array();
		$cats 				= $this->params->get('catids');
		
		if (is_array($cats))
		{
			$categories 	= $cats;
		}
		else
		{
			$categories[] 	= $cats;
		}
		
		// Custom content
		$custom_content		= $this->params->get('custom');
		
		if (in_array($article->catid, $categories))
		{
			$output = '';

			$output .= '<div class="siscontent">';
			$output .= $custom_content;
			$output .= '</div>';			
			
			return $output;		
		}
	}
}