<?php

/**
 * @file       /application/controllers/My.php
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

class My extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('grocery_CRUD');
        $this->load->helper(array('url', 'session', 'script', 'timestamp', 'page'));
        $this->load->model(array('auth_model', 'settings_model'), TRUE);
    }

    public function view($dataSets = NULL) {
        $pageDATA = Page::get_page_data('my_view');
        if (isset($dataSets) === TRUE) {
            $pageDATA['dataSets'] = $dataSets;
        }
        $this->load->view('header', $pageDATA);
        $this->load->view('main', $pageDATA);
        $this->load->view('footer', $pageDATA);
    }

    public function index() {
        $this->view();
    }

    public function settings() {
        $this->view();
    }

    public function unreviewed() {
        $this->view();
    }

    
    public function unrated() {
        $this->view();
    }
    public function ratings() {
        $pageDATA = Page::get_page_data('my_rating');
        $this->load->view('header', $pageDATA);
        $this->load->view('my_ratings', $pageDATA);
        $this->load->view('footer', $pageDATA);
    }

    public function downloads() {
        $this->view();
    }

    public function _example_output($output = null) {
        $pageDATA = Page::get_page_data('my_manage');
        //$this->load->view('header', $pageDATA);
        $this->load->view('my_manage.php', $output);
        $this->load->view('footer', $pageDATA);
    }

    public function plans_gcrud() {
        // $this->db = $this->load->database('data', TRUE);
        if (isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_id']) === TRUE) {
            $crud = new grocery_CRUD();
            $crud->set_table('items');
            $crud->set_subject('Plans');

            $crud->where('items.deleted_on IS NULL AND items.created_by = ' . $_SESSION['user']['user_id']);
            //$crud->where(array('files.created_by' => $_SESSION['user']['user_id']));
            //$crud->where(array('images.created_by' => $_SESSION['user']['user_id']));

            $crud->set_crud_url_path(site_url('/my/plans'));
            $crud->columns('title', 'category_id', 'tags', 'created_on');
            $crud->display_as('created_on', 'Created');
            $crud->display_as('updated_on', 'Updated');
            $crud->display_as('created_by', 'created by');
            $crud->display_as('category_id', 'Category');
            $crud->display_as('type_id', 'Type');
            $crud->fields('title', 'subtitle', 'category_id', 'tags', 'files', 'images', 'cost', 'description');
            $crud->set_relation_n_n('tags', 'item_tags', 'tags', 'item_id', 'tag_id', 'name', FALSE);
            $crud->set_relation_n_n('files', 'item_files', 'files', 'item_id', 'file_id', 'name', FALSE);
            $crud->set_relation_n_n('images', 'item_images', 'images', 'item_id', 'image_id', 'name', FALSE);
            $crud->set_relation('category_id', 'categories', 'name');
            $crud->set_relation('type_id', 'types', 'name');
            $crud->set_relation('created_by', 'users', 'username');
            $crud->set_relation('updated_by', 'users', 'username');
            $crud->set_relation('deleted_by', 'users', 'username');
            $crud->unset_fields('description_long', 'name', 'deleted_by', 'deleted_on', 'item_id', 'type_id', 'dev', 'created_on', 'created_by', 'updated_on', 'updated_by');
            //$crud->unset_delete();
            $output = $crud->render();
            $this->_example_output($output);
        } else {
            header('Location: /');
        }
    }
  public function plans() {
        $pageDATA = Page::get_page_data('main');
        $this->load->view('header', $pageDATA);
        $this->load->view('main', $pageDATA);
        $this->load->view('footer', $pageDATA);
    }
}
