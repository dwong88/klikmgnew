<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Handle functionality of "date" function php.
 *
 */
class DateTimeParser {
    //put your code here
    public $timestamp;

    public function init() {
        $this->timestamp=time();
    }

    public function getDateTimeNow() {
        return date('Y-m-d H:i:s', $this->timestamp);
    }
    
    public function getDateNow() {
    	return date('Y-m-d', $this->timestamp);
    }
}
?>
