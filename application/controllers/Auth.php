<?php

/**
 * @file       /application/controllers/Auth.php
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

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        //$this->load->helper(array('timestamp', 'session'));
        $this->load->library('session');
        $this->load->model(array('auth_model', 'settings_model'), TRUE);
    }

    public function login() {
        $return = array('status' => 'error', 'message' => 'Unable to process your response at this time.');
        $this->clear_session();
        $session_data = array("status" => "error", "message" => "invalid username or password");
        $data = array('username' => $this->input->post('username', TRUE), 'password' => $this->input->post('password', TRUE),);
        $result = $this->auth_model->verify_login($data);
        if ($result) {
            $return['status'] = 'success';
            $return['message'] = 'Welcome';
            $session_data = array(
                "ip_address" => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'valid' => TRUE,
                'settings' => array(),
            );
            $names = array('user_id' => 'int',
                'user_type_id' => 'int',
                'first_name' => 'string',
                'middle_initial' => 'string',
                'last_name' => 'string',
                'username' => 'string',
                'password_expire' => 'datetime',
                'last_logon' => 'datetime',
                'phone' => 'string',
                'email' => 'string',
                'image_id' => 'string',
                'created_on' => 'datetime',
                'created_by' => 'int',
                'updated_on' => 'datetime',
                'updated_by' => 'int');


            foreach ($names as $name => $type) {
                if (isset($result->$name)) {
                    if($type === 'int'){
                        $result->$name = intval($result->$name);
                    }
                    $session_data[$name] = $result->$name;
                }
            }
            $session_data['settings'] = $this->settings_model->get_settings($session_data['user_id']);
            $this->session->set_userdata($session_data);
            $session_data = $this->session->all_userdata(); //remove this
            $return['username'] = $session_data['username'];
            $return['user_id'] = $session_data['user_id'];
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function logout() {
        $this->session->sess_destroy();
        header('Content-Type: application/json');
        echo json_encode(array("status" => "success"));
        exit();
    }

    public function get_session() {
        $session = $this->session->all_userdata();
        header('Content-Type: application/json');
        echo json_encode($session);
        exit();
    }

    public function clear_session() {
        $array_items = array('user', 'settings', 'status', 'ip_address', 'user_agent');
        $this->session->unset_userdata($array_items);
    }

}
