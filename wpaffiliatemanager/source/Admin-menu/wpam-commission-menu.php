<?php

//display commission menu
function wpam_display_commission_menu()
{
    ?>
    <div class="wrap">
    <h2><?php _e('Affiliate Commissions', 'affiliates-manager');?></h2>
    <?php
    $wpam_commission_tabs = array(
        'wpam-commission' => __('Overall Commissions', 'affiliates-manager'),
        'wpam-commission&action=manual-commission' => __('Manual Commission', 'affiliates-manager'),
    ); 

    if(isset($_GET['page'])){
        $current = sanitize_text_field($_GET['page']);
        if(isset($_GET['action'])){
            $current .= "&action=".sanitize_text_field($_GET['action']);
        }
    }
    $content = '';
    $content .= '<h2 class="nav-tab-wrapper">';
    foreach($wpam_commission_tabs as $location => $tabname)
    {
        if($current == $location){
            $class = ' nav-tab-active';
        } else{
            $class = '';    
        }
        $content .= '<a class="nav-tab'.$class.'" href="?page='.$location.'">'.$tabname.'</a>';
    }
    $content .= '</h2>';
    echo $content;
    if(isset($_GET['action']) && $_GET['action'] == "manual-commission"){
        wpam_display_manual_commission_tab();
    }
    else if(isset($_GET['action']) && $_GET['action'] == "edit-commission"){
        wpam_display_edit_commission_tab();
    }
    else{
        wpam_display_overall_commission_tab();
    }
    ?>
    </div>
    <?php
}

function wpam_display_overall_commission_tab()
{
    ?>   
    <p><?php _e('This tab shows all affiliate commission data', 'affiliates-manager');?></p>
    <div id="poststuff"><div id="post-body">
    <?php        
    
    include_once(WPAM_BASE_DIRECTORY . '/classes/ListCommissionTable.php');
    //Create an instance of our package class...
    $commission_list_table = new WPAM_List_Commission_Table();
    //Fetch, prepare, sort, and filter our data...
    $commission_list_table->prepare_items();
    ?>
    <!--
    <style type="text/css">
        .column-transactionId {width:6%;}
        .column-dateCreated {width:20%;}
        .column-affiliateId {width:6%;}
        .column-amount {width:10%;}
        .column-referenceId {width:25%;}
    </style>
    -->
    <div class="wpam-commission-data">

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="wpam-commission-data-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
            <!-- Now we can render the completed list table -->
            <?php $commission_list_table->display() ?>
        </form>

    </div>

    </div></div>
    <?php
}

function wpam_display_manual_commission_tab()
{
    /*
    $data['dateModified'] = date("Y-m-d H:i:s", time());
    $data['dateCreated'] = date("Y-m-d H:i:s", time());
    $data['referenceId'] = $txn_id;
    $data['affiliateId'] = $affiliate->affiliateId;
    $data['type'] = 'credit';
    $data['description'] = $description;
    $data['amount'] = $creditAmount;
    $wpdb->insert( $table, $data);
    */
    if (isset($_POST['wpam_manual_commission_save']))
    {
        $nonce = $_REQUEST['_wpnonce'];
        if ( !wp_verify_nonce($nonce, 'wpam_manual_commission_save')){
                wp_die('Error! Nonce Security Check Failed! Go back to the manual commission menu and add a commission again.');
        }
        $error_msg = '';
        $aff_id = sanitize_text_field($_POST["wpam_aff_id"]);
        if(empty($aff_id)){
            $error_msg .= '<p>'.__('You need to enter an affiliate ID', 'affiliates-manager').'</p>';;
        }
        $commission_amt = sanitize_text_field($_POST["wpam_commission_amt"]);
        if(!is_numeric($commission_amt)){
            $error_msg .= '<p>'.__('You need to enter a numeric commission amount', 'affiliates-manager').'</p>';;
        }
        $purchase_amt = sanitize_text_field($_POST["wpam_purchase_amt"]);
        if(!is_numeric($purchase_amt)){
            $error_msg .= '<p>'.__('You need to enter a numeric purchase amount', 'affiliates-manager').'</p>';;
        }
        $txn_id = sanitize_text_field($_POST["wpam_txn_id"]);
        if(empty($txn_id)){
            $txn_id = uniqid();
        }
        $buyer_email = sanitize_email($_POST["wpam_buyer_email"]);
        $date_created = sanitize_text_field($_POST["wpam_date_created"]);
        if(isset($date_created) && date("Y-m-d", strtotime($date_created)) === $date_created){  //valid date
            
        }
        else{            
            $date_created = date("Y-m-d");
        }
        $time_created = date("H:i:s");
        $selected_date = $date_created." ".$time_created;
        $mysql_date_created = date("Y-m-d H:i:s", strtotime($selected_date));
        
        global $wpdb;
        $table = WPAM_TRANSACTIONS_TBL;
        $query = "
        SELECT *
        FROM ".$table."
        WHERE referenceId = %s    
        ";
        $txn_record = $wpdb->get_row($wpdb->prepare($query, $txn_id));
        if($txn_record != null) {  //found a record
            $error_msg .= '<p>'.__('A commission with this transaction ID already exists', 'affiliates-manager').'</p>';
        }
        
        if(empty($error_msg)){ //no error in form submission
            $args = array();
            $args['date_modified'] = $mysql_date_created;
            $args['date_created'] = $mysql_date_created;
            $args['txn_id'] = $txn_id;
            $args['aff_id'] = $aff_id;
            $args['amount'] = $purchase_amt;
            $args['manual_comm_amount'] = $commission_amt;
            if(isset($buyer_email) && !empty($buyer_email)){
                $args['email'] = $buyer_email;
            }
            WPAM_Commission_Tracking::award_commission($args);
            echo '<div id="message" class="updated fade"><p><strong>';
            echo __('Commission added!', 'affiliates-manager');
            echo '</strong></p></div>';
        }
        else{
            echo '<div id="message" class="error fade"><p><strong>';
            echo $error_msg;
            echo '</strong></p></div>';
        }
    }
    ?>
    <p><?php _e('This tab allows you to manually award commission to an affiliate.', 'affiliates-manager');?></p>
    <div id="poststuff"><div id="post-body">
            
    <form method="post" action="">
    <?php wp_nonce_field('wpam_manual_commission_save'); ?>
    <table class="form-table" border="0" cellspacing="0" cellpadding="6" style="max-width:650px;">

    <tr valign="top">
    <th scope="row"><label for="wpam_aff_id"><?php _e('Affiliate ID', 'affiliates-manager');?></label></th>
    <td><input name="wpam_aff_id" type="text" id="wpam_aff_id" size="15" value="" class="regular-text">
    <p class="description"><?php _e('Enter the affiliate ID. Example: ', 'affiliates-manager');?>1</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_commission_amt"><?php _e('Commission Amount', 'affiliates-manager');?></label></th>
    <td><input name="wpam_commission_amt" type="text" id="wpam_commission_amt" size="15" value="" class="regular-text">
    <p class="description"><?php _e('Enter the commission amount. Example: ', 'affiliates-manager');?>5.00</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_purchase_amt"><?php _e('Purchase Amount', 'affiliates-manager');?></label></th>
    <td><input name="wpam_purchase_amt" type="text" id="wpam_purchase_amt" size="15" value="" class="regular-text">
    <p class="description"><?php _e('Enter the purchase amount. Example: ', 'affiliates-manager');?>15.00</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_txn_id"><?php _e('Transaction ID', 'affiliates-manager');?></label></th>
    <td><input name="wpam_txn_id" type="text" id="wpam_txn_id" size="15" value="" class="regular-text">
    <p class="description"><?php _e('Enter the unique transaction ID (leave empty to generate a unique ID). Example: ', 'affiliates-manager');?>1423</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_buyer_email"><?php _e('Buyer Email', 'affiliates-manager');?></label></th>
    <td><input name="wpam_buyer_email" type="text" id="wpam_buyer_email" size="15" value="" class="regular-text">
    <p class="description"><?php _e('Enter the email address of the buyer (optional).', 'affiliates-manager');?></p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_date_created"><?php _e('Date', 'affiliates-manager');?></label></th>
    <td><input name="wpam_date_created" type="text" id="wpam_date_created" size="15" value="<?php echo date("Y-m-d");?>" class="regular-text">
    <p class="description"><?php _e('Enter the date in yyyy-mm-dd format. Example: ', 'affiliates-manager');?>2015-09-17</p></td>
    </tr>

    <td width="25%" align="left">
    <div class="submit">
        <input type="submit" name="wpam_manual_commission_save" class="button-primary" value="Save &raquo;" />
    </div>                
    </td> 

    </tr>

    </table>

    </form>
            
    </div></div>
    <script>
    jQuery(function($) {
        $( "#wpam_date_created" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
    </script>
    <?Php
}

function wpam_display_edit_commission_tab()
{
    /*
    $data['dateModified'] = date("Y-m-d H:i:s", time());
    $data['dateCreated'] = date("Y-m-d H:i:s", time());
    $data['referenceId'] = $txn_id;
    $data['affiliateId'] = $affiliate->affiliateId;
    $data['type'] = 'credit';
    $data['description'] = $description;
    $data['amount'] = $creditAmount;
    $wpdb->insert( $table, $data);
    */
    global $wpdb;
    $table = WPAM_TRANSACTIONS_TBL;
    if(!isset($_GET['page']) || $_GET['page'] != "wpam-commission"){
        return;
    }
    if(!isset($_GET['action']) || $_GET['action'] != "edit-commission"){
        return;
    }
    if(!isset($_GET['edit_rowid']) || empty($_GET['edit_rowid'])){
        return;
    }
    $edit_rowid = sanitize_text_field($_GET['edit_rowid']);
    if (isset($_POST['wpam_edit_commission_save']))
    {
        $nonce = $_REQUEST['_wpnonce'];
        if(!wp_verify_nonce($nonce, 'wpam_edit_commission_save')){
            wp_die(__('Error! Nonce Security Check Failed! Go back to the Commissions menu and edit the commission again.', 'affiliates-manager'));
        }
        $data = array();
        $error = false;
        $aff_id = sanitize_text_field($_POST["wpam_aff_id"]);
        if(empty($aff_id)){
            $error = true;
            echo '<div id="message" class="error fade"><p><strong>';
            echo __('You need to enter an affiliate ID', 'affiliates-manager');
            echo '</strong></p></div>';
        }
        $data['affiliateId'] = $aff_id;
        $commission_amt = sanitize_text_field($_POST["wpam_commission_amt"]);
        if(!is_numeric($commission_amt)){
            $error = true;
            echo '<div id="message" class="error fade"><p><strong>';
            echo __('You need to enter a numeric commission amount', 'affiliates-manager');
            echo '</strong></p></div>';
        }
        $data['amount'] = $commission_amt;
        $txn_id = sanitize_text_field($_POST["wpam_txn_id"]);
        if(empty($txn_id)){
            $error = true;
            echo '<div id="message" class="error fade"><p><strong>';
            echo __('You need to enter a unique transaction ID', 'affiliates-manager');
            echo '</strong></p></div>';
        }
        $data['referenceId'] = $txn_id;
        $buyer_email = sanitize_email($_POST["wpam_buyer_email"]);
        $data['email'] = $buyer_email;
        $date_created = sanitize_text_field($_POST["wpam_date_created"]);
        if(isset($date_created) && date("Y-m-d H:i:s", strtotime($date_created)) === $date_created){  //valid date
            $data['dateModified'] = $date_created;
            $data['dateCreated'] = $date_created;
        }
        else{
            $error = true;
            echo '<div id="message" class="error fade"><p><strong>';
            echo __('You need to enter a valid date', 'affiliates-manager');
            echo '</strong></p></div>';            
        }
        if(!$error){
            $where = array('transactionId' => $edit_rowid);
            $updated = $wpdb->update($table, $data, $where);
            if(false === $updated) {
                echo '<div id="message" class="error fade"><p><strong>';
                echo __('Commission could not be updated', 'affiliates-manager');
                echo '</strong></p></div>';
            }
            else{
                echo '<div id="message" class="updated fade"><p><strong>';
                echo __('Commission updated!', 'affiliates-manager');
                echo '</strong></p></div>';
            }
        }
    }
    
    $query = "
    SELECT *
    FROM ".$table."
    WHERE transactionId = %s    
    ";
    $result = $wpdb->get_row($wpdb->prepare($query, $edit_rowid));
    if($result === null) {  //no record found
        $error_msg .= '<p>'.__('Commission could not be found', 'affiliates-manager').'</p>';
        return $error_msg;
    }
    
    ?>
    <p><?php _e('This tab allows you to edit a commission record.', 'affiliates-manager');?></p>
    <div id="poststuff"><div id="post-body">
            
    <form method="post" action="">
    <?php wp_nonce_field('wpam_edit_commission_save'); ?>
    <table class="form-table" border="0" cellspacing="0" cellpadding="6" style="max-width:650px;">

    <tr valign="top">
    <th scope="row"><label for="wpam_aff_id"><?php _e('Affiliate ID', 'affiliates-manager');?></label></th>
    <td><input name="wpam_aff_id" type="text" id="wpam_aff_id" size="15" value="<?php echo esc_attr($result->affiliateId); ?>" class="regular-text">
    <p class="description"><?php _e('The affiliate ID. Example: ', 'affiliates-manager');?>1</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_commission_amt"><?php _e('Commission Amount', 'affiliates-manager');?></label></th>
    <td><input name="wpam_commission_amt" type="text" id="wpam_commission_amt" size="15" value="<?php echo esc_attr($result->amount); ?>" class="regular-text">
    <p class="description"><?php _e('The commission amount. Example: ', 'affiliates-manager');?>5.00</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_txn_id"><?php _e('Transaction ID', 'affiliates-manager');?></label></th>
    <td><input name="wpam_txn_id" type="text" id="wpam_txn_id" size="15" value="<?php echo esc_attr($result->referenceId); ?>" class="regular-text">
    <p class="description"><?php _e('The unique transaction ID. Example: ', 'affiliates-manager');?>1423</p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_buyer_email"><?php _e('Buyer Email', 'affiliates-manager');?></label></th>
    <td><input name="wpam_buyer_email" type="text" id="wpam_buyer_email" size="15" value="<?php echo esc_attr($result->email); ?>" class="regular-text">
    <p class="description"><?php _e('The email address of the buyer (optional).', 'affiliates-manager');?></p></td>
    </tr>
    
    <tr valign="top">
    <th scope="row"><label for="wpam_date_created"><?php _e('Date', 'affiliates-manager');?></label></th>
    <td><input name="wpam_date_created" type="text" id="wpam_date_created" size="15" value="<?php echo esc_attr($result->dateCreated); ?>" class="regular-text">
    <p class="description"><?php _e('The date in Y-m-d H:i:s format. Example: ', 'affiliates-manager');?>2022-01-20 05:04:54</p></td>
    </tr>

    <td width="25%" align="left">
    <div class="submit">
        <input type="submit" name="wpam_edit_commission_save" class="button-primary" value="Save &raquo;" />
    </div>                
    </td> 

    </tr>

    </table>

    </form>
            
    </div></div>
    <!--
    <script>
    jQuery(function($) {
        $( "#wpam_date_created" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
    </script>-->
    <?Php
}
