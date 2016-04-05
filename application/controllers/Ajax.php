<?php

/**
 * @file       /application/controllers/Ajax.php
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

class ajax extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
//$this->load->helper(array('timestamp', 'session'));
        $this->load->helper(array('page'));
        $this->load->library('session');
        $this->load->model(array('auth_model', 'settings_model', 'purchase_model'), TRUE);
    }

    public function index() {
        echo "no access";
    }

    public function get_pageDATA() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time.');
        $errors = array();
        $pageDATA = array();
        $this->load->model('Pagedata_model', 'Pagedata_model', TRUE);
        /**
         * Top Tags
         */
        $result_tagCounts = $this->Pagedata_model->getTagCounts();
        if ($result_tagCounts !== FALSE) {
            $pageDATA['tagCounts'] = $result_tagCounts;
        } else {
            $errors[] = array('type' => 'mysql', 'function' => 'getTagCounts', 'error' => $this->db->error(), 'last_query' => $this->db->last_query());
        }
        /**
         * Top Categories
         */
        $result_categoryCounts = $this->Pagedata_model->getCategoryCounts();
        if ($result_categoryCounts !== FALSE) {
            $pageDATA['categoryCounts'] = $result_categoryCounts;
        } else {
            $errors[] = array('type' => 'mysql', 'function' => 'getTagCounts', 'error' => $this->db->error(), 'last_query' => $this->db->last_query());
        }
        /**
         * Recent Uploads
         */
        $result_recentUploads = $this->Pagedata_model->get_recentUploads();
        if ($result_recentUploads !== FALSE) {
            $pageDATA['recentUploads'] = $result_recentUploads;
        } else {
            $errors[] = array('type' => 'mysql', 'function' => 'getTagCounts', 'error' => $this->db->error(), 'last_query' => $this->db->last_query());
        }
        /**
         * Tags
         */
        $result_tags = $this->Pagedata_model->getTags();
        if ($result_tags !== FALSE) {
            $pageDATA['tags'] = $result_tags;
        } else {
            $errors[] = array('type' => 'mysql', 'function' => 'getTagCounts', 'error' => $this->db->error(), 'last_query' => $this->db->last_query());
        }
        /**
         * Categories
         */
        $result_categories = $this->Pagedata_model->getCategories();
        if ($result_categories !== FALSE) {
            $pageDATA['categories'] = $result_categories;
        } else {
            $errors[] = array('type' => 'mysql', 'function' => 'getTagCounts', 'error' => $this->db->error(), 'last_query' => $this->db->last_query());
        }
        if (COUNT($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'Data received.';
            $return['pageDATA'] = $pageDATA;
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function search() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time.');
        $errors = array();
        $options = array('search', 'category_id', 'tag_id', 'item_id');
        $data = array();
        foreach ($options as $option) {
            if ($this->input->post($option) !== FALSE && $this->input->post($option) !== NULL) {
                $data[$option] = $this->input->post($option);
            }
        }
        if (count($data) > 0) {
            $this->load->model('Logs_model', 'Logs_model', TRUE);
            $insertDATA = $data;
            $insertDATA['name'] = 'Search';
            $insertDATA['log_type_id'] = 3;
            $insertDATA['created_on'] = date('Y-m-d H:i:s');
            $insertDATA['created_by'] = 1;
            $insertDATA['updated_on'] = date('Y-m-d H:i:s');
            $insertDATA['updated_by'] = 1;
            $insertDATA['ip'] = $_SERVER['REMOTE_ADDR'];
            $insertDATA['item_id'] = NULL;
            $insertDATA['description'] = NULL;
            if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id'])) {
                $insertDATA['created_by'] = $_SESSION['user']['user_id'];
                $insertDATA['updated_by'] = $_SESSION['user']['user_id'];
            }
            $result_log = $this->Logs_model->insert_log($insertDATA);
            $this->load->model('Items_model', 'Items_model', TRUE);
            $result_search = $this->Items_model->_search($data);
//echo $this->db->last_query();exit();
            if ($result_search !== FALSE) {
                $return['result'] = $result_search;
            } else {
                $return['result'] = array();
            }
        } else {
            $errors[] = array('type' => 'mysql', 'function' => 'getTagCounts', 'error' => $this->db->error(), 'last_query' => $this->db->last_query());
        }
        if (COUNT($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'Data received.';
            $return['qty'] = COUNT($return['result']);
        } else {
            $return['errors'] = $errors;
        }


        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function get_item_details() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time.');
        $errors = array();
        $data = array();
        if ($this->input->post('item_id') !== FALSE) {
            $data['item_id'] = $this->input->post('item_id');
        }
        $data['user_id'] = 0;
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id'])) {
            $data['user_id'] = $_SESSION['user']['user_id'];
        }
        if (count($data) > 0) {
            $this->load->model('Items_model', 'Items_model', TRUE);
            $result_item_details = $this->Items_model->item_details($data);
            if ($result_item_details !== FALSE) {
                $return['result'] = $result_item_details;
            }
        }
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id'])) {
            if ($return['result']['created_by'] === $_SESSION['user']['user_id']) {
                $return['editable'] = TRUE;
            }
        }
        if (COUNT($errors) === 0) {
            $this->load->model('Logs_model', 'Logs_model', TRUE);
            $insertDATA = array(
                'name' => 'Item: View',
                'log_type_id' => 1,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => 1,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'item_id' => $data['item_id']
            );
            if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id'])) {
                $insertDATA['created_by'] = $_SESSION['user']['user_id'];
                $insertDATA['updated_by'] = $_SESSION['user']['user_id'];
            }
            $result_log = $this->Logs_model->insert_log($insertDATA);
            $return['status'] = 'success';
            $return['message'] = 'Data received.';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function post_item_details() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time.');
        $errors = array();
        $options = array(
            'item_id' => array('type' => 'int', 'required' => FALSE),
            'type_id' => array('type' => 'int', 'required' => TRUE),
            'category_id' => array('type' => 'int', 'required' => TRUE),
            'title' => array('type' => 'string', 'required' => TRUE),
            'subtitle' => array('type' => 'string', 'required' => TRUE),
            'name' => array('type' => 'string', 'required' => TRUE),
            'description' => array('type' => 'string', 'required' => TRUE),
            'description_long' => array('type' => 'string', 'required' => TRUE),
            'file_id' => array('type' => 'int', 'required' => FALSE),
            'tags' => array('type' => 'array', 'required' => TRUE),
            'files' => array('type' => 'array', 'required' => TRUE),
        );
        $data = array();
        foreach ($options AS $i => $v) {
            if ($this->input->post($i) !== FALSE && $this->input->post($i) !== NULL) {
                $data[$i] = $this->input->post($i);
            }
        }
        $return['inDATA'] = $data;
        if (COUNT($errors) === 0) {
            $this->load->model('Items_model', 'Items_model', TRUE);
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = 1;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $data['updated_by'] = 1;
            if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id'])) {
                $data['created_by'] = $_SESSION['user']['user_id'];
                $data['updated_by'] = $_SESSION['user']['user_id'];
            }
            $result_save_item = $this->Items_model->save_item($data);
            $return['result'] = $result_save_item;
        }
        /*
          if (COUNT($errors) === 0) {
          $this->load->model('Logs_model', 'Logs_model', TRUE);
          $insertDATA=array(
          'name'=>'Item: Save',
          'log_type_id'=>1,
          'created_on'=>date('Y-m-d H:i:s'),
          'created_by'=>1,
          'updated_on'=>date('Y-m-d H:i:s'),
          'updated_by'=>1,
          'ip'=>$_SERVER['REMOTE_ADDR'],
          'item_id'=>$data['item_id']
          );
          if (isset($_SESSION, $_SESSION['user'],$_SESSION['user']['user_id'])) {
          $insertDATA['created_by'] = $_SESSION['user']['user_id'];
          $insertDATA['updated_by'] = $_SESSION['user']['user_id'];
          }
          $result_log = $this->Logs_model->insert_log($insertDATA);
          } else {
          }
         * 
         */
        if (COUNT($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'Item saved.';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function login() {
        $return = array('status' => 'error', 'message' => 'Unable to process your response at this time.');
        $data = array();
        if ($this->input->post('username') !== FALSE && $this->input->post('username') !== NULL) {
            $data['username'] = $this->input->post('username');
        } else {
            
        }
        if ($this->input->post('password') !== FALSE && $this->input->post('password') !== NULL) {
            $data['password'] = $this->input->post('password');
        } else {
            
        }
//$this->db->select('`users`.`user_id` AS `user_id`');
        $this->db->select('`users`.*');
        $this->db->from('`lessonplans`.`users`');
        $this->db->where('`users`.`username` = "' . $data['username'] . '"');
        $this->db->where('`users`.`password` = "' . $data['password'] . '"');
        $result = $this->db->get();
        if ($result && $result->num_rows() > 0) {
            $_SESSION = $result->result();
            $return['status'] = 'success';
            $return['message'] = 'Welcome';
            $return['session'] = $_SESSION;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function purchase() {
        $return = array('status' => 'error', 'message' => 'Unable to process your response at this time.');
        $errors = array();
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id'])) {
            if ($this->input->post('item_id') !== FALSE && $this->input->post('item_id') !== NULL) {
                if ($this->input->post('cost') !== FALSE && $this->input->post('cost') !== NULL) {
                    $data = array(
                        'item_id' => intval($this->input->post('item_id')),
                        'cost' => $this->input->post('cost'),
                        'user_id' => intval($_SESSION['user']['user_id'])
                    );
                    $result_purchase = $this->purchase_model->purchase_item($data);
                    if ($result_purchase !== FALSE && isset($result_purchase['status']) && $result_purchase['status'] === 'success') {
                        
                    } else if (isset($result_purchase, $result_purchase['type'], $result_purchase['error'], $result_purchase['message'])) {
                        $errors[] = array('type' => $result_purchase['type'], 'error' => $result_purchase['error'], 'message' => $result_purchase['message']);
                    } else {
                        $errors[] = array('type' => 'db', 'error' => 'db error occured while completing purchase', 'message' => 'You have not incurred any charges.  Please attempt to complete your purchase again.');
                    }
                    $return['inDATA'] = $data;
                } else {
                    $errors[] = array('type' => 'parameter', 'error' => 'item_id is missing or invalid.', 'message' => 'You have not incurred any charges.  Please attempt to complete your purchase again.');
                }
            } else {
                $errors[] = array('type' => 'parameter', 'error' => 'item_id is missing or invalid.', 'message' => 'You have not incurred any charges.  Please attempt to complete your purchase again.');
            }
        } else {
            $errors[] = array('type' => 'session', 'error' => 'Session is not valid.', 'message' => 'Please log in to complete your purchase.');
        }
        if (count($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'Item purchased successfully';
        } else {
            $return['errors'] = $errors;
            $return['message'] = COUNT($errors) . ' error';
            if (count($errors) > 1) {
                $return['message'].='s';
            }
            $return['message'].=' occurred while processing your purchase.';
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function post_signup() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $inDATA = array();
        $opt = array(
            'first_name' => array('required' => TRUE, 'min' => 2),
            'last_name' => array('required' => TRUE, 'min' => 2),
            'email' => array('required' => TRUE, 'min' => 4),
            'years' => array('required' => FALSE, 'min' => FALSE)
        );
        foreach ($opt as $key => $settings) {
            if ($this->input->post($key) !== FALSE && $this->input->post($key) !== '') {
                $inDATA[$key] = $this->input->post($key);
            } else if ($settings['required'] === TRUE) {
                $errors[] = array('error' => 'Required parameter missing or invalid', 'parameter' => $key);
            }
        }
        $this->load->model('users_model', 'users_model', FALSE);
        if (count($errors) === 0) {
            $data = array(
                'first_name' => $inDATA['first_name'],
                'last_name' => $inDATA['last_name'],
                'email' => $inDATA['email'],
                'password' => $this->generatePassword(16),
                'password_expires_on' => date('Y-m-d 00:00:00')
            );
            $result_email = $this->users_model->email_available($data);
            if ($result_email !== TRUE) {
                $errors[] = array('error' => 'email already attached to an account', 'email' => $inDATA['email']);
            }
        }
        if (count($errors) === 0) {
            $result = $this->users_model->add_user($data);
            if ($result === FALSE) {
                $errors[] = array('error' => 'DB insert failed', 'last_query' => $this->db->last_query());
            }
        }
        if (count($errors) === 0) {
            $to = $inDATA['email'];
            $subject = 'Temporary Password';
            $message = $data['password'];
            if (mail($to, $subject, $message) === FALSE) {
                $errors[] = array('error' => 'mail send failed', 'email' => $inDATA['email']);
            }
        }
        if (count($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'Account created temporary password sent to ' . $inDATA['email'];
        } else {
            $return['inDATA'] = $inDATA;
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    private function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return $result;
    }

    public function is_email_available() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        if ($this->input->post('email') !== FALSE && $this->input->post('email') !== '') {
            $data = array('email' => $this->input->post('email'));
            $this->load->model('users_model', 'users_model', FALSE);
            $result_email = $this->users_model->email_available($data);
            if ($result_email !== FALSE) {

                if ($result_email === TRUE) {
                    $return['status'] = 'success';
                    $return['message'] = 'Email available';
                } else {
                    $return['message'] = 'This email is already attached to another account.';
                }
                $return['result'] = $result_email;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function post_rating() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $data = array();
        if ($this->input->post('rating') !== FALSE && $this->input->post('rating') !== '' && $this->input->post('rating') !== NULL) {
            $rating = $data['rating'] = $this->input->post('rating');
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'rating');
        }
        if ($this->input->post('item_id') !== FALSE && $this->input->post('item_id') !== '' && $this->input->post('item_id') !== NULL) {
            $item_id = $data['item_id'] = $this->input->post('item_id');
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'item_id');
        }
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id']) === TRUE) {
            $user_id = $_SESSION['user']['user_id'];
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'user_id');
        }
        if (COUNT($errors) === 0 && isset($user_id, $item_id, $rating) === TRUE) {
            $this->db->where(array('item_id' => $data['item_id'], 'created_by' => $user_id));
            $this->db->update('ratings', array('deleted_by' => $user_id, 'deleted_on' => date('Y-m-d H:i:s')));
            $this->db->insert('ratings', array('created_by' => $user_id, 'created_on' => date('Y-m-d H:i:s'), 'updated_by' => $user_id, 'updated_on' => date('Y-m-d H:i:s'), 'rating' => $rating, 'item_id' => $item_id));
            $this->load->model('Logs_model', 'Logs_model', TRUE);
            $insertDATA = array(
                'name' => 'Save: Rating',
                'log_type_id' => 4,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $user_id,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'item_id' => $item_id
            );
            $result_log = $this->Logs_model->insert_log($insertDATA);
            $return['status'] = 'success';
            $return['message'] = 'Rating saved.';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function post_settings() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $data = array();
        $opts = array('first_name' => FALSE, 'last_name' => FALSE, 'email' => FALSE, 'years' => FALSE);
        foreach ($opts AS $opt => $required) {
            if ($this->input->post($opt) !== FALSE && $this->input->post($opt) !== '' && $this->input->post($opt) !== NULL) {
                $data[$opt] = $this->input->post($opt);
            } else if ($required === TRUE) {
                $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => $opt);
            }
        }
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id']) === TRUE) {
            $user_id = $_SESSION['user']['user_id'];
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'user_id');
        }
        if (COUNT($errors) === 0 && count($data) > 0) {
            $data['updated_by'] = $user_id;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $this->db->where(array('user_id' => $user_id));
            $result = $this->db->update('users', $data);
            if ($result !== FALSE) {
//TODO if true update session
//$_SESSION['years']
                foreach ($data AS $k => $v) {
                    $_SESSION['user'][$k] = $v;
                }
            }
            $this->load->model('Logs_model', 'Logs_model', TRUE);
            $insertDATA = array(
                'name' => 'Save: Settings',
                'description' => json_encode($data),
                'log_type_id' => 7,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $user_id,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'item_id' => NULL
            );
            $result_log = $this->Logs_model->insert_log($insertDATA);
            $return['status'] = 'success';
            $return['message'] = 'Ratting saved.';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function post_review() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $data = array();
        if ($this->input->post('review') !== FALSE && $this->input->post('review') !== '' && $this->input->post('review') !== NULL) {
            $review = $data['review'] = $this->input->post('review');
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'review');
        }
        if ($this->input->post('item_id') !== FALSE && $this->input->post('item_id') !== '' && $this->input->post('item_id') !== NULL) {
            $item_id = $data['item_id'] = $this->input->post('item_id');
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'item_id');
        }
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id']) === TRUE) {
            $user_id = $_SESSION['user']['user_id'];
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'user_id');
        }
        if (COUNT($errors) === 0 && isset($user_id, $item_id, $review) === TRUE) {
            $reviewDATA = array(
                'created_by' => $user_id,
                'created_on' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id,
                'updated_on' => date('Y-m-d H:i:s'),
                'text' => $review,
                'item_id' => $item_id
            );

            $this->db->insert('reviews', $reviewDATA);
            $this->load->model('Logs_model', 'Logs_model', TRUE);
            $insertDATA = array(
                'name' => 'Save: Review',
                'log_type_id' => 5,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $user_id,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'item_id' => $item_id
            );
            $result_log = $this->Logs_model->insert_log($insertDATA);
            $return['status'] = 'success';
            $return['message'] = 'Review saved.';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function post_plan() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $insertDATA = array();
        $opts = array(
            'title' => array(
                'required' => TRUE,
                'type' => 'string'
            ),
            'subtitle' => array(
                'required' => FALSE,
                'type' => 'string'
            ),
            'category_id' => array(
                'required' => TRUE,
                'type' => 'string'
            ),
            /* 'tags' => array(
              'required' => TRUE,
              'type' => 'string'
              ), */
            'description' => array(
                'required' => TRUE,
                'type' => 'string'
            ),
        );
        foreach ($opts AS $key => $value) {
            if ($this->input->post($key) !== FALSE && $this->input->post($key) !== '' && $this->input->post($key) !== NULL) {
                $insertDATA[$key] = $this->input->post($key);
            } else if (is_bool($value['required']) === TRUE && $value['required'] === TRUE) {
                $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => $key);
            }
        }
        $files = array();
        if ($this->input->post('files') !== FALSE && $this->input->post('files') !== '' && $this->input->post('files') !== NULL) {
            foreach ($this->input->post('files') as $fileDATA) {
                $files[] = $fileDATA;
            }
        }
        if (count($files) === 0) {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'files');
        }
        $tags = array();
        if ($this->input->post('tags') !== FALSE && $this->input->post('tags') !== '' && $this->input->post('tags') !== NULL) {
            foreach ($this->input->post('tags') as $tagDATA) {
                $tags[] = $tagDATA;
            }
        }
        $return['tags'] = $tags;
        if (count($tags) === 0) {

            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'tags');
        }


        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id']) === TRUE) {
            $user_id = $_SESSION['user']['user_id'];
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'user_id');
        }
        if (COUNT($errors) === 0) {
            $insertDATA['created_by'] = $user_id;
            $insertDATA['created_on'] = date('Y-m-d H:i:s');
            $insertDATA['updated_by'] = $user_id;
            $insertDATA['updated_on'] = date('Y-m-d H:i:s');
            $result_insert_plan = $this->db->insert('items', $insertDATA);
            if ($result_insert_plan !== FALSE) {
                $this->db->where($insertDATA);
                $this->db->from('items');
                $result_plan = $this->db->get();
                if ($result_plan !== FALSE && $result_plan->num_rows() > 0) {
                    foreach ($result_plan->result() as $row) {
                        $item_id = $row->item_id;
                    }
                }
            }

            if (COUNT($errors) === 0) {
                foreach ($tags as $tag_id) {

                    $insert_item_tags_DATA = array(
                        'created_by' => $insertDATA['created_by'],
                        'created_on' => $insertDATA['created_on'],
                        'updated_by' => $insertDATA['updated_by'],
                        'updated_on' => $insertDATA['updated_on'],
                        'tag_id' => $tag_id,
                        'item_id' => $item_id
                    );
                    $this->db->insert('lesson_plans.item_tags', $insert_item_tags_DATA);
                }

                foreach ($files as $value) {
                    $insertFilesDATA = array(
                        'created_by' => $insertDATA['created_by'],
                        'created_on' => $insertDATA['created_on'],
                        'updated_by' => $insertDATA['updated_by'],
                        'updated_on' => $insertDATA['updated_on'],
                        'type' => $value['type'],
                        'name' => urlencode($value['name']),
                        'size' => $value['size']
                    );
                    $result0 = $this->db->insert('lesson_plans.files', $insertFilesDATA);
                    if ($result0 !== FALSE) {
                        $this->db->where($insertFilesDATA);
                        $this->db->from('lesson_plans.files');
                        $result1 = $this->db->get();
                        if ($result1 !== FALSE && $result1->num_rows() === 1) {
                            foreach ($result1->result() as $row) {
                                $file_id = $row->file_id;
                                $insert_item_files_DATA = array(
                                    'created_by' => $insertDATA['created_by'],
                                    'created_on' => $insertDATA['created_on'],
                                    'updated_by' => $insertDATA['updated_by'],
                                    'updated_on' => $insertDATA['updated_on'],
                                    'file_id' => $file_id,
                                    'item_id' => $item_id
                                );
                                $this->db->insert('lesson_plans.item_files', $insert_item_files_DATA);
                            }
                        }
                    }
                }
            }
            $this->load->model('Logs_model', 'Logs_model', TRUE);
            $logDATA = array(
                'name' => 'Save: Plan',
                'log_type_id' => 6,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $user_id,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $user_id,
                'description' => json_encode($insertDATA),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'item_id' => $item_id
            );
            $result_log = $this->Logs_model->insert_log($logDATA);
            $return['status'] = 'success';
            $return['message'] = 'Plan saved.';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function get_my_downloads() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $user_id = Page::userID();
        if (isset($user_id) === TRUE) {
            $this->db->select('COUNT(DISTINCT logs.log_id) AS downloads', FALSE);
            $this->db->select('items.item_id');
            $this->db->select('items.title');
            $this->db->select('items.subtitle');
            $this->db->select('items.category_id');
            $this->db->select('items.cost');
            $this->db->select('items.description');
            $this->db->select('items.created_on');
            $this->db->select('category_images.name AS category_image');
            $this->db->select('items.created_by');
            $this->db->select('logs.created_on as downloaded_on');
            $this->db->select('reviews.text as review');
            $this->db->select('IF(reviews.text IS NULL,0,1) AS reviewed', FALSE);
            $this->db->select('IF(ratings.rating IS NULL,0,1) AS rated', FALSE);
            $this->db->select('ratings.rating as rating');
            $this->db->select('tags.tag_id as tag_id');
            $this->db->select('tags.name as tag');
            $this->db->select('categories.name as category');
            $this->db->select('files.file_id as file_id');
            $this->db->select('files.name as file');
            $this->db->select('files.type as file_type');
            $this->db->select('COUNT(DISTINCT views.log_id) AS views', FALSE);
            $this->db->select('COUNT(DISTINCT downloads.log_id) AS downloads', FALSE);
            $this->db->from('lesson_plans.logs');
            $this->db->join('lesson_plans.items', 'items.item_id=logs.item_id', 'LEFT');
            $this->db->join('lesson_plans.categories', 'items.category_id=categories.category_id', 'LEFT');
            $this->db->join('lesson_plans.images AS category_images', 'category_images.image_id=categories.image_id', 'LEFT');
            $this->db->join('lesson_plans.reviews', 'items.item_id=reviews.item_id AND reviews.created_by = "' . $user_id . '"', 'LEFT');
            $this->db->join('lesson_plans.ratings', 'ratings.deleted_on IS NULL AND items.item_id=ratings.item_id AND ratings.created_by = "' . $user_id . '"', 'LEFT', FALSE);
            $this->db->join('lesson_plans.item_tags', 'items.item_id=item_tags.item_id', 'LEFT');
            $this->db->join('lesson_plans.tags', 'tags.tag_id=item_tags.tag_id', 'LEFT');
            $this->db->join('lesson_plans.item_files', 'items.item_id=item_files.item_id', 'LEFT');
            $this->db->join('lesson_plans.files', 'files.file_id=item_files.file_id', 'LEFT');
            $this->db->join('lesson_plans.logs AS views', 'views.item_id=items.item_id AND views.log_type_id="1"', 'LEFT');
            $this->db->join('lesson_plans.logs AS downloads', 'downloads.item_id=items.item_id AND downloads.log_type_id="2"', 'LEFT');
            $this->db->where(array('logs.created_by' => $user_id, 'logs.log_type_id' => '2'));
            $this->db->where('logs.item_id IS NOT NULL');
            $this->db->group_by('logs.item_id,tags.tag_id,files.file_id');
            $results = $this->db->get();
            $return['results'] = array();
// echo $this->db->last_query();exit();
            if ($results !== FALSE && $results->num_rows() > 0) {
                foreach ($results->result() AS $row) {
                    if (isset($row->item_id) === TRUE) {
                        if (isset($return['results'][$row->item_id]) !== TRUE) {
                            $return['results'][$row->item_id] = array(
                                'category' => $row->category,
                                'downloads' => intval($row->downloads),
                                'views' => intval($row->views),
                                'item_id' => intval($row->item_id),
                                'title' => $row->title,
                                'subtitle' => $row->subtitle,
                                'description' => $row->description,
                                'created_on' => $row->created_on,
                                'reviewed' => intval($row->reviewed),
                                'rated' => intval($row->rated),
                                'review' => $row->review,
                                'rating' => $row->rating,
                                'category_id' => intval($row->category_id),
                                'downloaded_on' => $row->downloaded_on,
                                'category_image' => $row->category_image,
                                'tags' => array(),
                                'files' => array(),
                            );
                        }
                        $return['results'][$row->item_id]['tags'][$row->tag_id] = array(
                            'tag_id' => INTVAL($row->tag_id),
                            'tag' => $row->tag
                        );
                        $return['results'][$row->item_id]['files'][$row->file_id] = array(
                            'file_id' => INTVAL($row->file_id),
                            'file' => $row->file,
                            'type' => $row->file_type
                        );
                    }
                }
            }
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'user_id');
        }

        if (COUNT($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'Downloads found.';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function test() {
        $ids = array(1, 2, 3, 4);
        $reviews = $this->_get_ratings($ids);
        header('Content-Type: application/json');
        echo json_encode($reviews);
        exit();
    }

    public function get_items() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $user_id = Page::userID();
        if (isset($user_id) === TRUE) {
            $this->db->select('items.item_id');
            $this->db->select('items.title');
            $this->db->select('items.subtitle');
            $this->db->select('items.category_id');
            $this->db->select('items.cost');
            $this->db->select('items.description');
            $this->db->select('items.created_on');
            $this->db->select('items.created_by');
            $this->db->select('item_creator.username AS creator_username');
            $this->db->select('item_creator.user_id AS creator_user_id');
            $this->db->select('categories.name as category');
            $this->db->select('category_images.name AS category_image');
            $this->db->select('tags.tag_id as tag_id');
            $this->db->select('tags.name as tag');
            $this->db->select('COUNT(DISTINCT views.log_id) AS views', FALSE);
            $this->db->select('COUNT(DISTINCT downloads.log_id) AS downloads', FALSE);
            $this->db->select('files.file_id as file_id');
            $this->db->select('files.name as file');
            $this->db->select('files.type as file_type');
            $this->db->from('lesson_plans.items');
            $this->db->join('lesson_plans.categories', 'items.category_id=categories.category_id', 'LEFT');
            $this->db->join('lesson_plans.item_tags', 'items.item_id=item_tags.item_id', 'LEFT');
            $this->db->join('lesson_plans.tags', 'tags.tag_id=item_tags.tag_id', 'LEFT');
            $this->db->join('lesson_plans.item_files', 'items.item_id=item_files.item_id', 'LEFT');
            $this->db->join('lesson_plans.files', 'files.file_id=item_files.file_id', 'LEFT');
            $this->db->join('lesson_plans.logs AS views', 'views.item_id=items.item_id AND views.log_type_id="1"', 'LEFT');
            $this->db->join('lesson_plans.logs AS downloads', 'downloads.item_id=items.item_id AND downloads.log_type_id="2"', 'LEFT');
            $this->db->join('lesson_plans.images AS category_images', 'category_images.image_id=categories.image_id', 'LEFT');
            $this->db->join('lesson_plans.users AS item_creator', 'item_creator.user_id=items.created_by', 'LEFT');
            $opts = array(
                'item_id' => 'items',
                'tag_id' => 'tags',
                'category_id' => 'categories',
            );
            foreach ($opts AS $col => $tbl) {
                if ($this->input->post($col) !== FALSE && $this->input->post($col) !== '' && strlen($this->input->post($col)) > 0) {
                    $this->db->where('`' . $tbl . '`.`' . $col . '`="' . $this->input->post($col) . '"');
                }
            }
            $this->db->group_by('tags.tag_id,files.file_id');
            $results = $this->db->get();
// echo $this->db->last_query();exit();
//echo json_encode($results); exit();

            $return['results'] = array();
            $ids = array();
// echo $this->db->last_query();exit();
            if ($results !== FALSE && $results->num_rows() > 0) {
                foreach ($results->result() AS $row) {
                    $ids[] = $row->item_id;
                    if (isset($row->item_id) === TRUE) {
                        if (isset($return['results'][$row->item_id]) !== TRUE) {
                            $return['results'][$row->item_id] = array(
                                'item_id' => intval($row->item_id),
                                'title' => $row->title,
                                'subtitle' => $row->subtitle,
                                'category_id' => intval($row->category_id),
                                'category' => $row->category,
                                'category_image' => $row->category_image,
                                'cost' => $row->cost,
                                //'rating' => $row->rating,
                                'views' => intval($row->views),
                                'downloads' => intval($row->downloads),
                                'created_on' => $row->created_on,
                                'username' => $row->creator_username,
                                'user_id' => intval($row->creator_user_id),
                                'description' => $row->description,
                                'files' => array(),
                                'ratings' => array(),
                                'reviews' => array(),
                                'tags' => array(),
                            );
                        }

                        $return['results'][$row->item_id]['files'][$row->file_id] = array(
                            'file_id' => INTVAL($row->file_id),
                            'file' => $row->file,
                            'type' => $row->file_type
                        );
                    }
                }
                $ratings = $this->_get_ratings($ids);
                foreach ($return['results'] AS $item_id => $item) {
                    if ($ratings !== FALSE && isset($ratings[$item_id]) === TRUE) {
                        $return['results'][$item_id]['ratings'] = $ratings[$item_id];
                    }
                }

                $reviews = $this->_get_reviews($ids);
                foreach ($return['results'] AS $item_id => $item) {
                    if ($reviews !== FALSE && isset($reviews[$item_id]) === TRUE) {
                        $return['results'][$item_id]['reviews'] = $reviews[$item_id];
                    }
                }
                $tags = $this->_get_tags($ids);
                foreach ($return['results'] AS $item_id => $item) {
                    if ($tags !== FALSE && isset($tags[$item_id]) === TRUE) {
                        $return['results'][$item_id]['tags'] = $tags[$item_id];
                    }
                }
            }
        } else {
            $errors[] = array('error' => 'required parameter missing or invalid', 'parameter' => 'user_id');
        }

        if (COUNT($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'items found.';
        } else {
            $return['errors'] = $errors;
        }

        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }

    public function pass_code() {
        $timestamp = date('YmsmYhmh');
        $hex = bin2hex($timestamp);
        $value = unpack('H*', $hex);


        $str = base_convert($value[1], 16, 2);
        echo $str;
        exit();
    }

    function bin2bstr($input) {
// Convert a binary expression (e.g., "100111") into a binary-string
        if (!is_string($input))
            return null; // Sanity check
// Pack into a string
        return pack('H*', base_convert($input, 2, 16));
    }

    public function get_search_results() {
        if ($this->input->post('search') !== FALSE && $this->input->post('search') !== '' && strlen($this->input->post('search')) > 0) {
            $words = explode(' ', $this->input->post('search'));
            $items = $this->_get_search_hits($words);
            $ids = array();
            foreach ($items AS $item) {
                $ids[] = $item['item_id'];
            }
            $result = $this->_get_items($ids);

            foreach ($items AS $k => $v) {
                $result['results'][$k]['hits_data'] = $v['hits'];
                $result['results'][$k]['hits'] = $v['hits']['total'];
            }
            rsort( $result['results']);

            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }
    }

    private function _get_search_hits($words) {
        $data = array();
        $blank_item = array(
            'item_id' => 0,
            'hits' => array(
                'categories' => 0,
                'items' => 0,
                'tags' => 0,
                'total' => 0
            )
        );
        foreach ($words AS $id => $word) {
            $this->db->select('COUNT(DISTINCT `tags`.`tag_id`) AS `hits`', FALSE);
            $this->db->select('`item_tags`.`item_id`');
            $this->db->from('`lesson_plans`.`item_tags`');
            $this->db->join('lesson_plans.tags', 'tags.tag_id=item_tags.tag_id', 'LEFT');
            $this->db->where('tags.name LIKE "%' . $word . '%"');
            $this->db->group_by('item_tags.item_id');
            $result0 = $this->db->get();
            if ($result0 !== FALSE && $result0->num_rows() > 0) {
                foreach ($result0->result() AS $row) {
                    if (isset($data[$row->item_id]) === FALSE) {
                        $data[$row->item_id] = $blank_item;
                        $data[$row->item_id]['item_id'] = INTVAL($row->item_id);
                    }
                    $data[$row->item_id]['hits']['total'] += intval($row->hits);
                    $data[$row->item_id]['hits']['tags'] += intval($row->hits);
                }
            }

            $this->db->select('items.item_id');
            $this->db->select('COUNT(DISTINCT `categories`.`category_id`) AS `hits`', FALSE);
            $this->db->from('lesson_plans.categories');
            $this->db->join('lesson_plans.items', 'items.category_id=categories.category_id', 'LEFT');
            $this->db->where('categories.name LIKE "%' . $word . '%"');
            $this->db->group_by('items.item_id');
            $result1 = $this->db->get();
            if ($result1 !== FALSE && $result1->num_rows() > 0) {
                foreach ($result1->result() AS $row) {
                    if (isset($data[$row->item_id]) === FALSE) {
                        $data[$row->item_id] = $blank_item;
                        $data[$row->item_id]['item_id'] = INTVAL($row->item_id);
                    }
                    $data[$row->item_id]['hits']['total'] += intval($row->hits);
                    $data[$row->item_id]['hits']['categories'] += intval($row->hits);
                }
            }
            $this->db->select('items.item_id');
            $this->db->select('IF(INSTR (items.title,"' . $word . '") > 0, 1,0) AS in_title', FALSE);
            $this->db->select('IF(INSTR (items.subtitle,"' . $word . '") > 0, 1,0) AS in_subtitle', FALSE);
            $this->db->select('IF(INSTR (items.description,"' . $word . '") > 0, 1,0) AS in_description', FALSE);
            $this->db->from('lesson_plans.items');
            $this->db->where('items.title LIKE "%' . $word . '%"');
            $this->db->or_where('items.subtitle LIKE "%' . $word . '%"');
            //$this->db->or_where('items.description LIKE "%' . $word . '%"');

            $result2 = $this->db->get();
            if ($result2 !== FALSE && $result2->num_rows() > 0) {
                foreach ($result2->result() AS $row) {
                    if (isset($data[$row->item_id]) === FALSE) {
                        $data[$row->item_id] = $blank_item;
                        $data[$row->item_id]['item_id'] = INTVAL($row->item_id);
                    }
                    $data[$row->item_id]['hits']['total'] += intval($row->in_title) + intval($row->in_subtitle) + intval($row->in_description);
                    $data[$row->item_id]['hits']['items'] += intval($row->in_title) + intval($row->in_subtitle) + intval($row->in_description);
                }
            }
        }
        if (COUNT($data) > 0) {
            //rsort($data);
            return $data;
        }
        return FALSE;
    }

    private function _get_reviews($ids) {
        $this->db->select('reviews.text');
        $this->db->select('reviews.review_id');
        $this->db->select('reviews.item_id');
        $this->db->select('reviews.created_by');
        $this->db->select('reviews.created_on');
        $this->db->select('users.username');
        $this->db->select('users.user_id');
        $this->db->where_in('reviews.item_id', $ids);
        $this->db->from('lesson_plans.reviews');
        $this->db->join('lesson_plans.users', 'users.user_id=reviews.created_by', 'LEFT');
        $result = $this->db->get();
        $data = array();
        if ($result !== FALSE && $result->num_rows() > 0) {
            foreach ($result->result() AS $row) {
                if (isset($data[$row->item_id]) === FALSE) {
                    $data[$row->item_id] = array();
                }
                $data[$row->item_id][$row->review_id] = array(
                    'review_id' => INTVAL($row->review_id),
                    'item_id' => INTVAL($row->item_id),
                    'review' => $row->text,
                    'created_on' => $row->created_on,
                    'username' => $row->username,
                    'user_id' => INTVAL($row->user_id)
                );
            }
            if (COUNT($data) > 0) {
                return $data;
            }
        }
        return FALSE;
    }

    private function _get_ratings($ids) {
        $this->db->select('ratings.rating_id');
        $this->db->select('ratings.rating');
        $this->db->select('ratings.item_id');
        $this->db->select('ratings.created_by');
        $this->db->select('ratings.created_on');
        $this->db->select('users.username');
        $this->db->select('users.user_id');
        $this->db->where_in('ratings.item_id', $ids);
        $this->db->from('lesson_plans.ratings');
        $this->db->join('lesson_plans.users', 'users.user_id=ratings.created_by', 'LEFT');
        $result = $this->db->get();
        $data = array();
        if ($result !== FALSE && $result->num_rows() > 0) {
            foreach ($result->result() AS $row) {
                if (isset($data[$row->item_id]) === FALSE) {
                    $data[$row->item_id] = array();
                }
                $data[$row->item_id][$row->rating_id] = array(
                    'rating_id' => INTVAL($row->rating_id),
                    'item_id' => INTVAL($row->item_id),
                    'rating' => $row->rating,
                    'created_on' => $row->created_on,
                    'username' => $row->username,
                    'user_id' => INTVAL($row->user_id)
                );
            }
            if (COUNT($data) > 0) {
                return $data;
            }
        }
        return FALSE;
    }

    private function _get_tags($ids) {
        $this->db->select('tags.tag_id');
        $this->db->select('tags.name');
        $this->db->select('item_tags.item_id');
        $this->db->where_in('item_tags.item_id', $ids);
        $this->db->from('lesson_plans.item_tags');
        $this->db->join('lesson_plans.tags', 'tags.tag_id=item_tags.tag_id', 'LEFT');
        $result = $this->db->get();
        $data = array();
        if ($result !== FALSE && $result->num_rows() > 0) {
            foreach ($result->result() AS $row) {
                if (isset($data[$row->item_id]) === FALSE) {
                    $data[$row->item_id] = array();
                }
                $data[$row->item_id][$row->tag_id] = array(
                    'tag_id' => INTVAL($row->tag_id),
                    'item_id' => INTVAL($row->item_id),
                    'tag' => $row->name,
                );
            }
            if (COUNT($data) > 0) {
                return $data;
            }
        }
        return FALSE;
    }

    private function _get_items($ids) {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time');
        $errors = array();
        $this->db->select('items.item_id');
        $this->db->select('items.title');
        $this->db->select('items.subtitle');
        $this->db->select('items.category_id');
        $this->db->select('items.cost');
        $this->db->select('items.description');
        $this->db->select('items.created_on');
        $this->db->select('items.created_by');
        $this->db->select('item_creator.username AS creator_username');
        $this->db->select('item_creator.user_id AS creator_user_id');
        $this->db->select('categories.name as category');
        $this->db->select('category_images.name AS category_image');
        $this->db->select('tags.tag_id as tag_id');
        $this->db->select('tags.name as tag');
        $this->db->select('COUNT(DISTINCT views.log_id) AS views', FALSE);
        $this->db->select('COUNT(DISTINCT downloads.log_id) AS downloads', FALSE);
        $this->db->select('files.file_id as file_id');
        $this->db->select('files.name as file');
        $this->db->select('files.type as file_type');
        $this->db->from('lesson_plans.items');
        $this->db->join('lesson_plans.categories', 'items.category_id=categories.category_id', 'LEFT');
        $this->db->join('lesson_plans.item_tags', 'items.item_id=item_tags.item_id', 'LEFT');
        $this->db->join('lesson_plans.tags', 'tags.tag_id=item_tags.tag_id', 'LEFT');
        $this->db->join('lesson_plans.item_files', 'items.item_id=item_files.item_id', 'LEFT');
        $this->db->join('lesson_plans.files', 'files.file_id=item_files.file_id', 'LEFT');
        $this->db->join('lesson_plans.logs AS views', 'views.item_id=items.item_id AND views.log_type_id="1"', 'LEFT');
        $this->db->join('lesson_plans.logs AS downloads', 'downloads.item_id=items.item_id AND downloads.log_type_id="2"', 'LEFT');
        $this->db->join('lesson_plans.images AS category_images', 'category_images.image_id=categories.image_id', 'LEFT');
        $this->db->join('lesson_plans.users AS item_creator', 'item_creator.user_id=items.created_by', 'LEFT');
        $this->db->where_in('items.item_id', $ids);
        $this->db->group_by('tags.tag_id,files.file_id');
        $results = $this->db->get();
        $return['results'] = array();


        if ($results !== FALSE && $results->num_rows() > 0) {
            foreach ($results->result() AS $row) {
                $ids[] = $row->item_id;
                if (isset($row->item_id) === TRUE) {
                    if (isset($return['results'][$row->item_id]) !== TRUE) {
                        $return['results'][$row->item_id] = array(
                            'hits' => 0,
                            'item_id' => intval($row->item_id),
                            'title' => $row->title,
                            'subtitle' => $row->subtitle,
                            'category_id' => intval($row->category_id),
                            'category' => $row->category,
                            'category_image' => $row->category_image,
                            'cost' => $row->cost,
                            'views' => intval($row->views),
                            'downloads' => intval($row->downloads),
                            'created_on' => $row->created_on,
                            'username' => $row->creator_username,
                            'user_id' => intval($row->creator_user_id),
                            'description' => $row->description,
                            'files' => array(),
                            'ratings' => array(),
                            'reviews' => array(),
                            'tags' => array(),
                        );
                    }

                    $return['results'][$row->item_id]['files'][$row->file_id] = array(
                        'file_id' => INTVAL($row->file_id),
                        'file' => $row->file,
                        'type' => $row->file_type
                    );
                }
            }
            $ratings = $this->_get_ratings($ids);
            foreach ($return['results'] AS $item_id => $item) {
                if ($ratings !== FALSE && isset($ratings[$item_id]) === TRUE) {
                    $return['results'][$item_id]['ratings'] = $ratings[$item_id];
                }
            }

            $reviews = $this->_get_reviews($ids);
            foreach ($return['results'] AS $item_id => $item) {
                if ($reviews !== FALSE && isset($reviews[$item_id]) === TRUE) {
                    $return['results'][$item_id]['reviews'] = $reviews[$item_id];
                }
            }
            $tags = $this->_get_tags($ids);
            foreach ($return['results'] AS $item_id => $item) {
                if ($tags !== FALSE && isset($tags[$item_id]) === TRUE) {
                    $return['results'][$item_id]['tags'] = $tags[$item_id];
                }
            }
        }


        if (COUNT($errors) === 0) {
            $return['status'] = 'success';
            $return['message'] = 'items found.';
        } else {
            $return['errors'] = $errors;
        }
        return $return;
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }
public function post_item_view() {
        $return = array('status' => 'error', 'message' => 'Unable to process your request at this time.');
        $errors = array();
        $data = array();
        if ($this->input->post('item_id') !== FALSE) {
            $data['item_id'] = $this->input->post('item_id');
        }
        $data['user_id'] = 1;
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id'])) {
            $data['user_id'] = $_SESSION['user']['user_id'];
        }
        if (count($data) > 0) {

            $this->load->model('Logs_model', 'Logs_model', TRUE);
            $insertDATA = array(
                'name' => 'Item: View',
                'log_type_id' => 1,
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $data['user_id'],
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $data['user_id'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'item_id' => $data['item_id']
            );

            $result_log = $this->Logs_model->insert_log($insertDATA);
            $return['status'] = 'success';
            $return['message'] = 'log saved';
        } else {
            $return['errors'] = $errors;
        }
        header('Content-Type: application/json');
        echo json_encode($return);
        exit();
    }
}
