<?php
/**
 * @author John Hargrove
 * 
 * Date: 1/3/11
 * Time: 11:01 PM
 */


/**
 * New wrapper for wp_options
 * 
 * TODO: setters, with internal validation
 */
class WPAM_Options
{
	public function getPaypalAPIUser() { return get_option( WPAM_PluginConfig::$PaypalAPIUserOption ); }
	public function getPaypalAPIPassword() { return get_option( WPAM_PluginConfig::$PaypalAPIPasswordOption ); }
	public function getPaypalAPISignature() { return get_option( WPAM_PluginConfig::$PaypalAPISignatureOption ); }
	public function getPaypalAPIEndPoint() { return get_option( WPAM_PluginConfig::$PaypalAPIEndPointOption ); }
	
	public function getPaypalAPIEndPointURL() {
		switch ( get_option( WPAM_PluginConfig::$PaypalAPIEndPointOption ) )
		{
			case 'dev': return WPAM_PayPal_Service::PAYPAL_API_ENDPOINT_SANDBOX;
			case 'live': return WPAM_PayPal_Service::PAYPAL_API_ENDPOINT_LIVE;
			default: throw new Exception( __( 'Invalid PaPpal API value', 'wpam' ) );
		}
	}

	public function getPaypalMassPayEnabled() { return (int)get_option( WPAM_PluginConfig::$PaypalMassPayEnabledOption ); }
 	
	public function initOptions()
	{
		add_option( WPAM_PluginConfig::$CookieExpireOption,                30,    NULL, 'no' );
		add_option( WPAM_PluginConfig::$EmailNameOption,                   NULL, NULL, 'no' );
		add_option( WPAM_PluginConfig::$EmailAddressOption,                NULL, NULL, 'no' );
                add_option( WPAM_PluginConfig::$AutoAffiliateApproveIsEnabledOption,  true, NULL, 'no' );
                add_option( WPAM_PluginConfig::$AffBountyType, 'percent' );
                add_option( WPAM_PluginConfig::$AffBountyAmount, 25 );
                add_option( WPAM_PluginConfig::$AffCurrencySymbol, '$' );
                add_option( WPAM_PluginConfig::$AffCurrencyCode, 'USD' );
                add_option( WPAM_PluginConfig::$AffEnableImpressions, true, NULL, 'no' );
		add_option( WPAM_PluginConfig::$PayoutMethodCheckIsEnabledOption,  true, NULL, 'no' );
		add_option( WPAM_PluginConfig::$PayoutMethodPaypalIsEnabledOption, true, NULL, 'no' );

		add_option( WPAM_PluginConfig::$TNCOptionOption, file_get_contents( WPAM_RESOURCES_DIR . "default_tnc.txt" ) );
		add_option( WPAM_PluginConfig::$MinPayoutAmountOption, 20 );
		add_option( WPAM_PluginConfig::$PaypalAPIEndPointOption, 'dev' );
	}
}

//display clicks menu
function wpam_display_clicks_menu()
{
    ?>
    <div class="wrap">
    <h2><?php _e('Affiliate Clickthrough Data', 'wpam');?></h2>
    <div id="poststuff"><div id="post-body">
    <?php        
    
    $limit = 50;
    
    /*
    if (isset($_REQUEST['deleted'])) {
        echo '<div id="message" class="updated fade"><p><strong>';
        echo $_REQUEST['msg'];
        echo '</strong></p></div>';
    }
    if (isset($_REQUEST['Delete'])) {
        $prod_id = $_REQUEST['prod_id'];
        if (wp_eStore_delete_product_data($prod_id)) {
            $message = "Product successfully deleted";
        } else {
            $message = "An error occurded while trying to delete the entry";
        }
        $redirect_url = 'admin.php?page=wp-cart-for-digital-products/wp_eStore1.php&deleted=1&msg=' . urlencode($message);
        eStore_redirect_to_url($redirect_url);
    }

    if (isset($_POST['info_update'])) {
        $search_term = (string) $_POST["eStore_product_search"];
        update_option('eStore_product_search', (string) $_POST["eStore_product_search"]);
        eStore_display_searched_products($search_term);
    } else {
        eStore_display_products($limit);
    }
    */
    
    include_once(WPAM_BASE_DIRECTORY . '/classes/aff_list_clicks_table.php');
    //Create an instance of our package class...
    $clicks_list_table = new WPAM_List_Clicks_Table();
    //Fetch, prepare, sort, and filter our data...
    $clicks_list_table->prepare_items();
    ?>
    <style type="text/css">
        .column-trackingTokenId {width:6%;}
        .column-dateCreated {width:20%;}
        .column-sourceAffiliateId {width:6%;}
        .column-trackingKey {width:25%;}
        .column-sourceCreativeId {width:6%;}
        .column-referer {width:25%;}
    </style>
    <div class="wpam-click-throughs">

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="wpam-click-throughs-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $clicks_list_table->display() ?>
        </form>

    </div>

    </div></div>
    </div>
    <?php
}
