<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Encryption function.
 */
class Encryption {
    //put your code here
    public static function encrypt($data)
    {
        return sha1(strrev(md5('53fdsdf3').sha1($data).sha1(strrev($data)).strrev(md5('t3e658sdf'))));
    }
}
?>
