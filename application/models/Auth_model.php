<?php

/**
 * @file       /application/models/Auth_model.php
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

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function verify_login($in) {
        $data = $in;
        $data['deleted_on'] = null;
        $result = $this->db->get_where('users', $data);
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $this->db->where('user_id', $row->user_id);
                $this->db->update('users', array('last_logon' => date("Y-m-d H:i:s")));
                return $row;
            }
        }
        return false;
    }

}
