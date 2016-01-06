<?php

function wpam_has_purchase_record($purchaseLogId){
    $db = new WPAM_Data_DataAccess();
    $affiliate = NULL;
    $has_record = true;
    $affiliate = $db->getAffiliateRepository()->loadByPurchaseLogId( $purchaseLogId );
    if($affiliate == NULL) {
        $has_record = false;
    }
    return $has_record;
}

function wpam_generate_refkey_from_affiliate_id($aff_id){
    $db = new WPAM_Data_DataAccess();
    $affiliateRepos1 = $db->getAffiliateRepository();
    $wpam_refkey = NULL;
    $affiliate = $affiliateRepos1->loadBy(array('affiliateId' => $aff_id, 'status' => 'active'));
    if ( $affiliate === NULL ) {  //affiliate with this ID does not exist
        WPAM_Logger::log_debug("generate_refkey_from_affiliate_id function - affiliate ID ".$aff_id." does not exist");
    }
    else
    {
        $default_creative_id = get_option(WPAM_PluginConfig::$DefaultCreativeId);
        if(!empty($default_creative_id))
        {
            $creative = $db->getCreativesRepository()->load($default_creative_id);
            $linkBuilder = new WPAM_Tracking_TrackingLinkBuilder($affiliate, $creative);
            $strRefKey = $linkBuilder->getTrackingKey()->pack();
            $refKey = new WPAM_Tracking_TrackingKey();
            $refKey->unpack( $strRefKey );

            $idGenerator = new WPAM_Tracking_UniqueIdGenerator();
            $trackTokenModel = new WPAM_Data_Models_TrackingTokenModel();

            $trackTokenModel->dateCreated = time();
            $trackTokenModel->sourceAffiliateId = $aff_id;
            $trackTokenModel->sourceCreativeId = $refKey->getCreativeId();
            $trackTokenModel->trackingKey = $idGenerator->generateId();
            $trackTokenModel->referer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : NULL;
            /* add a new visit so it doesn't fail while awarding commission */
            $db->getTrackingTokenRepository()->insert( $trackTokenModel );
            $db->getEventRepository()->quickInsert( time(), $trackTokenModel->trackingKey, 'visit' );
            /* */
            $binConverter = new WPAM_Util_BinConverter();
            $wpam_refkey = $binConverter->binToString( $trackTokenModel->trackingKey );
        }
    }
    return $wpam_refkey;
}

function wpam_get_cookie_life_time() {
    $cookie_expiry = get_option( WPAM_PluginConfig::$CookieExpireOption );
    $cookie_life_time = 0; //if set to 0 or omitted, the cookie will expire at the end of the session (when the browser closes).
    if (is_numeric($cookie_expiry) && $cookie_expiry > 0) {
        $cookie_life_time = time() + $cookie_expiry * 86400;
    }
    return $cookie_life_time;
}
