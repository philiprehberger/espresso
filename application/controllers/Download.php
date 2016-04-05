<?php

/**
 * @file       /application/controllers/Download.php
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

class Download extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Files_model', 'Files_model', TRUE);
        $this->load->library('session');
    }

    public function index() {
        $data = array();
        if ($this->input->get('file_id') !== FALSE && $this->input->get('file_id') !== NULL) {
            $data['file_id'] = $this->input->get('file_id');
            $result = $this->Files_model->get_file_info($data);
            if ($result) {
                foreach ($result AS $row) {
                    $filename = $row->name;
                    $item_id = $row->item_id;
                    $this->load->model('Logs_model', 'Logs_model', TRUE);
                    $insertDATA = array(
                        'name' => 'Item: Download',
                        'log_type_id' => 2,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => 1,
                        'updated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => 1,
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'item_id' => $item_id
                    );
                    if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id']) === TRUE) {
                        $insertDATA['created_by'] = $_SESSION['user']['user_id'];
                        $insertDATA['updated_by'] = $_SESSION['user']['user_id'];
                    }
                    $result_log = $this->Logs_model->insert_log($insertDATA);
                }
            }
        } else if ($this->input->get('filename') !== FALSE && $this->input->get('filename') !== NULL) {
            $filename = $this->input->get('filename');
        }
        echo $filename;
        if (isset($filename)) {
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $filename);
            readfile('/var/www/html/lessonplans/assets/uploads/files/' . $filename);
        }
    }

}
