<?php

class WPAM_Click_Tracking {

    public static function record_click() {
        if (isset($_REQUEST[WPAM_PluginConfig::$RefKey]) && !empty($_REQUEST[WPAM_PluginConfig::$RefKey])) { //this is the old tracking code (deprecated). This will be removed once the new tracking system is functional.
            global $wpdb;
            $strRefKey = trim(strip_tags($_REQUEST[WPAM_PluginConfig::$RefKey]));
            $aff_id = "";
            if (is_numeric($strRefKey)) {  //wpam_refkey contains affiliate ID. If a record is found save it
                $query = "SELECT * FROM ".WPAM_AFFILIATES_TBL." WHERE affiliateId = %d";        
                $affiliate = $wpdb->get_row($wpdb->prepare($query, $strRefKey));
                if($affiliate != null && $affiliate->status == "active") {
                    $aff_id = $strRefKey;
                }
            } 
            else if (is_email($strRefKey)) {  //wpam_refkey contains email. Find the ID associated with that email and save it
                $query = "SELECT * FROM ".WPAM_AFFILIATES_TBL." WHERE email = %s";        
                $affiliate = $wpdb->get_row($wpdb->prepare($query, $strRefKey));
                if($affiliate != null && $affiliate->status == "active") {
                    $aff_id = $affiliate->affiliateId;
                }
            }
            else{   //TODO start - wpam_refkey contains long tracking key. Find affiliate ID from it and save it. This block of code will just be here for backwards compatibilty
                $refKey = new WPAM_Tracking_TrackingKey();
                $refKey->unpack($strRefKey);
                $db = new WPAM_Data_DataAccess();
                $affiliateRepos = $db->getAffiliateRepository();
                $affiliateId = $affiliateRepos->getAffiliateIdFromRefKey($refKey->getAffiliateRefKey());
                if ($affiliateId === NULL) {
                    
                }
                else{
                    $aff_id = $affiliateId;
                }
            }
            //TODO end
            if(!empty($aff_id)){
                $cookie_life_time = wpam_get_cookie_life_time();
                $domain_url = $_SERVER['SERVER_NAME'];
                $cookie_domain = str_replace("www", "", $domain_url);
                setcookie('wpam_id', $aff_id, $cookie_life_time, "/", $cookie_domain);
            }
        }
        //this will be the new affiliate link. A click will be tracked when wpam_id is present in the URL
        if (isset($_REQUEST[WPAM_PluginConfig::$wpam_id]) && !empty($_REQUEST[WPAM_PluginConfig::$wpam_id])) {
            $aff_id = trim(strip_tags($_REQUEST[WPAM_PluginConfig::$wpam_id]));
            $cookie_life_time = wpam_get_cookie_life_time();
            $domain_url = $_SERVER['SERVER_NAME'];
            $cookie_domain = str_replace("www", "", $domain_url);
            setcookie('wpam_id', $aff_id, $cookie_life_time, "/", $cookie_domain);
            $args = array();
            $args['dateCreated'] = date("Y-m-d H:i:s", time());
            $args['sourceAffiliateId'] = $aff_id;
            $args['trackingKey'] = uniqid(); //save a unique ID to avoid error
            $args['sourceCreativeId'] = '';  // remove this column from the click tracking menu in the settings
            $args['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $args['affiliateSubCode'] = '';
            WPAM_Click_Tracking::insert_click_data($args);
        }
        
    }
    
    public static function insert_click_data($args){
        global $wpdb;
        $table = WPAM_TRACKING_TOKENS_TBL;
        $wpdb->insert( $table, $args);
    }
    
    /*
     * Gets total number of clicks for a given affiliate.
     * $args array requires at least 3 elements 
     * aff_id - affiliate ID
     * start_date - start date 
     * end_date - end date
     */
    public static function get_total_clicks($args){  //$args() at least requires 3 elements: affiliate ID, start date and end date
        global $wpdb;
        $table = WPAM_TRACKING_TOKENS_TBL;
        $total_clicks = 0;
        $result = $wpdb->get_var( $wpdb->prepare( 
	"
		SELECT COUNT(*) 
		FROM $table 
		WHERE sourceAffiliateId = %d
                AND dateCreated >= %s
                AND dateCreated < %s
                
	",
        $args['aff_id'],        
	$args['start_date'],
        $args['end_date']        
        ) );
        if($result != null){
            $total_clicks = $result;
        }
        return $total_clicks;
    }
    /*
     * Gets all time total number of clicks for a given affiliate.
     * $args array requires only 1 element
     * aff_id - affiliate ID
     */
    public static function get_all_time_total_clicks($args){  //$args() at least requires affiliate ID
        global $wpdb;
        $table = WPAM_TRACKING_TOKENS_TBL;
        $total_clicks = 0;
        $result = $wpdb->get_var( $wpdb->prepare( 
	"
		SELECT COUNT(*) 
		FROM $table 
		WHERE sourceAffiliateId = %d              
	",
        $args['aff_id']             
        ) );
        if($result != null){
            $total_clicks = $result;
        }
        return $total_clicks;
    }

}
