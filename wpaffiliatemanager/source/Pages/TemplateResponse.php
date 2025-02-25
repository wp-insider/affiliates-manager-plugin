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
                $template_name = "{$this->templateName}.php";
                //List of file paths (in order of priority) where the plugin should check for the template.
                $template_files = array(
                    get_stylesheet_directory() . '/' . WPAM_TEMPLATE_PATH . '/' . $template_name, //First check inside child theme (if you are using a child theme)
                    get_template_directory() . '/' . WPAM_TEMPLATE_PATH . '/' . $template_name, //Then check inside the main theme folder
                    WPAM_BASE_DIRECTORY . '/html/' . $template_name //Otherwise load the standard template
                );
                //Filter hook to allow overriding of the template file path
                $template_files = apply_filters( 'wpam_load_template_files', $template_files, $template_name);
                $template_to_load = '';
                foreach ($template_files as $file) {
                    if (file_exists($file)) {
                        $template_to_load = $file;
                        break;
                    }
                }
                if(!empty($template_to_load)){
                    ob_start();
                    include $template_to_load;
                    $buffer = ob_get_contents();
                    ob_end_clean();
                    return $buffer;
                }
                else{
                    wp_die( sprintf( __('Error! Failed to find a template path for the specified template: %s.', 'affiliates-manager' ), $template_name) );
                }
	}
}
