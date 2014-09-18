<?php
/**
 * @author Justin Foell
 */

class WPAM_Util_UserHandler {

	public function approveAffiliate( $affiliate, $bountyType, $bountyAmount, $update = true ) {
		$sendEmail = true;
		$mailer = new WPAM_Util_EmailHandler();
		$db = new WPAM_Data_DataAccess();

		//Create Affiliate account in WP (1.1.2 if they don't have one)
		//and send them an email telling them they're approved
			
		$userLogin = sanitize_user( $affiliate->email );
		$userEmail = apply_filters( 'user_registration_email', $affiliate->email );
			
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$message  = sprintf( __( 'New affiliate registration for %s: has been approved!', 'wpam' ) , $blogname ) . "\r\n\r\n";
			
		if ( username_exists( $userLogin ) ) {
			$user = new WP_User( get_userdatabylogin( $userLogin )->ID );
			if ( $user->has_cap( WPAM_PluginConfig::$AffiliateCap ) ) {
				throw new Exception( __( 'User already has an account and is already an affiliate', 'wpam' ) );
			} else {
				$user->add_cap( WPAM_PluginConfig::$AffiliateCap );
				$message .= __( 'Log into the site with your existing account and get started.', 'wpam' ) . "\r\n";
				$userId = $user->ID;
			}	
		} elseif ( email_exists( $userEmail ) ) {
			$user = new WP_User( get_user_by( 'email', $userEmail )->ID );
			if ( $db->getAffiliateRepository()->existsBy( array( 'userId' => $user->ID ) ) ) {
				throw new Exception( __( 'User already has an account and is already an affiliate', 'wpam' ) );
			} else {
				$user->add_cap( WPAM_PluginConfig::$AffiliateCap );
				$message .= __( 'Log into the site with your existing account and get started.', 'wpam' ) . "\r\n";
				$userId = $user->ID;
			}	
		} else {
			//user not found by email address as username and no account with that email address exists
			//create new user using email address as username
			$userPass = wp_generate_password();
			$userId = wp_create_user( $userLogin, $userPass, $userEmail );
				
			if ( is_wp_error( $userId ) )
				throw new Exception( $userId->get_error_message() );

			//$mailer->mailNewAffiliate( $userId, $userPass );
                        $mailer->mailNewApproveAffiliate($userId, $userPass);
			$sendEmail = false;
				
			$user = new WP_User( $userId );
			$user->add_cap( WPAM_PluginConfig::$AffiliateCap );
		}
			
		//Send user email indicating they're approved
		if ( $sendEmail )
			$mailer->mailAffiliate( $userEmail, sprintf( __( 'Affiliate Application for %s', 'wpam' ) , $blogname ), $message );
			
		$affiliate->approve();
		$affiliate->userId = $userId;
		$affiliate->bountyType = $bountyType;
		$affiliate->bountyAmount = $bountyAmount;
		if ( $update )
			$db->getAffiliateRepository()->update( $affiliate );
		else
			$db->getAffiliateRepository()->insert( $affiliate );
	}
        
        public function AutoapproveAffiliate( $affiliate, $bountyType = '', $bountyAmount = '') {
		$sendEmail = true;
		$mailer = new WPAM_Util_EmailHandler();
		$db = new WPAM_Data_DataAccess();

		//Create Affiliate account in WP (1.1.2 if they don't have one)
		//and send them an email telling them they're approved
			
		$userLogin = sanitize_user( $affiliate->email );
		$userEmail = apply_filters( 'user_registration_email', $affiliate->email );
			
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$message  = sprintf( __( 'New affiliate registration for %s: has been approved!', 'wpam' ) , $blogname ) . "\r\n\r\n";
			
		if ( username_exists( $userLogin ) ) {
			$user = new WP_User( get_userdatabylogin( $userLogin )->ID );
			if ( $user->has_cap( WPAM_PluginConfig::$AffiliateCap ) ) {
				throw new Exception( __( 'User already has an account and is already an affiliate', 'wpam' ) );
			} else {
				$user->add_cap( WPAM_PluginConfig::$AffiliateCap );
				$message .= __( 'Log into the site with your existing account and get started.', 'wpam' ) . "\r\n";
				$userId = $user->ID;
			}	
		} elseif ( email_exists( $userEmail ) ) {
			$user = new WP_User( get_user_by( 'email', $userEmail )->ID );
			if ( $db->getAffiliateRepository()->existsBy( array( 'userId' => $user->ID ) ) ) {
				throw new Exception( __( 'User already has an account and is already an affiliate', 'wpam' ) );
			} else {
				$user->add_cap( WPAM_PluginConfig::$AffiliateCap );
				$message .= __( 'Log into the site with your existing account and get started.', 'wpam' ) . "\r\n";
				$userId = $user->ID;
			}	
		} else {
			//user not found by email address as username and no account with that email address exists
			//create new user using email address as username
			$userPass = wp_generate_password();
			$userId = wp_create_user( $userLogin, $userPass, $userEmail );
				
			if ( is_wp_error( $userId ) )
				throw new Exception( $userId->get_error_message() );

			//$mailer->mailNewAffiliate( $userId, $userPass );
                        $mailer->mailNewApproveAffiliate($userId, $userPass);
			$sendEmail = false;
				
			$user = new WP_User( $userId );
			$user->add_cap( WPAM_PluginConfig::$AffiliateCap );
		}	
		$affiliate->activate();
		$affiliate->userId = $userId;
                if(isset($bountyType) && !empty($bountyType)){
                    $affiliate->bountyType = $bountyType;
                }
                else{
                    $affiliate->bountyType = get_option(WPAM_PluginConfig::$AffBountyType);
                }
                if(isset($bountyAmount) && !empty($bountyAmount)){
                    $affiliate->bountyAmount = $bountyAmount;
                }
                else{
                    $affiliate->bountyAmount = get_option(WPAM_PluginConfig::$AffBountyAmount); 
                }
		$id = $db->getAffiliateRepository()->insert( $affiliate );
                if ( $id == 0 ) {
                        if ( WPAM_DEBUG ){
                                echo '<pre>', var_export($model, true), '</pre>';
                        }
                        wp_die( __('Error submitting your details to the database. This is a bug, and your application was not submitted.', 'wpam' ) );
                }
                //Notify admin that affiliate has registered
                $admin_message  = sprintf( __( 'New affiliate registration on your site %s:', 'wpam' ), $blogname) . "\r\n\r\n";
                $admin_message .= sprintf( __( 'Name: %s %s', 'wpam' ), $affiliate->firstName, $affiliate->lastName) . "\r\n";
                $admin_message .= sprintf( __( 'Email: %s', 'wpam' ), $affiliate->email) . "\r\n";
                $admin_message .= sprintf( __( 'Company: %s', 'wpam' ), $affiliate->companyName) . "\r\n";
                $admin_message .= sprintf( __( 'Website: %s', 'wpam' ), $affiliate->websiteUrl) . "\r\n\r\n";
                $admin_message .= sprintf( __( 'View Application: %s', 'wpam' ),  admin_url('admin.php?page=wpam-affiliates&viewDetail='.$id)) . "\r\n";
                $mailer->mailAffiliate( get_option('admin_email'), __( 'New Affiliate Registration', 'wpam' ), $admin_message );
                
		//Send user email indicating they're approved
		if ( $sendEmail ){
			$mailer->mailAffiliate( $userEmail, sprintf( __( 'Affiliate Application for %s', 'wpam' ) , $blogname ), $message );
                }
	}
}