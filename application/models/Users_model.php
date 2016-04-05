<?php

/**
 * @file       /application/models/Users_model.php
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
class Users_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }
    public function get_users() {
        $this->db->select('users.*');
        $result = $this->db->get_where('users', array('deleted_on' => null));
        if ($result) {
            return $result->result();
        }
        return FALSE;
    }
    public function email_available($inDATA) {
        if (isset($inDATA, $inDATA['email']) === TRUE) {
            $this->db->select('users.*');
            $this->db->where(array('users.email' => $inDATA['email']));
            $this->db->from('users');
            $result = $this->db->get();
            if ($result !== FALSE && $result->num_rows() > 0) {
                return $result->result();
            } else {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function add_user($inDATA) {
        if (isset($inDATA, $inDATA['first_name'], $inDATA['last_name'], $inDATA['email'], $inDATA['password'], $inDATA['password_expires_on']) !== FALSE) {
            $insertDATA = array(
                'first_name' => $inDATA['first_name'],
                'last_name' => $inDATA['last_name'],
                'email' => $inDATA['email'],
                'password' => md5($inDATA['password']),
                'password_expire' => $inDATA['password_expires_on'],
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => 2,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => 2
            );
            $result = $this->db->insert('users', $insertDATA);
            if ($result !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
