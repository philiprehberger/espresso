<?php

/**
 * @file       /application/models/Pagedata_model.php
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
 */ if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pagedata_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getTagCounts() {
        $this->db->select('`tags`.`name` AS tag');
        $this->db->select('`tags`.`tag_id` AS tag_id');
        $this->db->select('COUNT(*) AS `qty`', FALSE);
        $this->db->from('`lesson_plans`.`items`');
        $this->db->join('`lesson_plans`.`item_tags`', 'item_tags.item_id=items.item_id', 'LEFT');
        $this->db->join('`lesson_plans`.`tags`', 'item_tags.tag_id=tags.tag_id', 'LEFT');
        $this->db->where('items.deleted_on IS NULL');
        $this->db->group_by('`tags`.`name`');
        $this->db->order_by('COUNT(`tags`.`name`) ', 'DESC');
        $this->db->limit(5);
        $result = $this->db->get();
        if ($result && $result->num_rows() > 0) {
            return $result->result();
        }
        return FALSE;
    }

    public function get_recentUploads() {
        $this->db->select('`items`.*');
        $this->db->from('`lesson_plans`.`items`');
        $this->db->join('`lesson_plans`.`item_tags`', 'item_tags.item_id=items.item_id', 'LEFT');
        $this->db->join('`lesson_plans`.`tags`', 'item_tags.tag_id=tags.tag_id', 'LEFT');
        $this->db->where('items.deleted_on IS NULL');
        $this->db->order_by('`items`.`created_on`', 'DESC');
        $this->db->limit(5);
        $result = $this->db->get();
        if ($result && $result->num_rows() > 0) {
            return $result->result();
        }
        return FALSE;
    }

    public function getCategoryCounts() {
        $this->db->select('`categories`.`name` AS category');
        $this->db->select('`categories`.`category_id` AS category_id');
        $this->db->select('COUNT(DISTINCT items.item_id) AS `qty`', FALSE);
        $this->db->from('`lesson_plans`.`items`');
        $this->db->join('`lesson_plans`.`categories`', 'categories.category_id=items.category_id', 'LEFT');
        $this->db->where('items.deleted_on IS NULL');
        $this->db->group_by('`categories`.`name`');
        $this->db->order_by('COUNT(`categories`.`name`) ', 'DESC');
        $this->db->limit(5);
        $result = $this->db->get();
        //echo $this->db->last_query();exit();
        if ($result && $result->num_rows() > 0) {
            return $result->result();
        }
        return FALSE;
    }

    public function getTags() {
        $this->db->select('`tags`.`name` AS tag');
        $this->db->select('`tags`.`tag_id` AS tag_id');
        $this->db->from('`lesson_plans`.`tags`');
        $this->db->where('tags.deleted_on IS NULL');
        $this->db->order_by('`tags`.`name`');
        $result = $this->db->get();
        if ($result && $result->num_rows() > 0) {
            $return = array();
            foreach ($result->result() AS $row) {
                $return[$row->tag_id] = $row;
            }
            return $return;
        }
        return FALSE;
    }

    public function getCategories() {
        $this->db->select('`categories`.`name` AS category');
        $this->db->select('`categories`.`category_id` AS category_id');
        $this->db->from('`lesson_plans`.`categories`');
        $this->db->where('categories.deleted_on IS NULL');
        $this->db->order_by('`categories`.`name`');
        $result = $this->db->get();
        if ($result && $result->num_rows() > 0) {
            $return = array();
            foreach ($result->result() AS $row) {
                $return[$row->category_id] = $row;
            }
            return $return;
        }
        return FALSE;
    }

}
