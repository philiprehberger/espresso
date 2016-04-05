<?php

/**
 * @file       /application/models/Files_model.php
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

class Files_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insert_file($inDATA) {
        if (isset($inDATA)) {
            $result = $this->db->insert('lesson_plans.files', $inDATA);
            if ($result) {

                $result_get = $this->db->get_where('lesson_plans.files', $inDATA);
                if ($result_get && $result_get->num_rows() > 0) {
                    foreach ($result_get->result() as $row) {
                        return $row->file_id;
                    }
                }
            }
        }
        return FALSE;
    }

    public function get_file_info($inDATA) {
        if (isset($inDATA, $inDATA['file_id'])) {
            $this->db->select('`files`.*');
            $this->db->select('`item_files`.`item_id`');

            $this->db->from('`lesson_plans`.`files`');
            $this->db->where(array('files.file_id' => $inDATA['file_id']));
            $this->db->join('`lesson_plans`.`item_files`', 'item_files.file_id=files.file_id');


            $result = $this->db->get();
            if ($result && $result->num_rows() > 0) {
                return $result->result();
                foreach ($result->result() as $row) {
                    return $row;
                }
            }
        }
        return FALSE;
    }

}
