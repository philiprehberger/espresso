<?php

/**
 * @file       /application/controllers/Upload.php
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

class Upload extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('upload');
        $this->load->model(array('auth_model', 'settings_model'), TRUE);
    }

    public function index() {
        error_reporting(E_ALL | E_STRICT);
        $upload_handler = new UploadHandler();
        //echo json_encode($upload_handler);
        $files = $upload_handler->response;
        $array = json_decode(json_encode($files), true);
        $timestamp = date('Y-m-d H:i:s');
        $user_id = 1;
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id']) === TRUE) {
            $user_id = $_SESSION['user']['user_id'];
        }
        $this->load->model('Files_model', 'Files_model', TRUE);

        foreach ($array['files'] as $key => $value) {
            $fileDATA = array(
                'size' => $value['size'],
                'type' => $value['type'],
                'name' => str_replace('/lessonplans/assets/uploads/files/', '', $value['url']),
                'created_on' => $timestamp,
                'updated_on' => $timestamp,
                'created_by' => $user_id,
                'updated_by' => $user_id
            );
            $file_id = $this->Files_model->insert_file($fileDATA);
            $array['files'][$key]['file_id'] = $file_id;
        }
        echo json_encode($array);
    }

    public function file() {
        error_reporting(E_ALL | E_STRICT);
        require('/var/www/html/m_lessonplans/application/controllers/Upload_handler.php');
        $upload_handler = new UploadHandler();
         //header('Content-Type: application/json');
        echo json_encode($upload_handler->response);
        exit();
        
    }

}
