<?php

/**
 * @file       /application/helpers/page_helper.php
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
if (defined('BASEPATH') === FALSE) {
    exit('No direct script access allowed');
}

class Page {

    static public function get_baseURL() {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
    }

    static public function userID() {
        $CI = get_instance();
        $userDATA = $CI->session->all_userdata();
        if (isset($userDATA, $userDATA['user_id']) === TRUE) {
            return $userDATA['user_id'];
        }
        return FALSE;
    }

    static public function get_page_data($page) {
        $CI = get_instance();
        $pageDATA = array();
        $pageDATA['baseURL'] = self::get_baseURL();

        $types = self::get_types($page);
        if ($types === FALSE) {
            $types = array();
        }
        $pageDATA['scriptDATA'] = self::get_files($types);
        $pageDATA['userDATA'] = $CI->session->all_userdata();
        $pageDATA['sessionDATA'] = $CI->session;
        if($page === 'home'){
            $pageDATA['products'] = self::get_products();
        }else{
            $pageDATA['products'] = self::get_products_flat();
        }
        return $pageDATA;
    }

    static private function get_types($page) {
        //echo $page; exit();
        $pages = array(
            'home' => array('jquery', 'jquery_mobile', 'noty', 'ckeditor', 'md5', 'core', 'theme'),
            'main' => array('jquery', 'jquery_mobile', 'noty', 'ckeditor', 'md5', 'core', 'theme'),
            'login' => array('jquery', 'jquery_mobile', 'noty', 'ckeditor', 'md5', 'core', 'theme'),
            'my_manage' => array('theme'),
        );
        if (isset($pages[$page]) === TRUE) {
            return $pages[$page];
        }
        echo $page;
        exit();
        return FALSE;
    }

    static public function get_files($types) {
        if (isset($types) !== FALSE) {
            $data = array('jsDATA' => array(), 'cssDATA' => array());
            if (in_array('jquery', $types) !== FALSE) {
                $data['jsDATA'][] = '/public/plugins/jquery-2.2.0.min.js';
            }
            if (in_array('jquery_mobile', $types) !== FALSE) {
                $data['jsDATA'][] = '/public/plugins/jquery.mobile/jquery.mobile-1.4.5.min.js';
                $data['cssDATA'][] = '/public/plugins/jquery.mobile/jquery.mobile.structure-1.4.5.min.css';
            }
            if (in_array('noty', $types) !== FALSE) {
                $data['jsDATA'][] = '/public/plugins/noty/packaged/jquery.noty.packaged.min.js';
            }
            if (in_array('theme', $types) !== FALSE) {
                $data['cssDATA'][] = '/public/plugins/jquery.mobile/themes/espresso/espresso.min.css';
                $data['cssDATA'][] = '/public/plugins/jquery.mobile/themes/espresso/jquery.mobile.icons.min.css';
            }
            if (in_array('md5', $types) !== FALSE) {
                $data['jsDATA'][] = '/public/js/md5.min.js';
            }
            if (in_array('ckeditor', $types) !== FALSE) {
                $data['jsDATA'][] = '/public/plugins/ckeditor/ckeditor.js';
            }
            if (in_array('core', $types) !== FALSE) {

                $data['cssDATA'][] = '/public/css/core.css';
                $data['jsDATA'][] = '/public/js/footer.js';
                $data['jsDATA'][] = '/public/js/core.js';
            }
            return $data;
        }
        return FALSE;
    }

    static public function get_products() {
        $errors = array();
        $pageDATA = array();
        $CI = get_instance();
        $CI->db->select('`products`.`sales_price` AS `price`');
        $CI->db->select('`products`.`image_id` AS `image`');
        $CI->db->select('`products`.`description`');
        $CI->db->select('`categories`.`category_id`');
        $CI->db->select('`products`.`description`');
        $CI->db->select('`products`.`name`');
        $CI->db->select('`products`.`product_id`');
        $CI->db->select('`products`.`tax_group_id`');
        $CI->db->select('categories.name AS category');
        $CI->db->from('products');
        $CI->db->join('categories', 'categories.category_id=products.category_id', 'LEFT');
        $CI->db->where('products.deleted_on IS NULL');
        $CI->db->where('products.active = 1');
        $CI->db->order_by('categories.name,products.name');
        $results = $CI->db->get();
        if ($results !== FALSE && $results->num_rows() > 0) {
          foreach($results->result() AS $row){
              if(isset($pageDATA[$row->category]) === FALSE){
                  $pageDATA[$row->category]=array(
                      'category_id' => $row->category_id,
                      'items' => array()
                  );
              }
              $pageDATA[$row->category]['items'][]=$row;
          }
        }

        if (count($errors) === 0) {
            return $pageDATA;
        }
        return $errors;
    }
    static public function get_products_flat() {
        $errors = array();
        $pageDATA = array();
        $CI = get_instance();
        $CI->db->select('`products`.`sales_price` AS `price`');
        $CI->db->select('`products`.`image_id` AS `image`');
        $CI->db->select('`products`.`description`');
        $CI->db->select('`categories`.`category_id`');
        $CI->db->select('`products`.`description`');
        $CI->db->select('`products`.`name`');
        $CI->db->select('`products`.`product_id`');
        $CI->db->select('`products`.`tax_group_id`');
        $CI->db->select('categories.name AS category');
        $CI->db->from('products');
        $CI->db->join('categories', 'categories.category_id=products.category_id', 'LEFT');
        $CI->db->where('products.deleted_on IS NULL');
        $CI->db->where('products.active = 1');
        $CI->db->order_by('categories.name,products.name');
        $results = $CI->db->get();
        if ($results !== FALSE && $results->num_rows() > 0) {
            $pageDATA = $results->result();    
        }
        if (count($errors) === 0) {
            return $pageDATA;
        }
        return $errors;
    }

}
