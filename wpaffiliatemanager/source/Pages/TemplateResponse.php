<?php
/**
 * @author John Hargrove
 * 
 * Date: May 24, 2010
 * Time: 10:51:06 PM
 */

class WPAM_Pages_TemplateResponse
{
	public $viewData = array();
	private $templateName;

	public function __construct($templateName, $viewData = array())
	{
		$this->templateName = $templateName;
		$this->viewData = $viewData;
	}

	public function render()
	{
		ob_start();
		$template = get_stylesheet_directory() . "/html/{$this->templateName}.php";
    		include $template;
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}
