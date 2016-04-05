<?php

/**
 * @file       /application/models/Items_model.php
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

class Items_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function _search($inDATA) {
        if (isset($inDATA)) {
            $this->db->select('`items`.`item_id` AS `item_id`');
            $this->db->select('`items`.`title` AS `title`');
            $this->db->select('`items`.`subtitle` AS `subtitle`');
            $this->db->select('`items`.`cost` AS `cost`');
            $this->db->select('`files`.`file_id` AS `file_id`');
            $this->db->select('`files`.`name` AS `file_name`');
            $this->db->select('`files`.`type` AS `file_type`');
            $this->db->select('`items`.`description` AS `description`');
            $this->db->select('`items`.`created_on` AS `created_on`');
            $this->db->select('`items`.`created_by` AS `created_by`');
            $this->db->select('`categories`.`name` AS `category`');
            $this->db->select('`categories`.`category_id` AS `category_id`');
            $this->db->select('`category_image`.`name` AS `category_image`');
            $this->db->select('`types`.`name` AS `type`');
            $this->db->select('`types`.`type_id` AS `type_id`');
            $this->db->select('`type_image`.`name` AS `type_image`');
            $this->db->select('`tags`.`name` AS `tag`');
            $this->db->select('`tags`.`tag_id` AS `tag_id`');
            $this->db->select('`item_image`.`image_id` AS `item_image_id`');
            $this->db->select('`item_image`.`name` AS `item_image_name`');
            $this->db->select('`item_creator`.`username` AS `item_creator`');
            $this->db->select('COUNT(DISTINCT `files`.`file_id`) AS `files`',FALSE);
            
            $this->db->select('(SELECT COUNT(DISTINCT `logs`.`log_id`) FROM  lesson_plans.logs where logs.log_type_id=1 AND logs.item_id=items.item_id) AS `views`', FALSE);
            $this->db->select('(SELECT COUNT(DISTINCT `logs`.`log_id`) FROM  lesson_plans.logs where logs.log_type_id=2 AND logs.item_id=items.item_id) AS `downloads`', FALSE);
            $this->db->select('(SELECT COUNT(DISTINCT `reviews`.`review_id`) FROM  lesson_plans.reviews where reviews.deleted_on IS NULL AND reviews.item_id=items.item_id) AS `reviews`', FALSE);
            $this->db->select('(SELECT if(sum( `ratings`.`rating`)/COUNT(*) IS NULL,"n/a",ROUND(sum( `ratings`.`rating`)/COUNT(*), 2)) FROM  lesson_plans.ratings where ratings.deleted_on IS NULL AND ratings.item_id=items.item_id) AS `rating`', FALSE);
            $this->db->from('`lesson_plans`.`items`');
            $this->db->join('`lesson_plans`.`categories` AS `categories`', 'categories.category_id=items.category_id', 'LEFT');
            $this->db->join('`lesson_plans`.`item_files` AS `item_files`', 'item_files.item_id=items.item_id', 'LEFT');
            $this->db->join('`lesson_plans`.`files` AS `files`', 'files.file_id=item_files.file_id', 'LEFT');
            $this->db->join('`lesson_plans`.`item_tags`', 'item_tags.item_id=items.item_id', 'LEFT');
            $this->db->join('`lesson_plans`.`tags`', 'item_tags.tag_id=tags.tag_id', 'LEFT');
            $this->db->join('`lesson_plans`.`logs` AS `downloads`', 'downloads.item_id=items.item_id AND downloads.log_type_id = 2', 'LEFT');
            $this->db->join('`lesson_plans`.`logs` AS `views`', 'views.item_id=items.item_id AND downloads.log_type_id = 1', 'LEFT');
            $this->db->join('`lesson_plans`.`reviews` AS `reviews`', 'reviews.item_id=items.item_id', 'LEFT');
            $this->db->join('`lesson_plans`.`ratings` AS `ratings`', 'ratings.item_id=items.item_id', 'LEFT');
  
            $this->db->where('items.deleted_on IS NULL');
            if (isset($inDATA['search'])) {
                $this->db->where('items.title LIKE "%' . $inDATA['search'] . '%" OR items.description LIKE "%' . $inDATA['search'] . '%" ');
            }
            if (isset($inDATA['category_id'])) {
                $this->db->where('`categories`.`category_id`=' . $inDATA['category_id']);
            }
            if (isset($inDATA['tag_id'])) {
                $this->db->where('`tags2`.`tag_id`=' . $inDATA['tag_id']);
            }
            if (isset($inDATA['item_id'])) {
                $this->db->where('`items`.`item_id`=' . $inDATA['item_id']);
            }
            $this->db->group_by('items.item_id');
            $this->db->limit(25);
            $result = $this->db->get();
            $returnDATA=array();
            if ($result && $result->num_rows() > 0) {
                foreach($result->result() AS $k => $v){
                    if(isset($returnDATA[$v->item_id]) === FALSE){
                        $returnDATA[$v->item_id]=array(
                            'item_id'=>intval($v->item_id),
                            'title'=>$v->title,
                            'subtitle'=>$v->subtitle,
                            'description'=>$v->description,
                            'cost'=>$v->cost,
                            'created_by'=>intval($v->created_by),
                            'created_on'=>$v->created_on,
                            'category_id'=>intval($v->category_id),
                            'category_image'=>$v->category_image,
                            'category'=>$v->category,
                            'item_creator'=>$v->item_creator,
                            'downloads'=>intval($v->downloads),
                            'views'=>intval($v->views),
                            'reviews'=>intval($v->reviews),
                            'rating'=>$v->rating,
                            'tags'=>array(),
                            'files'=>array(),
                            'images'=>array(),
                        );
                    }
                    $returnDATA[$v->item_id]['tags'][$v->tag_id]=array('tag_id'=>intval($v->tag_id),'tag'=>$v->tag);
                    $returnDATA[$v->item_id]['files'][$v->file_id]=array('file_id'=>intval($v->file_id),'file_type'=>$v->file_type,'file_name'=>$v->file_name);
                    if(isset($v->item_image_id) === TRUE){
                    $returnDATA[$v->item_id]['images'][$v->item_image_id]=array('image_id'=>intval($v->item_image_id),'name'=>$v->item_image_name);
                    }
                }
                return $returnDATA;
            }
        }
        return FALSE;
    }

    public function item_details($inDATA) {
        if (isset($inDATA, $inDATA['item_id'])) {
            $this->db->select('`items`.*');
            $this->db->select('`tags`.`name` AS `tag`');
            $this->db->select('`tags`.`tag_id` AS `tag_id`');
            $this->db->select('`categories`.`name` AS `category`');
            $this->db->select('`categories`.`category_id` AS `category_id`');
            $this->db->select('`category_image`.`name` AS `category_image`');
            $this->db->select('`types`.`name` AS `type`');
            $this->db->select('`types`.`type_id` AS `type_id`');
            $this->db->select('`files`.`name` AS `file_name`');
            $this->db->select('`files`.`type` AS `file_type`');
            $this->db->select('`files`.`size` AS `file_size`');
            $this->db->select('`type_image`.`name` AS `type_image`');
            $this->db->select('`item_creator`.`username` AS `creator_username`');
            $this->db->select('`item_creator`.`user_id` AS `creator_user_id`');
            $this->db->select('COUNT(DISTINCT `views`.`log_id`) AS `views`',FALSE);
            $this->db->select('COUNT(DISTINCT `downloads`.`log_id`) AS `downloads`',FALSE);
            $this->db->select('SUM(ratings.rating) AS `rating`',FALSE);
            $this->db->from('`lesson_plans`.`items`');
            $this->db->join('`lesson_plans`.`item_tags`', 'item_tags.item_id=items.item_id', 'LEFT');
            $this->db->join('`lesson_plans`.`tags`', 'item_tags.tag_id=tags.tag_id', 'LEFT');
            $this->db->join('`lesson_plans`.`categories` AS `categories`', 'categories.category_id=items.category_id', 'LEFT');
            $this->db->join('`lesson_plans`.`types` AS `types`', 'types.type_id=items.type_id', 'LEFT');
            $this->db->join('`lesson_plans`.`images` AS `category_image`', 'category_image.image_id=categories.image_id', 'LEFT');
            $this->db->join('`lesson_plans`.`images` AS `type_image`', 'type_image.image_id=types.image_id', 'LEFT');
            $this->db->join('`lesson_plans`.`users` AS `item_creator`', 'item_creator.user_id=items.created_by', 'LEFT');
            $this->db->join('`lesson_plans`.`item_files` AS `item_files`', 'item_files.item_id=items.item_id', 'LEFT');
            $this->db->join('`lesson_plans`.`files` AS `files`', 'files.file_id=item_files.file_id', 'LEFT');
            $this->db->join('`lesson_plans`.`logs` AS `views`', 'views.item_id=items.item_id AND views.name="Item: View"', 'LEFT',FALSE);
            $this->db->join('`lesson_plans`.`logs` AS `downloads`', 'downloads.item_id=items.item_id AND downloads.name="Item: Download"', 'LEFT',FALSE);
            $this->db->join('`lesson_plans`.`ratings` AS `ratings`', 'ratings.item_id=items.item_id ', 'LEFT',FALSE);
            $this->db->where('`items`.`item_id`=' . $inDATA['item_id']);
            $this->db->where('items.deleted_on IS NULL');
            $this->db->group_by('tags.name');

            $result = $this->db->get();
            if ($result && $result->num_rows() > 0) {
                $this->db->select('SUM(ratings.rating) / COUNT(ratings.rating_id) AS rating');
                $this->db->from('`lesson_plans`.`ratings`');
                $this->db->where(array('ratings.item_id'=>$inDATA['item_id']));
                $result_rating=$this->db->get();
                $rating='n/a';
                //echo $this->db->last_query();
               
                if($result_rating !== FALSE && $result_rating->num_rows() === 1){

                    foreach($result_rating->result() AS $row){
                        $rating=$row->rating;
                    }
                }
                
                $itemDATA = array();
                $tags = array();
                foreach ($result->result() AS $row) {
                    $itemDATA = array(
                        'item_id' => intval($row->item_id),
                        'type_id' => intval($row->type_id),
                        'category_id' => intval($row->category_id),
                        'name' => $row->name,
                        'title' => $row->title,
                        'subtitle' => $row->subtitle,
                        'description' => $row->description,
                        'cost' => $row->cost,
                        'description_long' => $row->description_long,
                        'file_name' => $row->file_name,
                        'file_type' => $row->file_type,
                        'file_size' => $row->file_size,
                        'created_on' => $row->created_on,
                        'created_by' => intval($row->created_by),
                        'updated_on' => $row->updated_on,
                        'updated_by' => intval($row->updated_by),
                        'category' => $row->category,
                        'category_image' => $row->category_image,
                        'type' => $row->type,
                        'type_image' => $row->type_image,
                        'creator_username' => $row->creator_username,
                        'creator_user_id' => intval($row->creator_user_id),
                        'views' => intval($row->views),
                        'downloads' => intval($row->downloads),
                        'rating'=>$rating
                    );
                    $tags[] = array(
                        'tag' => $row->tag,
                        'tag_id' => intval($row->tag_id)
                    );
                }
                $itemDATA['tags'] = $tags;
                $this->db->select('`files`.`file_id`');
                $this->db->select('`files`.`type_id`');
                $this->db->select('`files`.`name`');
                $this->db->select('`files`.`type`');
                $this->db->select('`files`.`size`');
                $this->db->select('`files`.`created_on`');
                $this->db->select('`files`.`created_by`');
                $this->db->from('`lesson_plans`.`files`');
                $this->db->join('`lesson_plans`.`item_files`', 'item_files.file_id=files.file_id', 'LEFT');
                $this->db->where('`item_files`.`item_id`=' . $inDATA['item_id']);
                $result_files=$this->db->get();
                if($result_files && $result_files->num_rows() > 0 ){
                    $itemDATA['files']=$result_files->result();
                }
                return $itemDATA;
            }
        }
        return FALSE;
    }

    public function save_item($inDATA) {
        $options = array(
            'item_id',
            'type_id',
            'category_id',
            'title',
            'subtitle',
            'name',
            'description',
            'cost',
            'description_long',
            'file_id',
            'created_on',
            'created_by',
            'updated_on',
            'updated_by'
        );
        $itemDATA = array();
        foreach ($options AS $option) {
            if (isset($inDATA[$option])) {
                $itemDATA[$option] = $inDATA[$option];
            }
        }


        if (isset($itemDATA['item_id'])) {
//UPDATE: item                    
            $result = $this->db->update('`lesson_plans`.`items`', $itemDATA);
        } else {
//INSERT: item  
            $result = $this->db->insert('`lesson_plans`.`items`', $itemDATA);
        }
        
//GET: item_id      
        if ($result) {
            $result_get = $this->db->get_where('`lesson_plans`.`items`', $itemDATA);
            if ($result_get !== FALSE && $result_get->num_rows() > 0) {
                foreach ($result_get->result() AS $row) {
                    $item_id = $row->item_id;
                }
            }
            
         
            if (isset($item_id)) {
//INSERT: tags   
                foreach ($inDATA['tags'] AS $tag_id) {
                    $tagDATA = array(
                        'item_id' => $item_id,
                        'tag_id' => $tag_id,
                        'created_on' => $itemDATA['updated_on'],
                        'updated_on' => $itemDATA['updated_on'],
                        'created_by' => $itemDATA['updated_by'],
                        'updated_by' => $itemDATA['updated_by'],
                    );
                    $this->db->insert('`lesson_plans`.`item_tags`', $tagDATA);
                }
//INSERT: item_files   
                foreach ($inDATA['files'] AS $file_id) {
                    $fileDATA = array(
                        'item_id' => $item_id,
                        'file_id' => $file_id,
                        'created_on' => $itemDATA['updated_on'],
                        'updated_on' => $itemDATA['updated_on'],
                        'created_by' => $itemDATA['updated_by'],
                        'updated_by' => $itemDATA['updated_by'],
                    );
                    $this->db->insert('`lesson_plans`.`item_files`', $fileDATA);
                }
                return $item_id;
            }
            return TRUE;
        }

        return FALSE;
    }

}
