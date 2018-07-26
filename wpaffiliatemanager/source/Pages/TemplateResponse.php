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
		$templates_path_default = WPAM_BASE_DIRECTORY . "/html/";
		$templates_path = apply_filters('wpam_templates_path', $templates_path_default);
		
		ob_start();
		
		if ( file_exists($templates_path . "{$this->templateName}.php") ) {
			include $templates_path . "{$this->templateName}.php";
		} else {
			include $templates_path_default . "{$this->templateName}.php";
		}
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}
