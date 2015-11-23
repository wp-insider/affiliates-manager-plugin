<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 6, 2010
 * Time: 5:48:35 PM
 */


require_once WPAM_BASE_DIRECTORY . "/source/Validation/IValidator.php";

class WPAM_Pages_Admin_MyCreativesPage extends WPAM_Pages_Admin_AdminPage
{
	private $response;

	public function processRequest($request)
	{
		$db = new WPAM_Data_DataAccess();

		if(!empty($request['action'])) //changed from isset to resolve strange bug with siliconeapp theme
		{
			if ($request['action'] === 'viewDetail')
			{
				return $this->doDetailView($request);
			}
			else if ($request['action'] === 'new')
			{
				return $this->doNew($request);
			}
			else if ($request['action'] === 'edit')
			{
				return $this->doEdit($request);
			}
		}		
		else
		{
			$response = new WPAM_Pages_TemplateResponse('admin/manage_creatives');

			if(isset($request['statusFilter']))
			{				
				if ($request['statusFilter'] === 'active')
				{
					$response->viewData['creatives'] = $db->getCreativesRepository()->loadAllActiveNoDeletes();
				}
				else if ($request['statusFilter'] === 'inactive')
				{
					$response->viewData['creatives'] = $db->getCreativesRepository()->loadAllInactiveNoDeletes();
				}
			}
			else
			{
				$response->viewData['creatives'] = $db->getCreativesRepository()->loadAllNoDeletes();
			}

			$response->viewData['request'] = $request;
			$response->viewData['statusFilters'] = array(
				'all' => __( 'All', 'affiliates-manager' ),
				'active' => __( 'Active', 'affiliates-manager' ),
				'inactive' => __( 'Inactive', 'affiliates-manager' ),
			);
			return $response;
		}
	}

	protected function getCreativeUpdateForm($request = array(), $validationResult = null)
	{
		//add widget_form_error js to creative_update_form
		add_action('admin_footer', array( $this, 'onFooter' ) );

		$response = new WPAM_Pages_TemplateResponse('admin/creative_update_form');

		$db = new WPAM_Data_DataAccess();
		$images = $db->getWordPressRepository()->getAllImageAttachments();

		$response->viewData['creativeTypes'] = array(
			'none' => "",
			'image' => "Image",
			'text' => 'Text Link'
		);

		$response->viewData['images'] = array(
			'' => ""
		);

		foreach ($images as $image)
		{
			$response->viewData['images'][$image->ID] = "{$image->post_title} ({$image->post_name})";
		}


		$response->viewData['validationResult'] = $validationResult;
		$response->viewData['request'] = $request;

		//save for form validation in the footer
		$this->response = $response;

		return $response;
	}

	protected function doEdit($request)
	{
		if (isset($request['post']) && $request['post'])
			return $this->doCreativeSubmit($request);
		
		$db = new WPAM_Data_DataAccess();
		$creative = $db->getCreativesRepository()->load($request['creativeId']);
		if ($creative === NULL)
			wp_die( __( 'Invalid creative.', 'affiliates-manager' ) );
		
		// load up the request, show the form
		$request['txtName'] = $creative->name;
		$request['txtSlug'] = $creative->slug;
		$request['ddType'] = $creative->type;

		if ($creative->type === 'image')
		{
			$request['txtImageAltText'] = $creative->altText;
			$request['ddFileImage'] = $creative->imagePostId;
		}
		else if ($creative->type === 'text')
		{
			$request['txtLinkText'] = $creative->linkText;
			$request['txtAltText'] = $creative->altText;
		}

		return $this->getCreativeUpdateForm($request);
	}

	protected function doNew($request)
	{
		if (isset($request['post']) && $request['post'])
		{
			return $this->doCreativeSubmit($request);
		}
		return $this->getCreativeUpdateForm($request);
	}

	protected function doCreativeSubmit($request)
	{
		$validator = new WPAM_Validation_Validator();

		//#23 before getting media library images, upload an image file if there is one
		$has_new_image = isset( $_FILES['fileImageNew'] ) && trim( $_FILES['fileImageNew']['name'] ) != '';
		if ( $has_new_image ) {
			$mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
				'bmp' => 'image/bmp',
				'tif|tiff' => 'image/tiff'
			);
			$file = wp_handle_upload( $_FILES['fileImageNew'], array( 'mimes' => $mimes, 'test_form' => false ) );

			if ( isset( $file['error'] ) ) {
				$validator->addError(
					new WPAM_Validation_ValidatorError( 'fileImageNew',
														sprintf ( __( 'Image Upload Error: %s', 'affiliates-manager' ), $file['error'] ) ) );
			} else {
				//successfully uploaded an image
				$object = array(
					'post_title' => basename( $file['url'] ),
					'post_content' => $file['url'],
					'post_mime_type' => $file['type'],
					'guid' => $file['url'] );

				// Save the attachment
				$attachment_id = wp_insert_attachment($object, $file['file']);

				//make it selected in the media library dropdown
				$has_new_image = false;
				$request['ddFileImage'] = $attachment_id;
			}
		}
				
		$db = new WPAM_Data_DataAccess();
		$images = $db->getWordPressRepository()->getAllImageAttachments();
		$imageIds = array();
		foreach ($images as $image)
			$imageIds[] = $image->ID;

		$validator->addValidator('txtName', new WPAM_Validation_StringValidator(1));
		//$validator->addValidator('ddLandingPage', new WPAM_Validation_SetValidator(array('index','products')));
		$validator->addValidator('ddType', new WPAM_Validation_SetValidator(array('image','text')));

		if ($request['ddType'] === 'image' && ! $has_new_image )
		{
			$validator->addValidator('ddFileImage', new WPAM_Validation_SetValidator($imageIds));
		}
		else if ($request['ddType'] === 'text')
		{
			$validator->addValidator('txtLinkText', new WPAM_Validation_StringValidator(1));
		}

		$vr = $validator->validate($request);

		if ($vr->getIsValid())
		{
			$creativesRepo = $db->getCreativesRepository();

			if( $request['action'] === 'edit' ) {
				$model = $creativesRepo->load( $request['creativeId'] );
			} else {
				$model = new WPAM_Data_Models_CreativeModel();
				$model->dateCreated = time();
				//#50 new creatives start as 'inactive'
				$model->status = 'active';
			}
			
			$model->type = $request['ddType'];
			if ($model->type === 'image')
			{
				$model->imagePostId = $request['ddFileImage'];
				$model->altText = $request['txtImageAltText'];
			}
			else if ($model->type === 'text')
			{
				$model->linkText = $request['txtLinkText'];
				$model->altText = $request['txtAltText'];
			}
			else
			{
				wp_die( __( 'Insert failed: Bad creative type.', 'affiliates-manager' ) );
			}
			$model->slug = $request['txtSlug'];
			$model->name = $request['txtName'];
			
			$db = new WPAM_Data_DataAccess();
			$response = new WPAM_Pages_TemplateResponse('admin/creatives_detail');
			if ($request['action'] === 'edit')
			{
				$response->viewData['updateMessage'] = __( 'Creative Updated.', 'affiliates-manager' );
				$creativesRepo->update($model);
			}
			else if ($request['action'] === 'new')
			{
				$id = $creativesRepo->insert($model);
				$model->creativeId = $id;
				$response->viewData['updateMessage'] = __( 'Creative ... created.', 'affiliates-manager' );
			}
			else
			{
				wp_die( __( 'Insert failed: invalid creative update mechanism.', 'affiliates-manager' ) );
			}
			
			$response->viewData['request'] = $request;
			$response->viewData['creative'] = $model;
			
			return $response;
		}
		else
		{
			return $this->getCreativeUpdateForm($request, $vr);
		}
	}



	protected function doDetailView($request)
	{
		if (!is_numeric($request['creativeId']))
			wp_die( __('Invalid creative.', 'affiliates-manager' ) );

		$creativeId = (int)$request['creativeId'];
		$db = new WPAM_Data_DataAccess();
		$model = $db->getCreativesRepository()->load($creativeId);

		if ($model === NULL)
			wp_die( __('Invalid creative.', 'affiliates-manager' ) );

		$response = new WPAM_Pages_TemplateResponse('admin/creatives_detail');
		$response->viewData['creative'] = $model;
		$response->viewData['request'] = $request;
		return $response;
	}

	public function onFooter() {
		$response = new WPAM_Pages_TemplateResponse('widget_form_errors', $this->response->viewData);
		echo $response->render();
	}
	
}
