<?php

/**
 * @file       /application/controllers/Welcome.php
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

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('grocery_CRUD');
        $this->load->helper(array('url', 'timestamp', 'page'));
        $this->load->model(array('auth_model', 'settings_model'), TRUE);
    }

    public function index() {
        
        if ($this->input->get('alt') !== FALSE && $this->input->get('alt') === 1) {
            $pageDATA = Page::get_page_data('main');
            $this->load->view('header', $pageDATA);
            $this->load->view('main', $pageDATA);
            $this->load->view('footer', $pageDATA);
        } else {
            $pageDATA = Page::get_page_data('home');
        $this->load->view('home',$pageDATA);
        }
    }

}
