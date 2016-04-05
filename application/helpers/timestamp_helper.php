
<?php

/**
 * @file       /application/helpers/timestamp_helper.php
 * 
 * @project    unitingteachers.com 
 * @category   Uniting Teachers
 * @package    m_lessonplans
 * @link       http://dcsuniverse.com
 * @link       http://unitingteachers.com
 * @author     Philip Rehberger <me@philiprehberger.com>
 * @copyright  ©2016 DCS Universe
 * @version    2.0.2.0
 * @since      1.0.0.0
 * @date       Oct 12, 2014
 * 
 */
class TimeStamp {
    static private $now = 0;
    static private $db_createArray = 0;
    static private $db_deleteArray = 0;
    static private $db_updateArray = 0;

    static private function makeNOW() {
        $_SESSION['user_id'] = 1;
        self::$now = date("Y-m-d H:i:s");
        self::$db_createArray = array("created_by"=>$_SESSION["user_id"],"created_on"=>self::$now,"updated_by"=>$_SESSION["user_id"],"updated_on"=>self::$now);
        self::$db_deleteArray = array("deleted_by"=>$_SESSION["user_id"],"deleted_on"=>self::$now,"updated_by"=>$_SESSION["user_id"],"updated_on"=>self::$now);
        self::$db_updateArray = array("updated_by"=>$_SESSION["user_id"],"updated_on"=>self::$now);
    }

    static public function dbCreateArr() {
        if ( self::$db_createArray === 0 ) { self::makeNOW(); }
        return self::$db_createArray;
    }

    static public function dbDeleteArr() {
        if ( self::$db_deleteArray === 0 ) { self::makeNOW(); }
        return self::$db_deleteArray;
    }
    
    static public function dbUpdateArr() {
        if ( self::$db_updateArray === 0 ) { self::makeNOW(); }
        return self::$db_updateArray;
    }
}