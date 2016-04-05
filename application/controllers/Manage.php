<?php
/**
 * @file       /application/controllers/Manage.php
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
class Manage extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'timestamp'));
        $this->load->library('session');
        $this->load->library('grocery_CRUD');
    }
    public function access() {
        if (isset($_SESSION, $_SESSION['user_type_id']) === FALSE || $_SESSION['user_type_id'] !== 1) {
            exit('Unauthorized access');
        }
    }
    public function _example_output($output = null) {
        $this->load->view('manage.php', $output);
    }
    public function index() {
        $this->access();
        $this->multigrids();
    }
    public function multigrids() {
        $this->access();
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);
        $output1 = $this->products_multigrid();
        $output2 = $this->categories_multigrid();
        $output3 = $this->users_multigrid();
        $output4 = $this->user_types_multigrid();
        $js_files = $output1->js_files + $output2->js_files + $output3->js_files + $output4->js_files;
        $css_files = $output1->css_files + $output2->css_files + $output3->css_files + $output4->css_files;
        $output = $output1->output . $output2->output . $output3->output . $output4->output;
        $this->_example_output((object) array(
                    'js_files' => $js_files,
                    'css_files' => $css_files,
                    'output' => $output
        ));
    }
    public function products_multigrid() {
        $this->access();
        $crud = new grocery_CRUD();
        //$this->config->load('grocery_crud');
        //$this->config->set_item('grocery_crud_dialog_forms', true);
        //$this->config->set_item('grocery_crud_default_per_page', 10);
        $crud->set_table('products');
        $crud->set_subject('Products');
        $crud->where('products.deleted_on IS NULL');
        $crud->columns('product_id', 'image_id', 'name', 'category_id', 'updated_on', 'updated_by');
        $crud->fields('name', 'category_id', 'sales_price','active', 'description', 'image_id');
        $crud->display_as('updated_on', 'Updated')
                ->display_as('updated_by', 'Updated By')
                ->display_as('created_on', 'Created')
                ->display_as('created_by', 'Created By')
                ->display_as('category_id', 'Category')
                ->display_as('image_id', 'Image')
                ->display_as('product_id', 'ID')
                ->display_as('active', 'State');
        $crud->set_relation('category_id', 'categories', 'name');
        $crud->set_relation('created_by', 'users', 'username');
        $crud->set_relation('updated_by', 'users', 'username');
        $crud->set_relation('deleted_by', 'users', 'username');
        $crud->set_field_upload('image_id', 'assets/uploads/files');
        $crud->callback_after_insert(array($this, 'products_after_insert'));
        $crud->callback_after_update(array($this, 'products_after_update'));
        $crud->callback_delete(array($this, 'products_delete'));
        $crud->set_crud_url_path(site_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), site_url(strtolower(__CLASS__ . "/multigrids")));
        $output = $crud->render();
        if ($crud->getState() != 'list') {
            $this->_example_output($output);
        } else {
            return $output;
        }
    }
    public function products_after_insert($post_array, $primary_key) {
        $this->access();
        return $this->db->update('products', TimeStamp::dbCreateArr(), array('product_id' => $primary_key));
    }
    public function products_after_update($post_array, $primary_key) {
        $this->access();
        return $this->db->update('products', TimeStamp::dbUpdateArr(), array('product_id' => $primary_key));
    }
    public function products_delete($primary_key) {
        $this->access();
        return $this->db->update('products', TimeStamp::dbDeleteArr(), array('product_id' => $primary_key));
    }
    public function categories_multigrid() {
        $this->access();
        $crud = new grocery_CRUD();
        $crud->set_table('categories');
        $crud->set_subject('Categories');
        $crud->where('categories.deleted_on IS NULL');
        $crud->columns('category_id', 'image_id', 'name', 'updated_on', 'updated_by');
        $crud->fields('name', 'description', 'image_id');
        $crud->display_as('updated_on', 'Updated')
                ->display_as('updated_by', 'Updated By')
                ->display_as('created_on', 'Created')
                ->display_as('created_by', 'Created By')
                ->display_as('category_id', 'Category')
                ->display_as('image_id', 'Image')
                ->display_as('category_id', 'ID');
        $crud->set_relation('created_by', 'users', 'username');
        $crud->set_relation('updated_by', 'users', 'username');
        $crud->set_relation('deleted_by', 'users', 'username');
        $crud->set_field_upload('image_id', 'assets/uploads/files');
        $crud->callback_after_insert(array($this, 'categories_after_insert'));
        $crud->callback_after_update(array($this, 'categories_after_update'));
        $crud->callback_delete(array($this, 'categories_delete'));
        $crud->set_crud_url_path(site_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), site_url(strtolower(__CLASS__ . "/multigrids")));
        $output = $crud->render();
        if ($crud->getState() != 'list') {
            $this->_example_output($output);
        } else {
            return $output;
        }
    }
    public function categories_after_insert($post_array, $primary_key) {
        $this->access();
        return $this->db->update('products', TimeStamp::dbCreateArr(), array('category_id' => $primary_key));
    }
    public function categories_after_update($post_array, $primary_key) {
        $this->access();
        return $this->db->update('products', TimeStamp::dbUpdateArr(), array('category_id' => $primary_key));
    }
    public function categories_delete($primary_key) {
        $this->access();
        return $this->db->update('products', TimeStamp::dbDeleteArr(), array('category_id' => $primary_key));
    }
    public function users_multigrid() {
        $this->access();
        $crud = new grocery_CRUD();
        $crud->set_table('users');
        $crud->set_subject('Users');
        $crud->where('users.deleted_on IS NULL');
        $crud->columns('user_id', 'image_id', 'user_type_id', 'username', 'updated_on', 'updated_by');
        $crud->fields('username', 'first_name', 'last_name', 'user_type_id', 'image_id');
        $crud->display_as('updated_on', 'Updated')
                ->display_as('updated_by', 'Updated By')
                ->display_as('created_on', 'Created')
                ->display_as('created_by', 'Created By')
                ->display_as('category_id', 'Category')
                ->display_as('image_id', 'Image')
                ->display_as('user_id', 'ID');
        $crud->set_relation('created_by', 'users', 'username');
        $crud->set_relation('updated_by', 'users', 'username');
        $crud->set_relation('deleted_by', 'users', 'username');
        $crud->set_relation('user_type_id', 'user_types', 'name');
        $crud->set_field_upload('image_id', 'assets/uploads/files');
        $crud->callback_after_insert(array($this, 'users_after_insert'));
        $crud->callback_after_update(array($this, 'users_after_update'));
        $crud->callback_delete(array($this, 'users_delete'));
        $crud->set_crud_url_path(site_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), site_url(strtolower(__CLASS__ . "/multigrids")));
        $output = $crud->render();
        if ($crud->getState() != 'list') {
            $this->_example_output($output);
        } else {
            return $output;
        }
    }
    public function users_after_insert($post_array, $primary_key) {
        $this->access();
        return $this->db->update('users', TimeStamp::dbCreateArr(), array('user_id' => $primary_key));
    }
    public function users_after_update($post_array, $primary_key) {
        $this->access();
        return $this->db->update('users', TimeStamp::dbUpdateArr(), array('user_id' => $primary_key));
    }
    public function users_delete($primary_key) {
        $this->access();
        return $this->db->update('users', TimeStamp::dbDeleteArr(), array('user_id' => $primary_key));
    }
    public function user_types_multigrid() {
        $this->access();
        $crud = new grocery_CRUD();
        $crud->set_table('user_types');
        $crud->set_subject('User Types');
        $crud->columns('user_type_id', 'image_id','name', 'updated_on', 'updated_by');
        $crud->fields('user_type_id', 'image_id', 'name', 'updated_on', 'updated_by');
        $crud->display_as('updated_on', 'Updated')
                ->display_as('updated_by', 'Updated By')
                ->display_as('created_on', 'Created')
                ->display_as('created_by', 'Created By')
                ->display_as('category_id', 'Category')
                ->display_as('image_id', 'Image')
                ->display_as('user_type_id', 'ID');
        $crud->set_relation('created_by', 'users', 'username');
        $crud->set_relation('updated_by', 'users', 'username');
        $crud->set_relation('deleted_by', 'users', 'username');
        $crud->set_field_upload('image_id', 'assets/uploads/files');
        $crud->callback_after_insert(array($this, 'user_types_after_insert'));
        $crud->callback_after_update(array($this, 'user_types_after_update'));
        $crud->callback_delete(array($this, 'user_types_delete'));
        $crud->set_crud_url_path(site_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), site_url(strtolower(__CLASS__ . "/multigrids")));
        $output = $crud->render();
        if ($crud->getState() != 'list') {
            $this->_example_output($output);
        } else {
            return $output;
        }
    }
    public function user_types_after_insert($post_array, $primary_key) {
        $this->access();
        return $this->db->update('user_types', TimeStamp::dbCreateArr(), array('user_type_id' => $primary_key));
    }
    public function user_types_after_update($post_array, $primary_key) {
        $this->access();
        return $this->db->update('user_types', TimeStamp::dbUpdateArr(), array('user_type_id' => $primary_key));
    }
    public function user_types_delete($primary_key) {
        $this->access();
        return $this->db->update('user_types', TimeStamp::dbDeleteArr(), array('user_type_id' => $primary_key));
    }
}
