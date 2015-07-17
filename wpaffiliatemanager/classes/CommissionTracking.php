<?php
class WPAM_Commission_Tracking {
    
    public static function award_commission($args){
        global $wpdb;
        $txn_id = $args['txn_id'];
        $amount = $args['amount'];
        $aff_id = $args['aff_id'];
        $affiliate = '';
        if(isset($aff_id) && is_numeric($aff_id)){  //aff_id contains affiliate ID from the new cookie system (wpam_id)
            $query = "SELECT * FROM ".WPAM_AFFILIATES_TBL." WHERE affiliateId = %d";        
            $affiliate = $wpdb->get_row($wpdb->prepare($query, $aff_id));    
        }
        else{ //TODO start - We only need this code for now to get the affiliate ID for a purchase. Later with the new tracking system it can be deleted            
            $query = "
            SELECT a.*
            FROM ".WPAM_TRACKING_TOKENS_PURCHASE_LOGS_TBL." pl
            INNER JOIN ".WPAM_TRACKING_TOKENS_TBL." tt ON (tt.trackingTokenId = pl.trackingTokenId)
            INNER JOIN ".WPAM_AFFILIATES_TBL." a ON (a.affiliateId = tt.sourceAffiliateId)
            WHERE
            pl.purchaseLogId = %s
            ";        
            $affiliate = $wpdb->get_row($wpdb->prepare($query, $txn_id));              
        } //TODO end - later affiliate ID can be tracked directly from the cookie instead of ref_key
        
        if($affiliate != null && $affiliate->status == "active") {
            $creditAmount = '';
            if ($affiliate->bountyType == 'percent')
            {
                $creditAmount = $amount * ($affiliate->bountyAmount / 100.0);
            }
            else if ($affiliate->bountyType == 'fixed')
            {
                $creditAmount = $affiliate->bountyAmount;
            }
            $creditAmount = apply_filters( 'wpam_credit_amount', $creditAmount, $amount, $txn_id );
            $currency = WPAM_MoneyHelper::getCurrencyCode();
            $description = "Credit for sale of $amount $currency (PURCHASE LOG ID = $txn_id)";
            $query = "
            SELECT *
            FROM ".WPAM_TRANSACTIONS_TBL."
            WHERE referenceId = %s    
            ";
            $txn_record = $wpdb->get_row($wpdb->prepare($query, $txn_id));
            if($txn_record != null) {  //found a record
                WPAM_Logger::log_debug('Commission for this sale has already been awarded. PURCHASE LOG ID: '.$txn_id.', Purchase amount: '.$amount);        
            } 
            else {
                $table = WPAM_TRANSACTIONS_TBL;
                $data = array();
                $data['dateModified'] = date("Y-m-d H:i:s", time());
                $data['dateCreated'] = date("Y-m-d H:i:s", time());
                $data['referenceId'] = $txn_id;
                $data['affiliateId'] = $affiliate->affiliateId;
                $data['type'] = 'credit';
                $data['description'] = $description;
                $data['amount'] = $creditAmount;
                $wpdb->insert( $table, $data);
                /*
                if($strRefKey){
                    $db->getEventRepository()->quickInsert( time(), $binConverter->stringToBin( $strRefKey ), 'purchase' );
                }
                */
            }
        }
    }
    
    /*
     * Gets total transaction count for a given affiliate.
     * $args array requires at least 3 elements 
     * aff_id - affiliate ID
     * start_date - start date 
     * end_date - end date
     */
    public static function get_transaction_count($args){ 
        global $wpdb;
        $table = WPAM_TRANSACTIONS_TBL;
        $count = 0;
        $result = $wpdb->get_var( $wpdb->prepare( 
	"
		SELECT COUNT(*) 
		FROM $table 
		WHERE affiliateId = %d
                AND dateCreated >= %s
                AND dateCreated < %s
                AND type = 'credit'
	",
        $args['aff_id'],        
	$args['start_date'],
        $args['end_date']        
        ) );
        if($result != null){
            $count = $result;
        }
        return $count;
    }
    /*
     * Gets all time total transaction count for a given affiliate.
     * $args array requires only 1 element
     * aff_id - affiliate ID
     */
    public static function get_all_time_transaction_count($args){ //$args() at least requires affiliate ID
        global $wpdb;
        $table = WPAM_TRANSACTIONS_TBL;
        $count = 0;
        $result = $wpdb->get_var( $wpdb->prepare( 
	"
		SELECT COUNT(*) 
		FROM $table 
		WHERE affiliateId = %d
                AND type = 'credit'
	",
        $args['aff_id']               
        ) );
        if($result != null){
            $count = $result;
        }
        return $count;
    }
    
    /*
     * Gets total commission amount for a given affiliate.
     * $args array requires at least 3 elements 
     * aff_id - affiliate ID
     * start_date - start date 
     * end_date - end date
     */    
    public static function get_total_commission_amount($args){ 
        global $wpdb;
        $table = WPAM_TRANSACTIONS_TBL;
        $total_commission = $wpdb->get_var( $wpdb->prepare( 
	"
		SELECT COALESCE(SUM(IF(type = 'credit', amount, 0)),0) 
		FROM $table 
		WHERE affiliateId = %d
                AND dateCreated >= %s
                AND dateCreated < %s
	",
        $args['aff_id'],        
	$args['start_date'],
        $args['end_date']        
        ) );
        return $total_commission;
    }
}
