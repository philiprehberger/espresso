<?php

/**
 * @file       /application/models/Logs_model.php
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

class Logs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insert_log($inDATA) {
        if (isset($inDATA)) {
            $result = $this->db->insert('lesson_plans.logs', $inDATA);
            if ($result) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
