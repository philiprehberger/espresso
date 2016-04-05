<?php

/**
 * @file       /application/helpers/script_helper.php
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

class Script {

    static public function get_baseURL() {
        return 'http://dcsuniverse.com/m_lessonplans';
    }

    static public function get_files($types) {
        if (isset($types) !== FALSE) {
            $data = array('js' => array(), 'css' => array());
            if (in_array('default', $types) !== FALSE) {
                $data['js'][] = '/public/plugins/jquery-2.2.0.min.js';
            }
            if (in_array('desktop', $types) !== FALSE) {
                
            }
            if (in_array('mobile', $types) !== FALSE) {
                $data['js'][] = '/public/plugins/jquery.mobile/jquery.mobile-1.4.5.min.js';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.theme-1.4.5.min.css';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.structure-1.4.5.min.css';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.inline-svg-1.4.5.min.css';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.inline-png-1.4.5.min.css';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.icons-1.4.5.min.css';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.external-png-1.4.5.min.css';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile-1.4.5.min.css';
            }
            if (in_array('UNUSED', $types) !== FALSE) {
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.custom.structure.min.css';
                $data['css'][] = '/public/plugins/jquery.mobile/jquery.mobile.custom.theme.min.css';
                $data['js'][] = '/public/plugins/jquery.mobile/jquery.mobile.custom.min.js';
            }
            if (in_array('search', $types) !== FALSE) {
                
            }
            return $data;
        }
        return FALSE;
    }

}
