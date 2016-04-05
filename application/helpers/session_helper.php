<?php

/**
 * @file       /application/helpers/session_helper.php
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
if (defined('BASEPATH') === FALSE) {
    exit('No direct script access allowed');
}

class Session {

    static private $session = 0;

    static public function sessionData($key = null, $filter = null, $fillWithEmptyString = false) {
        if (!$key) {
            if (function_exists('filter_var_array')) {
                return $filter ? filter_var_array($_SESSION, $filter) : $_SESSION;
            } else {
                return $_SESSION;
            }
        }
        if (isset($_SESSION[$key])) {
            if (function_exists('filter_var')) {
                return $filter ? filter_var($_SESSION[$key], $filter) : $_SESSION[$key];
            } else {
                return $_SESSION[$key];
            }
        } else if ($fillWithEmptyString === true) {
            return '';
        }
        return null;
    }

    static private function makeSession() {
        $data = array();
        foreach ($_SESSION as $k => $i) {
            $data[$k] = self::sessionData($k);
        }
        self::$session = $data;
    }

    static public function getSession() {
        if (self::$session === 0) {
            self::makeSession();
        }
        return self::$session;
    }

    public function request($key = null, $filter = null, $fillWithEmptyString = false) {
        if (!$key) {
            if (function_exists('filter_var_array')) {
                return $filter ? filter_var_array($_REQUEST, $filter) : $_REQUEST;
            } else {
                return $_REQUEST;
            }
        }
        if (isset($_REQUEST[$key])) {
            if (function_exists('filter_var')) {
                return $filter ? filter_var($_REQUEST[$key], $filter) : $_REQUEST[$key];
            } else {
                return $_REQUEST[$key];
            }
        } else if ($fillWithEmptyString === true) {
            return '';
        }
        return null;
    }

}
