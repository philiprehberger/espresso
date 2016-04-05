<?php

/**
 * @file       /application/controllers/Login.php
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
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('grocery_CRUD');
        $this->load->helper(array('url', 'session', 'timestamp', 'page'));
        $this->load->model(array('auth_model', 'settings_model'), TRUE);
    }
    public function index() {
        $pageDATA = Page::get_page_data('login');
        //$this->load->view('header', $pageDATA);
        $this->load->view('login', $pageDATA);
        //$this->load->view('footer', $pageDATA);
    }
}
