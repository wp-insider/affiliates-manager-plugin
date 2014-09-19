<?php

/**
 * @author John Hargrove
 * 
 * Date: 11/21/10
 * Time: 12:50 AM
 */
class WPAM_Validation_MultiPartPhoneNumberValidator implements WPAM_Validation_IValidator {

    function getError() {
        return __('must be a valid phone number', 'wpam');
    }

    function isValid($value) {
        if (!empty($value)) { //should at least contain *something*
            return true;
        }
        return false;
    }

}
