<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 4, 2010
 * Time: 12:38:45 AM
 */

class WPAM_Util_JsonHandler
{
	public function approveApplication($request)
	{
		if (!wp_get_current_user()->has_cap(WPAM_PluginConfig::$AdminCap)){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                if(!wp_verify_nonce($request['nonce'], 'wpam-ajax-approve-application')){
                        throw new Exception( __('Invalid nonce', 'affiliates-manager' ) );
                }
                $bountyAmount = $request['bountyAmount'];
		if (!is_numeric($bountyAmount)){
			throw new Exception( __( 'Invalid bounty amount.', 'affiliates-manager' ) );
                }
                $bountyType = $request['bountyType'];
		if (!in_array($bountyType, array("fixed", "percent"))){
			throw new Exception( __('Invalid bounty type.', 'affiliates-manager' ) );
                }
                $affiliateId = $request['affiliateId'];
		$affiliateId = (int)$affiliateId;

		$db = new WPAM_Data_DataAccess();
		$affiliate = $db->getAffiliateRepository()->load($affiliateId);

		if ($affiliate === null){
			throw new Exception( __('Invalid affiliate', 'affiliates-manager' ) );
                }
		if ( $affiliate->isPending() ) {
			$userHandler = new WPAM_Util_UserHandler();
			$userHandler->approveAffiliate( $affiliate, $bountyType, $bountyAmount );
                        do_action('wpam_affiliate_application_approved', $affiliateId);
			return new JsonResponse( JsonResponse::STATUS_OK );
		} else {
			throw new Exception( __( 'Invalid state transition.', 'affiliates-manager' ) );
		}
	}

	
	public function declineApplication($request)
	{
		if (!wp_get_current_user()->has_cap(WPAM_PluginConfig::$AdminCap)){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                if(!wp_verify_nonce($request['nonce'], 'wpam-ajax-decline-application')){
                        throw new Exception( __('Invalid nonce', 'affiliates-manager' ) );
                }
                $affiliateId = $request['affiliateId'];
		$affiliateId = (int)$affiliateId;

		$db = new WPAM_Data_DataAccess();
		$affiliate = $db->getAffiliateRepository()->load($affiliateId);

		if ($affiliate === null){
			throw new Exception( __( 'Invalid affiliate', 'affiliates-manager' ) );
                }
		if ($affiliate->isPending() || $affiliate->isBlocked())
		{
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
			if ($affiliate->isPending())
			{
				$mailer = new WPAM_Util_EmailHandler();
				$mailer->mailAffiliate( $affiliate->email, sprintf( __( 'Affiliate Application for %s', 'affiliates-manager' ), $blogname ), WPAM_MessageHelper::GetMessage( 'affiliate_application_declined_email' ) );
			}
			$affiliate->decline();
			$db->getAffiliateRepository()->update($affiliate);
                        do_action('wpam_affiliate_application_declined', $affiliateId);
			return new JsonResponse(JsonResponse::STATUS_OK);
		}
		else
		{
			throw new Exception( __( 'Invalid state transition.', 'affiliates-manager' ) );
		}
	}

	public function blockApplication($request)
	{
		if (!wp_get_current_user()->has_cap(WPAM_PluginConfig::$AdminCap)){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                if(!wp_verify_nonce($request['nonce'], 'wpam-ajax-block-application')){
                        throw new Exception( __('Invalid nonce', 'affiliates-manager' ) );
                }
                $affiliateId = $request['affiliateId'];
		$affiliateId = (int)$affiliateId;

		$db = new WPAM_Data_DataAccess();
		$affiliate = $db->getAffiliateRepository()->load($affiliateId);

		if ($affiliate === null){
			throw new Exception( __( 'Invalid affiliate', 'affiliates-manager' ) );
                }
		if ($affiliate->isPending() || $affiliate->isDeclined())
		{
			$affiliate->block();
			$db->getAffiliateRepository()->update($affiliate);
                        do_action('wpam_affiliate_application_blocked', $affiliateId);
			return new JsonResponse(JsonResponse::STATUS_OK);
		}
		else
		{
			throw new Exception( __( 'Invalid state transition.', 'affiliates-manager' ) );
		}

	}

	public function activateApplication($request)
	{
		if (!wp_get_current_user()->has_cap(WPAM_PluginConfig::$AdminCap)){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                if(!wp_verify_nonce($request['nonce'], 'wpam-ajax-activate-affiliate')){
                    throw new Exception( __('Invalid nonce', 'affiliates-manager' ) );
                }
                $affiliateId = $request['affiliateId'];
		$affiliateId = (int)$affiliateId;

		$db = new WPAM_Data_DataAccess();
		$affiliate = $db->getAffiliateRepository()->load($affiliateId);

		if ($affiliate === NULL){
			throw new Exception( __( 'Invalid affiliate', 'affiliates-manager' ) );
                }
		if ( !$affiliate->isConfirmed() && !$affiliate->isInactive() ){
			throw new Exception( __( 'Invalid state transition.', 'affiliates-manager' ) );
                }
		$affiliate->activate();
		$db->getAffiliateRepository()->update($affiliate);

		$user = new WP_User($affiliate->userId);
		$user->add_cap(WPAM_PluginConfig::$AffiliateActiveCap);		
                do_action('wpam_affiliate_application_activated', $affiliateId);
		return new JsonResponse(JsonResponse::STATUS_OK);
	}
	
	public function deactivateApplication($request) {
		if ( !wp_get_current_user()->has_cap( WPAM_PluginConfig::$AdminCap ) ){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                if(!wp_verify_nonce($request['nonce'], 'wpam-ajax-deactivate-affiliate')){
                    throw new Exception( __('Invalid nonce', 'affiliates-manager' ) );
                }
                $affiliateId = $request['affiliateId'];
		$affiliateId = (int)$affiliateId;

		$db = new WPAM_Data_DataAccess();
		$affiliate = $db->getAffiliateRepository()->load( $affiliateId );

		if ( $affiliate === NULL ){
			throw new Exception( __( 'Invalid affiliate', 'affiliates-manager' ) );
                }
		if ( !$affiliate->isActive() ){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
		$affiliate->deactivate();
		$db->getAffiliateRepository()->update( $affiliate );

		$user = new WP_User( $affiliate->userId );
		$user->remove_cap( WPAM_PluginConfig::$AffiliateActiveCap );
                do_action('wpam_affiliate_application_deactivated', $affiliateId);
		return new JsonResponse(JsonResponse::STATUS_OK);
	}

	public function setCreativeStatus($request)
	{
		if (!wp_get_current_user()->has_cap(WPAM_PluginConfig::$AdminCap)){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                if(!wp_verify_nonce($request['nonce'], 'wpam-ajax-set-creative-status')){
                        throw new Exception( __('Invalid nonce', 'affiliates-manager' ) );
                }
                $status = $request['status'];
		if (!in_array($status, array('inactive', 'active'))){
			throw new Exception( __( 'Invalid status.', 'affiliates-manager' ) );
                }
		$validTransitions = array(
			'active' => array('inactive'),
			'inactive' => array('active')
		);
                $creativeId = $request['creativeId'];
		$creativeId = (int)$creativeId;

		$db = new WPAM_Data_DataAccess();
		$creative = $db->getCreativesRepository()->load($creativeId);

		if ($creative === NULL){
			throw new Exception(  __( 'Invalid creative', 'affiliates-manager' ) );
                }
		if (!in_array($status, $validTransitions[$creative->status])){
			throw new Exception( __( 'Invalid state transition.', 'affiliates-manager' ) );
                }
		$creative->status = $status;
		$db->getCreativesRepository()->update($creative);

		return new JsonResponse(JsonResponse::STATUS_OK);
	}

	public function addTransaction($request)
	{
		if (!wp_get_current_user()->has_cap(WPAM_PluginConfig::$AdminCap)){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                if(!wp_verify_nonce($request['nonce'], 'wpam-ajax-add-transaction')){
                        throw new Exception( __('Invalid nonce', 'affiliates-manager' ) );
                }
                $type = $request['type'];
		if (!in_array($type, array('credit', 'payout', 'adjustment'))){
			throw new Exception( __( 'Invalid transaction type.', 'affiliates-manager' ) );
                }
                $amount = $request['amount'];
		if (!is_numeric($amount)){
			throw new Exception( __( 'Invalid value for amount.', 'affiliates-manager' ) );
                }
                $affiliateId = $request['affiliateId'];
		$affiliateId = (int)$affiliateId;
                $description = NULL;
                if(isset($request['description']) && !empty($request['description'])){
                    $description = $request['description'];
                }
		$db = new WPAM_Data_DataAccess();

		if (!$db->getAffiliateRepository()->exists($affiliateId)){
			throw new Exception( __( 'Invalid affiliate', 'affiliates-manager' ) );
                }
		$transaction = new WPAM_Data_Models_TransactionModel();
		$transaction->type = $type;
		$transaction->affiliateId = $affiliateId;
		$transaction->amount = $amount;
		$transaction->dateCreated = time();
		$transaction->description = $description;

		$db->getTransactionRepository()->insert($transaction);

		return new JsonResponse(JsonResponse::STATUS_OK);
	}

	public function deleteCreative($request)
	{
                if (!wp_get_current_user()->has_cap(WPAM_PluginConfig::$AdminCap)){
			throw new Exception( __('Access denied.', 'affiliates-manager' ) );
                }
                $nonce = isset($request['nonce']) ? sanitize_text_field($request['nonce']) : '';
                if(!wp_verify_nonce($nonce, 'wpam-delete-creative')){
                    wp_die(__('Error! Nonce Security Check Failed! Go to the My Creatives page to delete the creative.', 'affiliates-manager'));
                }
                $creativeId = sanitize_text_field($request['creativeId']);
		$db = new WPAM_Data_DataAccess();

		if (!$db->getCreativesRepository()->exists($creativeId)){
			throw new Exception( __( 'Invalid creative.', 'affiliates-manager' ) );
                }
		$creative = $db->getCreativesRepository()->load($creativeId);
		$creative->status = 'deleted';
		$db->getCreativesRepository()->update($creative);

		return new JsonResponse(JsonResponse::STATUS_OK);
	}
}

class JsonResponse
{
	const STATUS_OK = "OK";
	const STATUS_ERROR = "ERROR";

	public $status;
	public $message;
	public $data;
	public function __construct($status, $message = NULL) { $this->status = $status; $this->message = $message; }

}
