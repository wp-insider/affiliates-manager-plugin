<?php
/**
 * @author John Hargrove
 * 
 * Date: May 19, 2010
 * Time: 11:41:41 PM
 */

class WPAM_Data_Models_AffiliateModel implements WPAM_Data_Models_IDataModel
{
    public $affiliateId;
    public $userId;

    public $firstName;
    public $lastName;
	public $email; 

    public $addressLine1;
    public $addressLine2;
    public $addressCity;
    public $addressState;
    public $addressZipCode;
	public $addressCountry;

	public $status;

	public $websiteUrl;
	public $companyName;

	public $dateCreated;
	public $uniqueRefKey;

	public $nameOnCheck;
	public $paypalEmail;

	public $paymentMethod;
	public $bountyType;
	
	public $bountyAmount;

	public $phoneNumber;
	public $userData;


    public function fromRow($rowData)
    {
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$binConverter = new WPAM_Util_BinConverter();

		$modelMapper->map($rowData, $this);

		$this->dateCreated = strtotime($this->dateCreated);
		$this->uniqueRefKey = $binConverter->stringToBin($rowData->uniqueRefKey);
		$this->userData = unserialize($rowData->userData);
	}

    public function toRow()
    {
        $row = new stdClass();

		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);
		$binConverter = new WPAM_Util_BinConverter();

		$row->dateCreated = date('Y-m-d H:i:s', $row->dateCreated);
		$row->uniqueRefKey = $binConverter->binToString($this->uniqueRefKey);
		$row->userData = serialize($this->userData);

		return $row;
    }

	public function isActive()
	{
		return $this->status == 'active';
	}

	public function isPending()
	{
		return $this->status == 'applied';
	}

	public function isBlocked()
	{
		return $this->status == 'blocked';
	}

	public function isDeclined()
	{
		return $this->status == 'declined';
	}

	public function isApproved()
	{
		return $this->status == 'approved';
	}

	public function isInactive()
	{
		return $this->status == 'inactive';
	}

	public function isConfirmed()
	{
		return $this->status == 'confirmed';
	}

	public function approve()
	{
		$this->status = 'approved';
	}

	public function activate()
	{
		$this->status = 'active';
	}
	
	public function deactivate()
	{
		$this->status = 'inactive';
	}

	public function confirm()
	{
		$this->status = 'confirmed';
	}
	public function decline()
	{
		$this->status = 'declined';
	}

	public function block()
	{
		$this->status = 'blocked';
	}

	public function setPaypalPaymentMethod($address)
	{
		$this->paymentMethod = 'paypal';
		$this->paypalEmail = $address;
	}

	public function setCheckPaymentMethod($recipient)
	{
		$this->paymentMethod = 'check';
		$this->nameOnCheck = $recipient;
	}
	
	public function formatPhoneNumber()
	{
		$phone = $this->phoneNumber;

		$phone = preg_replace("/[^0-9]/", "", $phone);

		if(strlen($phone) == 7)
			return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
		elseif(strlen($phone) == 10)
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
		else
			return $phone;
	}
	
	public function getBountyType() {
		if ( $this->bountyType == 'percent' )
			return __( 'Percent', 'wpam' );
		//else
		return __( 'Fixed', 'wpam' );
	}

	public function getPaymentMethod() {
		if ( $this->paymentMethod == 'paypal' )
			return __( 'PayPal', 'wpam' );
		//else
		return __( 'Check', 'wpam' );
	}
}
