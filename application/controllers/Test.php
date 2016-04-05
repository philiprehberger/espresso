<?php

/**
 * @file       /application/controllers/Test.php
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

class Test extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('grocery_CRUD');
        $this->load->helper(array('url', 'session', 'script', 'timestamp', 'page'));
        $this->load->model(array('auth_model', 'settings_model'), TRUE);
    }
   
    public function index() {
        $pageDATA = Page::get_page_data('home');
        //$this->load->view('header', $pageDATA);
        $this->load->view('home',$pageDATA);
    }
    public function upload() {
        $pageDATA = Page::get_page_data('default');
        $this->load->view('header',$pageDATA);
        $this->load->view('test',$pageDATA);
       // $this->load->view('footer',$pageDATA);
    }

    public function post_plan() {
        $this->post_upload();
    }
    public function post_upload() {
        header('Content-Type: application/json');
        //$this->load->helper('upload');

        echo json_encode($_POST);
        
        exit();
    }
    public function now(){
        //header('Content-Type: application/json');
        
        echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];       
        exit();
    }

}
