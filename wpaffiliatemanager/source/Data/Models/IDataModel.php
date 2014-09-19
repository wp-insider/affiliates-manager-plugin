<?php
/**
 * @author John Hargrove
 * 
 * Date: May 19, 2010
 * Time: 11:43:06 PM
 */

interface WPAM_Data_Models_IDataModel {
    function toRow();
    function fromRow($rowData);
}
