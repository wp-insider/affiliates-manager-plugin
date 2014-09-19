<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 6, 2010
 * Time: 6:26:31 PM
 */
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/TrackingTokenModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Tracking/TrackingKey.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/ImpressionModel.php";

//any 'wpsca' checks can go away sometime in the future (changed to wpam in 12/2012)
class WPAM_Tracking_RequestTracker {

	public function handleCheckout( $purchaseLogId, $purchaseAmount ) {
		
		$db = new WPAM_Data_DataAccess();
		$binConverter = new WPAM_Util_BinConverter();
		$affiliate = NULL;

		// keeping this block and "($affiliate !== NULL)" seperate to
		// help indicate any problems
		// (purchase log recorded w/o a purchase event)

		$strRefKey = NULL;

		if ( isset( $_COOKIE[WPAM_PluginConfig::$RefKey] ) ) {
                    $strRefKey = $_COOKIE[WPAM_PluginConfig::$RefKey];
		}
		
		if ( $strRefKey ) {
			
			$trackingToken = $db->getTrackingTokenRepository()->loadBy( array(
				'trackingKey' => $strRefKey,
			) );

			if ( $trackingToken !== NULL ) {
				$ttpl = $db->getTrackingTokenPurchaseLogRepository()->loadBy( array(
					'trackingTokenId' => $trackingToken->trackingTokenId,
					'purchaseLogId' => $purchaseLogId
				));

				if ( $ttpl === NULL ) {
					$trackingTokenPurchaseLog = new WPAM_Data_Models_TrackingTokenPurchaseLogModel();
					$trackingTokenPurchaseLog->trackingTokenId = $trackingToken->trackingTokenId;
					$trackingTokenPurchaseLog->purchaseLogId = $purchaseLogId;

					$db->getTrackingTokenPurchaseLogRepository()->insert( $trackingTokenPurchaseLog );
					//this will be handled further down if the affiliate is set and the purchase was successful
					//$db->getEventRepository()->quickInsert(time(), $strRefKey, 'purchase');
				}
			}
		}

		$affiliate = $db->getAffiliateRepository()->loadByPurchaseLogId( $purchaseLogId );

		if ( $affiliate !== NULL && $affiliate->isActive() ) {
			
			if ( $strRefKey )
				$db->getEventRepository()->quickInsert( time(), $binConverter->stringToBin( $strRefKey ), 'purchase' );

			$creditAmount = $this->calculateCreditAmount( $affiliate, $purchaseAmount );
			$creditAmount = apply_filters( 'wpam_credit_amount', $creditAmount, $purchaseAmount, $purchaseLogId );
			$currency = WPAM_MoneyHelper::getCurrencyCode();
			$description = "Credit for sale of $purchaseAmount $currency (PURCHASE LOG ID = $purchaseLogId)";
			$existingCredit = $db->getTransactionRepository()->loadBy( array(
					'referenceId' => $purchaseLogId
				)
			);

			if ( $existingCredit === NULL ) {
				$credit = new WPAM_Data_Models_TransactionModel();
				$credit->dateCreated = time();
				$credit->referenceId = $purchaseLogId;
				$credit->affiliateId = $affiliate->affiliateId;
				$credit->type = 'credit';
				$credit->description = $description;
				$credit->amount = $creditAmount;

				$db->getTransactionRepository()->insert( $credit );

			} else {
				$existingCredit->dateModified = time();
				$existingCredit->description = $description;
				$existingCredit->amount = $creditAmount;
				$db->getTransactionRepository()->update( $existingCredit );
			}
		}
	}
	
	public function handleCheckoutWithRefKey( $purchaseLogId, $purchaseAmount, $strRefKey) {
		
		$db = new WPAM_Data_DataAccess();
		$binConverter = new WPAM_Util_BinConverter();
		$affiliate = NULL;

		// keeping this block and "($affiliate !== NULL)" seperate to
		// help indicate any problems
		// (purchase log recorded w/o a purchase event)
		
		if ( !empty($strRefKey )) {
			
			$trackingToken = $db->getTrackingTokenRepository()->loadBy( array(
				'trackingKey' => $strRefKey,
			) );

			if ( $trackingToken !== NULL ) {
				$ttpl = $db->getTrackingTokenPurchaseLogRepository()->loadBy( array(
					'trackingTokenId' => $trackingToken->trackingTokenId,
					'purchaseLogId' => $purchaseLogId
				));

				if ( $ttpl === NULL ) {
					$trackingTokenPurchaseLog = new WPAM_Data_Models_TrackingTokenPurchaseLogModel();
					$trackingTokenPurchaseLog->trackingTokenId = $trackingToken->trackingTokenId;
					$trackingTokenPurchaseLog->purchaseLogId = $purchaseLogId;

					$db->getTrackingTokenPurchaseLogRepository()->insert( $trackingTokenPurchaseLog );
					//this will be handled further down if the affiliate is set and the purchase was successful
					//$db->getEventRepository()->quickInsert(time(), $strRefKey, 'purchase');
				}
			}
		}

		$affiliate = $db->getAffiliateRepository()->loadByPurchaseLogId( $purchaseLogId );

		if ( $affiliate !== NULL && $affiliate->isActive() ) {
			
			if ( $strRefKey )
				$db->getEventRepository()->quickInsert( time(), $binConverter->stringToBin( $strRefKey ), 'purchase' );

			$creditAmount = $this->calculateCreditAmount( $affiliate, $purchaseAmount );
			$creditAmount = apply_filters( 'wpam_credit_amount', $creditAmount, $purchaseAmount, $purchaseLogId );
			$currency = WPAM_MoneyHelper::getCurrencyCode();
			$description = "Credit for sale of $purchaseAmount $currency (PURCHASE LOG ID = $purchaseLogId)";
			$existingCredit = $db->getTransactionRepository()->loadBy( array(
					'referenceId' => $purchaseLogId
				)
			);

			if ( $existingCredit === NULL ) {
				$credit = new WPAM_Data_Models_TransactionModel();
				$credit->dateCreated = time();
				$credit->referenceId = $purchaseLogId;
				$credit->affiliateId = $affiliate->affiliateId;
				$credit->type = 'credit';
				$credit->description = $description;
				$credit->amount = $creditAmount;

				$db->getTransactionRepository()->insert( $credit );

			} else {
				$existingCredit->dateModified = time();
				$existingCredit->description = $description;
				$existingCredit->amount = $creditAmount;
				$db->getTransactionRepository()->update( $existingCredit );
			}
		}
	}
        
	protected function calculateCreditAmount(WPAM_Data_Models_AffiliateModel $affiliate, $amount)
	{
		if ($affiliate->bountyType === 'percent')
		{
			return $amount * ($affiliate->bountyAmount / 100.0);
		}
		else if ($affiliate->bountyType === 'fixed')
		{
			return $affiliate->bountyAmount;
		}
	}

	public function handleIncomingReferral($request)
	{
		$strRefKey = NULL;

		if ( isset( $request[WPAM_PluginConfig::$RefKey] ) ) {
			$strRefKey = $request[WPAM_PluginConfig::$RefKey];
		} else {
			throw new Exception(  __( 'no refkey in request.', 'wpam' ) );
		}

		if ( ! array_key_exists( WPAM_PluginConfig::$RefKey, $_COOKIE ) ) {
			
			$refKey = new WPAM_Tracking_TrackingKey();
			$refKey->unpack( $strRefKey );
                        
			$db = new WPAM_Data_DataAccess();
			$affiliateRepos = $db->getAffiliateRepository();
			$affiliateId = $affiliateRepos->getAffiliateIdFromRefKey( $refKey->getAffiliateRefKey() );

			if ( $affiliateId === NULL ) {
				echo '<pre>Affiliate ID: ';
				var_export($affiliateId);
				echo "\n\n";
				echo $refKey;
				throw new Exception( __( 'invalid refkey data.', 'wpam' ) );
			}

			//#37 make sure the affiliate is active before tracking stats
			$affiliate = $affiliateRepos->load( $affiliateId );
			if ( ! $affiliate->isActive() )
				return;

			$idGenerator = new WPAM_Tracking_UniqueIdGenerator();
			$trackTokenModel = new WPAM_Data_Models_TrackingTokenModel();

			$trackTokenModel->dateCreated = time();
			$trackTokenModel->sourceAffiliateId = $affiliateId;
			$trackTokenModel->sourceCreativeId = $refKey->getCreativeId();
			$trackTokenModel->trackingKey = $idGenerator->generateId();
			$trackTokenModel->referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : NULL;

			if ( isset( $request['wpam_affiliateSubCode'] ) ) {
				$trackTokenModel->affiliateSubCode = $request['wpam_affiliateSubCode'];
			}

			$db->getTrackingTokenRepository()->insert( $trackTokenModel );
			$db->getEventRepository()->quickInsert( time(), $trackTokenModel->trackingKey, 'visit' );

			$binConverter = new WPAM_Util_BinConverter();
			// store the tracking key in a cookie so we can monitor their activities
			$return = setcookie( WPAM_PluginConfig::$RefKey,
								 $binConverter->binToString( $trackTokenModel->trackingKey ),
								 $this->getExpireTime(),
								 COOKIEPATH );
		}
	}

	public function handleImpression($request)
	{
		$strRefKey = NULL;

		if ( isset( $request[WPAM_PluginConfig::$RefKey] ) ) {
			$strRefKey = $request[WPAM_PluginConfig::$RefKey];
		} else {
			throw new Exception(  __( 'no refkey in request.', 'wpam' ) );
		}

		$refKey = new WPAM_Tracking_TrackingKey();
		$refKey->unpack( $strRefKey );

		$db = new WPAM_Data_DataAccess();
		$affiliateRepos = $db->getAffiliateRepository();
		$affiliateId = $affiliateRepos->getAffiliateIdFromRefKey( $refKey->getAffiliateRefKey() );

		if ( $affiliateId === NULL ) {
			throw new Exception( __( 'invalid refkey data: ', 'wpam' ) . $strRefKey );
		}

		$impressionModel = new WPAM_Data_Models_ImpressionModel();

		$impressionModel->dateCreated = time();
		$impressionModel->sourceAffiliateId = $affiliateId;
		$impressionModel->sourceCreativeId = $refKey->getCreativeId();
		$impressionModel->referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : NULL;

		if ( isset( $request['wpam_affiliateSubCode'] ) ) {
			$impressionModel->affiliateSubCode = $request['wpam_affiliateSubCode'];
		}

		$db->getImpressionRepository()->insert( $impressionModel );
	}

	protected function getExpireTime() {
		$days = get_option( WPAM_PluginConfig::$CookieExpireOption );
		if ( $days == 0 )
			return 0;

		return strtotime( "+{$days} days" );
	}
}
