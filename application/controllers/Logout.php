<?php

/**
 * @file       /application/controllers/Logout.php
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
class Logout extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(array('url', 'session', 'timestamp', 'page'));
    }
    public function index() {
        session_destroy();
        session_unset(); 
        header('Location: /');
        exit();  
    }
}
