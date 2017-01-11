<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 28, 2010
 * Time: 11:50:51 PM
 */

class WPAM_Data_Models_TransactionModel implements WPAM_Data_Models_IDataModel
{
	public
		$transactionId,
		$dateModified,
		$dateCreated,
		$affiliateId,
		$referenceId,
		$amount,
		$description,
		$type,
		$status,
                $email,
		$balance; //not an actual column

	const STATUS_CONFIRMED = 'confirmed';
	const STATUS_PENDING = 'pending';

	const TYPE_CREDIT = 'credit';
	const TYPE_PAYOUT = 'payout';
	const TYPE_ADJUSTMENT = 'adjustment';

    public function fromRow($rowData)
    {
		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($rowData, $this);

		$this->dateCreated = strtotime($this->dateCreated);
		$this->dateModified = strtotime($this->dateModified);
	}

    public function toRow()
    {
        $row = new stdClass();

		$modelMapper = new WPAM_Data_Models_ModelMapper();
		$modelMapper->map($this, $row, false);

		$row->dateCreated = date('Y-m-d H:i:s', $row->dateCreated);
		$row->dateModified = date('Y-m-d H:i:s', $row->dateModified);

		//not an actual column
		unset( $row->balance );

		return $row;
    }
}
