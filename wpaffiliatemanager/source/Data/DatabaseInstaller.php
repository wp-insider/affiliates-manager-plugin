<?php

/**
 * Handles datbase table install and upgrade stuff
 */
class WPAM_Data_DatabaseInstaller {

    const WPAM_DB_VERSION_NAME = 'wpam_db_version';

    private $db;

    public function __construct(wpdb $db) {
        $this->db = $db;
    }

    public function doDbInstall() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $affiliates_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_AFFILIATES;
        $creatives_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_CREATIVES;
        $tracking_tokens_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS;
        $events_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_EVENTS;
        $actions_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_ACTIONS;
        $transactions_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_TRANSACTIONS;
        $messages_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_MESSAGES;
        $tt_purchase_logs_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_TRACKING_TOKENS_PURCHASE_LOGS;
        $affiliates_fields_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_AFFILIATES_FIELDS;
        $paypal_logs_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_PAYPAL_LOGS;
        $impressions_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_IMPRESSIONS;

        $aff_tbl_sql = "CREATE TABLE " . $affiliates_table . " (
        `affiliateId` int(11) NOT NULL AUTO_INCREMENT,
        `userId` int(11) DEFAULT NULL,
        `firstName` varchar(50) NOT NULL,
        `lastName` varchar(50) NOT NULL,
        `email` varchar(255) NOT NULL,
        `addressLine1` varchar(255) NOT NULL,
        `addressLine2` varchar(255) NOT NULL,
        `addressCity` varchar(128) NOT NULL,
        `addressState` char(64) NOT NULL,
        `addressZipCode` char(32) NOT NULL,
        `addressCountry` char(128) NOT NULL,
        `status` enum('applied','declined','approved','active','inactive','confirmed','blocked') NOT NULL,
        `dateCreated` datetime NOT NULL,
        `companyName` varchar(50) NOT NULL DEFAULT '',
        `websiteUrl` varchar(255) NOT NULL DEFAULT '',
        `uniqueRefKey` char(128) NOT NULL DEFAULT '<none>',
        `nameOnCheck` varchar(255) DEFAULT NULL,
        `paypalEmail` varchar(255) DEFAULT NULL,
        `paymentMethod` enum('paypal','check') DEFAULT NULL,
        `bountyType` enum('fixed','percent') DEFAULT NULL,
        `bountyAmount` decimal(7,2) DEFAULT NULL,
        `phoneNumber` varchar(32) NOT NULL default '',
        `userData` TEXT NULL,
        PRIMARY KEY (`affiliateId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($aff_tbl_sql);

        $creatives_tbl_sql = "CREATE TABLE " . $creatives_table . " (
        `creativeId` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `type` enum('text','image') NOT NULL,
        `altText` varchar(255) NOT NULL,
        `imagePostId` int(11) DEFAULT NULL,
        `dateCreated` datetime NOT NULL,
        `status` enum('active','inactive','deleted') NOT NULL DEFAULT 'inactive',
        `name` varchar(250) NOT NULL,
        `linkText` varchar(255) DEFAULT NULL,
        `slug` VARCHAR(255) NOT NULL default '',
        PRIMARY KEY (`creativeId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($creatives_tbl_sql);

        $tracking_tokens_tbl_sql = "CREATE TABLE " . $tracking_tokens_table . " (
        `trackingTokenId` int(11) NOT NULL AUTO_INCREMENT,
        `dateCreated` datetime NOT NULL,
        `sourceAffiliateId` int(11) NOT NULL,
        `trackingKey` varchar(27) NOT NULL,
        `sourceCreativeId` int(11) DEFAULT NULL,
        `referer` text,
        `affiliateSubCode` varchar(30) DEFAULT NULL,
        PRIMARY KEY (`trackingTokenId`),
        UNIQUE KEY `trackingKey` (`trackingKey`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($tracking_tokens_tbl_sql);

        $events_tbl_sql = "CREATE TABLE " . $events_table . " (
        `eventId` int(11) NOT NULL AUTO_INCREMENT,
        `dateModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `dateCreated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        `trackingTokenId` int(11) NOT NULL,
        `actionId` int(11) NOT NULL,
        PRIMARY KEY (`eventId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($events_tbl_sql);

        $actions_tbl_sql = "CREATE TABLE " . $actions_table . " (
        `actionId` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(20) NOT NULL,
        `description` varchar(255) NOT NULL,
        PRIMARY KEY (`actionId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($actions_tbl_sql);

        $transactions_tbl_sql = "CREATE TABLE " . $transactions_table . " (
        `transactionId` int(11) NOT NULL AUTO_INCREMENT,
        `dateModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `dateCreated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        `affiliateId` int(11) NOT NULL,
        `amount` decimal(7,2) NOT NULL,
        `type` enum('credit','payout','adjustment') NOT NULL DEFAULT 'credit',
        `description` varchar(255) NOT NULL,
        `referenceId` varchar(255) DEFAULT NULL,
        `status` ENUM('pending','confirmed','failed') NOT NULL DEFAULT 'confirmed',
        PRIMARY KEY (`transactionId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($transactions_tbl_sql);

        $aff_fields_tbl_sql = "CREATE TABLE " . $affiliates_fields_table . " (
        `affiliateFieldId` int(11) NOT NULL AUTO_INCREMENT,
        `type` enum('base','custom') NOT NULL,
        `name` varchar(60) NOT NULL,
        `length` int(11) NOT NULL,
        `fieldType` varchar(45) NOT NULL,
        `required` tinyint(1) NOT NULL,
        `databaseField` varchar(255) NOT NULL,
        `enabled` tinyint(1) NOT NULL DEFAULT '0',
        `order` int(11) NOT NULL,
        PRIMARY KEY (`affiliateFieldId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($aff_fields_tbl_sql);

        $messages_tbl_sql = "CREATE TABLE " . $messages_table . " (
        `messageId` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(60) NOT NULL,
        `use` varchar(255) NOT NULL,
        `content` text NOT NULL,
        `type` enum('email','web') NOT NULL DEFAULT 'web',
        PRIMARY KEY (`messageId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($messages_tbl_sql);

        $pp_logs_tbl_sql = "CREATE TABLE " . $paypal_logs_table . " (
        `paypalLogId` int(11) NOT NULL AUTO_INCREMENT,
        `responseTimestamp` DATETIME NOT NULL,
        `dateOccurred` DATETIME NOT NULL,
        `correlationId` VARCHAR(45) NOT NULL,
        `ack` VARCHAR(45) NOT NULL,
        `version` VARCHAR(45) NOT NULL,
        `build` VARCHAR(45) NOT NULL,
        `errors` TEXT NOT NULL,
        `rawResponse` TEXT NOT NULL,
        `status` ENUM('pending','reconciled','failed') NOT NULL DEFAULT 'pending',
        `amount` DECIMAL(7,2) NOT NULL,
        `fee` DECIMAL(7,2) NOT NULL,
        `totalAmount` DECIMAL(7,2) NOT NULL,
        PRIMARY KEY (`paypalLogId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($pp_logs_tbl_sql);

        $tt_purchase_logs_tbl_sql = "CREATE TABLE " . $tt_purchase_logs_table . " (
        `trackingTokenPurchaseLogId` int(11) NOT NULL AUTO_INCREMENT,
        `trackingTokenId` int(11) NOT NULL,
        `purchaseLogId` int(11) NOT NULL,
        PRIMARY KEY (`trackingTokenPurchaseLogId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($tt_purchase_logs_tbl_sql);

        $impressions_tbl_sql = "CREATE TABLE " . $impressions_table . " (
        `impressionId` int(11) NOT NULL AUTO_INCREMENT,
        `dateCreated` datetime NOT NULL,
        `sourceAffiliateId` int(11) NOT NULL,
        `sourceCreativeId` int(11) DEFAULT NULL,
        `referer` text,
        `affiliateSubCode` varchar(30) DEFAULT NULL,
        PRIMARY KEY (`impressionId`)
        )ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        dbDelta($impressions_tbl_sql);

        update_option(self::WPAM_DB_VERSION_NAME, WPAM_DB_VERSION);
    }

    public function doFreshInstallDbDefaultData() {
        //Only inserts the default data if the respective tables are empty
        
        $affiliates_fields_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_AFFILIATES_FIELDS;        
        $messages_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_MESSAGES;
        $actions_table = $this->db->prefix . WPAM_Data_DataAccess::TABLE_ACTIONS;

        $results = $this->db->get_results("SELECT * FROM " . $affiliates_fields_table, OBJECT);
        if( is_null($results) || is_array($results) && empty($results) ){//No record in this table 
            
            $this->db->query("
            INSERT INTO `$affiliates_fields_table`
            VALUES
            (1,'base','First Name',50,'string',1,'firstName',1,0),
            (2,'base','Last Name',50,'string',1,'lastName',1,1),
            (3,'base','E-Mail Address',0,'email',1,'email',1,3),
            (4,'base','Address Line 1',255,'string',1,'addressLine1',1,4),
            (5,'base','Address Line 2',255,'string',0,'addressLine2',1,5),
            (6,'base','City',128,'string',1,'addressCity',1,6),
            (7,'base','State',0,'stateCode',1,'addressState',1,7),
            (8,'base','Zip Code',0,'zipCode',1,'addressZipCode',1,8),
            (9,'base','Country',0,'countryCode',1,'addressCountry',1,10),
            (10,'base','Company Name',50,'string',0,'companyName',1,11),
            (11,'base','Website URL',255,'string',0,'websiteUrl',1,12),
            (12,'base','Phone Number',0,'phoneNumber',1,'phoneNumber',1,2)
            ");
        }
        
        $results = $this->db->get_results("SELECT * FROM " . $actions_table);
        if( is_null($results) || is_array($results) && empty($results) ){//No record in this table 
            $this->db->query(
            "INSERT INTO `$actions_table`
            (name, description)
            VALUES
            ('visit', 'New visitor'),
            ('purchase', 'User confirmed purchase')"
            );
        }
        
        $results = $this->db->get_results("SELECT * FROM " . $messages_table);
        if( is_null($results) || is_array($results) && empty($results) ){//No record in this table 
            $this->db->query("
            INSERT INTO `$messages_table`
            VALUES
            (1,'affiliate_application_approved','Displayed to user at logon if affiliate STATUS = APPROVED','Congratulations, the administrator has <strong>approved</strong> your application.  You have one more step to complete before you can begin publishing for this store and generating revenue! The store owner has specified the terms of your agreement, which you will need to review and agree to.<br /><br/>','web'),
            (2,'affiliate_application_declined','Displayed to user at logon if affiliate STATUS = DECLINED','I\'m sorry, your application was declined.','web'),
            (3,'affiliate_application_pending','Displayed to user at logon if affiliate STATUS = PENDING','Your application is still being reviewed. Please check back later!','web'),
            (4,'affiliate_application_submitted','Displayed to user after successfully submitting the affiliate registration form','Your application has been submitted.  The store owner will be contacting you soon.<br />\r\n<br />\r\nThank you!<br />\r\n','web'),
            (5,'affiliate_application_submitted_email','Body of e-mail sent to the affiliate immediately after submitting their application.','Your application will be reviewed and you will be hearing from us soon!','email')
            ");

            $db = new WPAM_Data_DataAccess();
            $msgRepo = $db->getMessageRepository();
            $msg = new WPAM_Data_Models_MessageModel();
            $msg->content = "I'm sorry, your application was declined.";
            $msg->name = 'affiliate_application_declined_email';
            $msg->type = 'email';
            $msg->use = 'Body of e-mail sent to the affiliate immediately following their application being declined.';
            $msgRepo->insert($msg);

            $db = new WPAM_Data_DataAccess();
            $msgRepo = $db->getMessageRepository();
            $msg = new WPAM_Data_Models_MessageModel();
            $msg->content = "Thank you. Your registration is now complete. You can log into the affiliate area and begin promoting.";
            $msg->name = 'aff_app_submitted_auto_approved';
            $msg->type = 'web';
            $msg->use = 'Displayed to a newly registered affiliate if automatic affiliate approval option is enabled.';
            $msgRepo->insert($msg);
        }
        
        //Add the default message insert code below (it will only add it if it doesn't exist already) 
        $db = new WPAM_Data_DataAccess();
        $msgRepo = $db->getMessageRepository();
        $message = $msgRepo->loadBy(array('name' => 'affiliate_application_approved_email'));
        if($message === NULL){
            $msg = new WPAM_Data_Models_MessageModel();
            $msg->content = "Your affiliate account for {blogname} has been approved!. \n\nUsername: {affusername} \nPassword: {affpassword} \nLogin URL: {affloginurl} \n\nPlease log into your account to get referral code.";
            $msg->name = 'affiliate_application_approved_email';
            $msg->type = 'email';
            $msg->use = 'Body of e-mail sent to a newly registered affiliate immediately following their application being approved.';
            $msgRepo->insert($msg);
        }
        //Add other options below
        
        
        //add a new creative (default)
        $default_creative_id = get_option(WPAM_PluginConfig::$DefaultCreativeId);
        $db = new WPAM_Data_DataAccess();
        $creativesRepo = $db->getCreativesRepository();
        $create_new_creative = false;
        if(empty($default_creative_id))  //no creative ID saved in the config
        {
            $create_new_creative = true;
        }
        else
        {
            if(!$creativesRepo->existsBy(array('creativeId' => $default_creative_id, 'status' => 'active')))  //no active creative with this ID in the creative database (probably the user deleted it)
            {
                $create_new_creative = true;
            }
        }
        
        if($create_new_creative)
        {
            $model = new WPAM_Data_Models_CreativeModel();
            $model->dateCreated = time();
            $model->status = 'active';
            $model->type === 'text';
            $model->linkText = 'default affiliate link';
            $model->altText = '';
            $model->slug = site_url('/');
            $model->name = 'default creative';
            $id = $creativesRepo->insert($model);
            update_option(WPAM_PluginConfig::$DefaultCreativeId, $id); //Save the ID of the deafult creative
        }
    }

    public function doInstallPages(array $new_pages) {
        $args = array(
            'post_type' => 'page'
        );
        $pages = get_posts($args);

        $home_page_id = NULL;
        $reg_page_id = NULL;
        $login_page = NULL;
        
        foreach ($pages as $page) {
            //Lets check if the required pages are already in place
            if (strpos($page->post_content, WPAM_PluginConfig::$ShortCodeHome) !== false) {
                $home_page_id = $page->ID;
            }

            if (strpos($page->post_content, WPAM_PluginConfig::$ShortCodeRegister) !== false) {
                $reg_page_id = $page->ID;
            }
            
            if (strpos($page->post_content, WPAM_PluginConfig::$ShortCodeLogin) !== false) {
                $login_page = get_permalink($page->ID);
            }
        }

        if (!$home_page_id) {
            //Could not find a page with the affiliate area shortcode. Lets create this page
            $home_page_id = $new_pages[WPAM_Plugin::PAGE_NAME_HOME]->install();
        }
        update_option(WPAM_PluginConfig::$HomePageId, $home_page_id); //Save the ID of our affiliate area home page

        if (!$reg_page_id) {
            //Could not find a page with the affiliate registration shortcode. Lets create this page
            $reg_page_id = $new_pages[WPAM_Plugin::PAGE_NAME_REGISTER]->install();
        }
        update_option(WPAM_PluginConfig::$RegPageId, $reg_page_id); //Save the ID of the registration page.
        
        if (!$login_page) {
            //Could not find a page with the affiliate login shortcode. Lets create this page
            $login_page_id = $new_pages[WPAM_Plugin::PAGE_NAME_LOGIN]->install();
            $login_page = get_permalink($login_page_id);          
        }
        update_option(WPAM_PluginConfig::$AffLoginPageURL, $login_page); //Save the URL of the login page
        //Add code for any new page needed for this plugin.
    }

    public function performUpgrade() {
        //Do db upgrade stuff
    }

}
