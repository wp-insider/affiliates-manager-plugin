<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 6, 2010
 * Time: 6:08:48 PM
 */

class WPAM_Pages_Admin_MyAffiliatesPage extends WPAM_Pages_Admin_AdminPage
{
	private $response;
	
	public  function processRequest($request)
	{
		$db = new WPAM_Data_DataAccess();
  		
		if (isset($request['viewDetail']) && is_numeric($request['viewDetail']))
		{
			$affiliateFields = $db->getAffiliateFieldRepository()->loadMultipleBy(
				array('enabled' => true),
				array('order' => 'asc')
			);

			$id = (int)$request['viewDetail'];
			$model = $db->getAffiliateRepository()->load($id);
			if ($model == null)
			{
				wp_die("Invalid affiliate ID.");
			}

			if (isset($request['action']) && $request['action'] == 'saveInfo')
			{
				$validator = new WPAM_Validation_Validator();

				//validate bounty type & amount if they're in the appropriate status
				if ( ! $model->isPending() && ! $model->isBlocked() && ! $model->isDeclined() ) {
					$validator->addValidator('ddBountyType', new WPAM_Validation_SetValidator(array('fixed','percent')));

					if ($request['ddBountyType'] === 'fixed') {
						$validator->addValidator('txtBountyAmount', new WPAM_Validation_MoneyValidator());
					} else if ($request['ddBountyType'] === 'percent') {
						$validator->addValidator('txtBountyAmount', new WPAM_Validation_NumberValidator());
					}
				
					$validator->addValidator('ddPaymentMethod', new WPAM_Validation_SetValidator(array('check','paypal')));
				
					if ($request['ddPaymentMethod'] === 'paypal') {
						$validator->addValidator('txtPaypalEmail', new WPAM_Validation_EmailValidator());
					}
				}
				
				$affiliateHelper = new WPAM_Util_AffiliateFormHelper();
				$vr = $affiliateHelper->validateForm( $validator,
													  $request,
													  $affiliateFields,
													  TRUE );
				if ($vr->getIsValid())
				{
					$affiliateHelper->setModelFromForm( $model, $affiliateFields, $request );
					$affiliateHelper->setPaymentFromForm( $model, $request );					
					$db->getAffiliateRepository()->update($model);
				}
				else
				{
					return $this->getDetailForm($affiliateFields, $model, $request, $vr);
				}
			}
			return $this->getDetailForm($affiliateFields, $model, $request);
		}
		else
		{

			if ( isset( $_GET['orderby'] ) )
				$current_orderby = $_GET['orderby'];
			else
				$current_orderby = 'affiliateId';

			if ( isset( $_GET['order'] ) && 'desc' == $_GET['order'] )
				$current_order = 'desc';
			else
				$current_order = 'asc';
						
			$orderBy = array( $current_orderby => $current_order );
						
			$filterWhere = array();
			$minPayout = NULL;

			if (!isset($request['statusFilter']))
				$request['statusFilter'] = 'all_active';

			if ($request['statusFilter'] !== 'all')
			{
				switch ($request['statusFilter'])
				{
					case 'all_active':
						$filterWhere['status'] = array('NOT IN', array('declined', 'blocked', 'inactive'));
						break;
					case 'all':
						break;

					case 'active':
					case 'applied':
					case 'approved':
					case 'confirmed':
					case 'declined':
					case 'blocked':
					case 'inactive':
						$filterWhere['status'] = $request['statusFilter'];

					default: break;
				}
			}
			
			if (isset($request['overPayout']) && $request['overPayout'] === 'true')
			{
				$minPayout = get_option(WPAM_PluginConfig::$MinPayoutAmountOption);
			}

			$models = $db->getAffiliateRepository()->loadAffiliateSummary( $filterWhere, $minPayout, $orderBy );


			$response = new WPAM_Pages_TemplateResponse('admin/affiliates_list');
			$response->viewData['minPayoutAmount'] = get_option(WPAM_PluginConfig::$MinPayoutAmountOption);
			$response->viewData['affiliates'] = $models;
			$response->viewData['request'] = $request;
			$response->viewData['statusFilters'] = array(
				'all_active' => __( 'All Active', 'wpam' ),
				'all' => __( 'All (Including Closed)', 'wpam' ),
				'---' => '---',
				'active' => __( 'Status: Active', 'wpam' ),
				'applied' => __( 'Status: Applied', 'wpam' ),
				'approved' => __( 'Status: Approved', 'wpam' ),
				'confirmed' => __( 'Status: Confirmed', 'wpam' ),
				'declined' => __( 'Status: Declined', 'wpam' ),
				'blocked' => __( 'Status: Blocked', 'wpam' ),
				'inactive' => __( 'Status: Inactive', 'wpam' ),
			);

			$response->viewData['current_orderby'] = $current_orderby;
			$response->viewData['current_order'] = $current_order;
		}
		return $response;
	}

	protected function getDetailForm($affiliateFields, $model, $request = null, $validationResult = null)
	{
		//add widget_form_error js to affiliate_detail page
		add_action('admin_footer', array( $this, 'onFooter' ) );

		$db = new WPAM_Data_DataAccess();
		$response = new WPAM_Pages_TemplateResponse('admin/affiliate_detail');
		$response->viewData['affiliateFields'] = $affiliateFields;
		$response->viewData['affiliate'] = $model;

		$where = array('affiliateId' => $model->affiliateId);
		
		$affiliateHelper = new WPAM_Util_AffiliateFormHelper();
		$affiliateHelper->addTransactionDateRange( $where, $request, $response );

		$response->viewData['transactions'] = $db->getTransactionRepository()->loadMultipleBy(
			$where,
			array('dateCreated' => 'desc')
			);
		
		$response->viewData['showBalance'] = true;
		$response->viewData['paymentMethods'] = $affiliateHelper->getPaymentMethods();
		$response->viewData['paymentMethod'] = isset( $request['ddPaymentMethod'] ) ? $request['ddPaymentMethod'] : $model->paymentMethod;
		$response->viewData['paypalEmail'] = isset( $request['txtPaypalEmail'] ) ? $request['txtPaypalEmail'] : $model->paypalEmail;		
		$response->viewData['bountyType'] = isset( $request['ddBountyType'] ) ? $request['ddBountyType'] : $model->bountyType;
		$response->viewData['bountyAmount'] = isset( $request['txtBountyAmount'] ) ? $request['txtBountyAmount'] : $model->bountyAmount;		

		$this->addBalance( $response->viewData['transactions'],
						   $db->getTransactionRepository()->getBalance(
							   $model->affiliateId,
							   empty( $request['from'] ) ? NULL : $request['from']
						   ),
						   'desc' );

		$accountStanding = $db->getTransactionRepository()->getAccountSummary($model->affiliateId);

		$response->viewData['accountStanding'] = $accountStanding->standing;
		$response->viewData['accountCredits'] = $accountStanding->credits;
		$response->viewData['accountDebits'] = $accountStanding->debits;
		$response->viewData['accountAdjustments'] = $accountStanding->adjustments;
		
		$response->viewData['user'] = new WP_User($model->userId);

		if ($request !== null) {
			$response->viewData['request'] = $request;
		}
		if ($validationResult !== null) {
			//die(print_r($validationResult, true));
			$response->viewData['validationResult'] = $validationResult;
		}
		$response->viewData['affiliateFields'] = $affiliateFields;
		$response->viewData['creatives'] = $db->getCreativesRepository()->loadAllActiveNoDeletes();

		//save for form validation in the footer
		$this->response = $response;

		return $response;

	}

	protected function addBalance( &$transactions, $balance, $order = 'desc' ) {
		$ordered_transactions = $transactions;
		if ( $order == 'desc' )
			$ordered_transactions = array_reverse( $transactions, true );

		foreach ( $ordered_transactions as $index => $info ) {
			$balance += $info->amount;
			$transactions[$index]->balance = $balance;
		}
	}
	
	public function onFooter() {		
		wp_enqueue_script( 'wpam_contact_info' );
		wp_localize_script( 'wpam_contact_info', 'currencyL10n', array(
								'fixedLabel' => sprintf( __( 'Bounty Rate (%s per Sale)', 'wpam' ), WPAM_MoneyHelper::getDollarSign() ),
								'percentLabel' => __( 'Bounty Rate (% of Sale)', 'wpam' ),
								'okLabel' => __( 'OK', 'wpam' ),
		) );
		wp_enqueue_script( 'wpam_money_format' );

		$response = new WPAM_Pages_TemplateResponse('widget_form_errors', $this->response->viewData);
		echo $response->render();
	}
	
}
