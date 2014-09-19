<?php


class WPAM_PluginConfig
{
	/***** WP Capabilities *****/
	public static $AdminCap = 'wpam_admin';
	public static $AffiliateCap = 'wpam_affiliate';
	public static $AffiliateActiveCap = 'wpam_affiliate_active';

	/***** WP Options *****/
	//public static $ReferralEncryptKeyOption = 'wpam_refkey_encrypt_key';
	public static $TNCOptionOption = 'wpam_tnc_body';
	public static $MinPayoutAmountOption = 'wpam_min_payout';
	public static $CookieExpireOption = 'wpam_cookie_expire';
	public static $EmailNameOption = 'wpam_email_name';
	public static $EmailAddressOption = 'wpam_email_address';
        public static $AutoAffiliateApproveIsEnabledOption = 'wpam_auto_aff_approve_enabled';
        public static $AffBountyType = 'wpam_affbounty_type';
        public static $AffBountyAmount = 'wpam_affbounty_amount';
        public static $AffCurrencySymbol = 'wpam_affcurrency_symbol';
        public static $AffCurrencyCode = 'wpam_affcurrency_code';
        public static $AffEnableImpressions = 'wpam_enable_impressions';
        public static $AffEnableDebug = 'wpam_enable_debug';
	public static $PayoutMethodCheckIsEnabledOption = 'wpam_payout_check_enabled';
	public static $PayoutMethodPaypalIsEnabledOption = 'wpam_payout_paypal_enabled';
	public static $HomePageId = 'wpam_home_page_id';
	public static $RegPageId = 'wpam_register_page_id';
        public static $AffLoginPageURL = 'wpam_aff_login_page';
	// paypal
	public static $PaypalMassPayEnabledOption = 'wpam_paypal_mass_pay_enabled';
	public static $PaypalAPIUserOption = 'wpam_paypal_api_user';
	public static $PaypalAPIPasswordOption = 'wpam_paypal_api_password';
	public static $PaypalAPISignatureOption = 'wpam_paypal_api_signature';
	public static $PaypalAPIEndPointOption = 'wpam_paypal_api_endpoint';

	/***** MISC *****/
	public static $RefKey = 'wpam_refkey';
        public static $DefaultCreativeId = 'wpam_default_creative_id';

	/***** short codes *****/
	public static $ShortCodeHome = '[AffiliatesHome]';
	public static $ShortCodeRegister = '[AffiliatesRegister]';
        public static $ShortCodeLogin = '[AffiliatesLogin]';
	
}

