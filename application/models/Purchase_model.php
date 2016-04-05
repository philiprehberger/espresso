<?php

/**
 * @file       /application/models/Purchase_model.php
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

class Purchase_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function purchase_item($inDATA) {
        if (isset($inDATA, $inDATA['user_id'], $inDATA['item_id'], $inDATA['cost'])) {
            $result_findItem = $this->db->get_where('lesson_plans.items', array('item_id' => $inDATA['item_id'], 'cost' => $inDATA['cost']));
            if ($result_findItem && $result_findItem->num_rows() === 1) {
                $this->db->insert('lesson_plans.purchases', array('item_id' => $inDATA['item_id'], 'item_cost' => $inDATA['cost'], 'purchase_price' => $inDATA['cost'], 'created_by' => $inDATA['user_id'], 'created_on' => date('Y-m-d H:i:s'), 'updated_by' => $inDATA['user_id'], 'updated_on' => date('Y-m-d H:i:s'), 'ip' => $_SERVER['REMOTE_ADDR']));
                return array('status' => 'success');
            } else {
                return array('status' => 'error', 'error' => 'unable to locate item with supplied cost', 'type' => 'cost', 'message' => 'Unable to locate item with supplied cost. Please attempt your purchase again.');
            }
        }
        return FALSE;
    }

}
