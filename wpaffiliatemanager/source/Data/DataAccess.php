<?php

/**
 * @author John Hargrove
 * 
 * Date: May 19, 2010
 * Time: 11:22:42 PM
 */
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/IDataModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/AffiliateModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/AffiliateFieldModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/TransactionModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/ModelMapper.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/MessageModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/GenericRepository.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/AffiliateRepository.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/CreativeRepository.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/WordPressRepository.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/EventRepository.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/TransactionRepository.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/TrackingTokenPurchaseLogModel.php";
require_once WPAM_BASE_DIRECTORY . "/source/Data/Models/PaypalLogModel.php";

class WPAM_Data_DataAccess {
    /**
     * table constants
     */

    const TABLE_AFFILIATES = "wpam_affiliates";
    const TABLE_CREATIVES = "wpam_creatives";
    const TABLE_TRACKING_TOKENS = "wpam_tracking_tokens";
    const TABLE_EVENTS = 'wpam_events';
    const TABLE_ACTIONS = 'wpam_actions';
    const TABLE_TRANSACTIONS = 'wpam_transactions';
    const TABLE_MESSAGES = 'wpam_messages';
    const TABLE_TRACKING_TOKENS_PURCHASE_LOGS = 'wpam_tracking_tokens_purchase_logs';
    const TABLE_AFFILIATES_FIELDS = "wpam_affiliates_fields";
    const TABLE_PAYPAL_LOGS = "wpam_paypal_logs";

    private $affiliateRepository;
    private $wordpressRepository;
    private $creativesRepository;
    private $trackingTokenRepository;
    private $eventRepository;
    private $transactionRepository;
    private $trackingTokenPurchaseLogsRepository;
    private $affiliateFieldRepository;
    private $messageRepository;
    private $paypalLogRepository;

    public function __construct() {
        // Hopefully this can be isolated to just this point
        // Hopefully.
        global $wpdb;

        $table_prefix = $wpdb->prefix;

        $this->wordpressRepository = new WPAM_Data_WordPressRepository($wpdb);
        $this->affiliateRepository = new WPAM_Data_AffiliateRepository($wpdb, $table_prefix . self::TABLE_AFFILIATES, "WPAM_Data_Models_AffiliateModel", "affiliateId");
        $this->creativesRepository = new WPAM_Data_CreativeRepository($wpdb, $table_prefix . self::TABLE_CREATIVES, "WPAM_Data_Models_CreativeModel", "creativeId");
        $this->trackingTokenRepository = new WPAM_Data_GenericRepository($wpdb, $table_prefix . self::TABLE_TRACKING_TOKENS, "WPAM_Data_Models_TrackingTokenModel", "trackingTokenId");
        $this->eventRepository = new WPAM_Data_EventRepository($wpdb, $table_prefix . self::TABLE_EVENTS, "WPAM_Data_Models_EventModel", "eventId");
        $this->transactionRepository = new WPAM_Data_TransactionRepository($wpdb, $table_prefix . self::TABLE_TRANSACTIONS, "WPAM_Data_Models_TransactionModel", "transactionId");
        $this->trackingTokenPurchaseLogsRepository = new WPAM_Data_GenericRepository($wpdb, $table_prefix . self::TABLE_TRACKING_TOKENS_PURCHASE_LOGS, "WPAM_Data_Models_TrackingTokenPurchaseLogModel", "trackingTokenPurchaseLogId");
        $this->affiliateFieldRepository = new WPAM_Data_GenericRepository($wpdb, $table_prefix . self::TABLE_AFFILIATES_FIELDS, "WPAM_Data_Models_AffiliateFieldModel", "affiliateFieldId");
        $this->messageRepository = new WPAM_Data_GenericRepository($wpdb, $table_prefix . self::TABLE_MESSAGES, "WPAM_Data_Models_MessageModel", "messageId");
        $this->paypalLogRepository = new WPAM_Data_GenericRepository($wpdb, $table_prefix . self::TABLE_PAYPAL_LOGS, "WPAM_Data_Models_PaypalLogModel", "paypalLogId");
    }

    /**
     * @return WPAM_Data_AffiliateRepository
     */
    public function getAffiliateRepository() {
        return $this->affiliateRepository;
    }

    /**
     * @return WPAM_Data_GenericRepository
     */
    public function getMessageRepository() {
        return $this->messageRepository;
    }

    /**
     * @return WPAM_Data_WordPressRepository
     */
    public function getWordPressRepository() {
        return $this->wordpressRepository;
    }

    /**
     * @return WPAM_Data_GenericRepository
     */
    public function getAffiliateFieldRepository() {
        return $this->affiliateFieldRepository;
    }

    /**
     * @return WPAM_Data_CreativeRepository
     */
    public function getCreativesRepository() {
        return $this->creativesRepository;
    }

    /**
     * @return WPAM_Data_GenericRepository
     */
    public function getTrackingTokenRepository() {
        return $this->trackingTokenRepository;
    }

    /**
     * @return WPAM_Data_EventRepository
     */
    public function getEventRepository() {
        return $this->eventRepository;
    }

    /**
     * @return WPAM_Data_TransactionRepository
     */
    public function getTransactionRepository() {
        return $this->transactionRepository;
    }

    /**
     * @return WPAM_Data_GenericRepository
     */
    public function getTrackingTokenPurchaseLogRepository() {
        return $this->trackingTokenPurchaseLogsRepository;
    }

    /**
     * @return WPAM_Data_GenericRepository
     */
    public function getPaypalLogRepository() {
        return $this->paypalLogRepository;
    }

}
