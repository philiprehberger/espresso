<?php

/**
 * @file       /application/models/Settings_model.php
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
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function get_settings($user_id) {
        $data = array();
        $data['deleted_on'] = null;
        $data['user_id'] = $user_id;
        $result = $this->db->get_where('settings', $data);
        $return = array();
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $return[$row->name] = $row->value;
            }
            return $return;
        }
        return FALSE;
    }

    public function save_settings($in) {
        $user_id = $this->userID();
        $now = date("Y-m-d h:i:s");
        $check = array(
            'name' => $in['name'],
            'user_id' => $user_id
        );
        $result = $this->db->get_where("settings", $check);
        if ($result->num_rows() > 0) {
            $this->db->where(array('name' => $in['name'], 'user_id' => $user_id));
            $this->db->update('settings', array("value" => $in['value'],
                "updated_on" => $now, 'updated_by' => $user_id
            ));
        } else {
            $data = array(
                'name' => $in['name'],
                'value' => $in['value'],
                'created_by' => $user_id,
                'created_on' => $now,
                'updated_by' => $user_id,
                'updated_on' => $now,
                'user_id' => $user_id
            );
            $this->db->insert('settings', $data);
        }
    }

    private function userID() {
        $user = $this->session->userdata("user");
        return $user['user_id'];
    }

}
