<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		PyroCMS
 * @subpackage	Categories Module
 * @category	Modules
 * @author		Edward Meehan
 */
class Estate_m extends MY_Model
{
	protected $_table = 'estate';
	
	/**
	 * Insert a new item into the database
	 * @access public
	 * @param array $input The data to insert
	 * @return string
	 */
	 
	public function insert($input = array())
    {
    	return parent::insert(array(
        	// Content Fields
			'title'=>$input['title'],
        	'slug'=>url_title(strtolower(convert_accented_characters($input['slug']))),
			'group_id'=>(int)$input['group_id'],
			'listing_status' => $input['listing_status'],
			'intro'=>$input['intro'],
			'body'=>$input['body'],
			// Detail Fields
			'listing_id' => $input['listing_id'],
			'listing_type' => (int)$input['listing_type'],
			'listing_price' => $input['listing_price'],
			'listing_size' =>$input['listing_size'],
			'listing_size_m' =>(int)$input['listing_size_m'],
			'listing_lot_size' =>$input['listing_lot_size'],
			'listing_lot_size_m' =>(int)$input['listing_lot_size_m'],
			// Location Fields
			'address_1' => $input['address_1'],
			'address_2' => $input['address_2'],
			'city' => $input['city'],
			'state' => $input['state'],
			'zip' => (int)$input['zip'],
			// Google Maps
			'listing_map_marker' => (int)$input['listing_map_marker'],
			'listing_lat' => (float)$input['listing_lat'],
			'listing_lng' => (float)$input['listing_lng'],
			// Image Fields
			'folder_id' => (int)$input['folder_id'],
			'thumbnail_id' => (int)$input['thumbnail_id'],
			// Hidden Fields
			'author_id' => (int)$this->user->id,
			'created_on'=> (int)now()
        ));
		
    }

	/**
	 * Update an existing item
	 * @access public
	 * @param int $id The ID of the item
	 * @param array $input The data to update
	 * @return bool
	 */
    public function update($id, $input)
	{
		return parent::update($id,array(
        	// Content Fields
			'title'=>$input['title'],
        	'slug'=>url_title(strtolower(convert_accented_characters($input['slug']))),
			'listing_status' => $input['listing_status'],
			'group_id'=>(int)$input['group_id'],
			'intro'=>$input['intro'],
			'body'=>$input['body'],
			// Detail Fields
			'listing_id' => $input['listing_id'],
			'listing_type' => (int)$input['listing_type'],
			'listing_price' => $input['listing_price'],
			'listing_size' =>$input['listing_size'],
			'listing_size_m' =>(int)$input['listing_size_m'],
			'listing_lot_size' =>$input['listing_lot_size'],
			'listing_lot_size_m' =>(int)$input['listing_lot_size_m'],
			// Location Fields
			'address_1' => $input['address_1'],
			'address_2' => $input['address_2'],
			'city' => $input['city'],
			'state' => $input['state'],
			'zip' => (int)$input['zip'],
			// Google Maps
			'listing_map_marker' => (int)$input['listing_map_marker'],
			'listing_lat' => (float)$input['listing_lat'],
			'listing_lng' => (float)$input['listing_lng'],
			// Image Fields
			'folder_id' => (int)$input['folder_id'],
			'thumbnail_id' => (int)$input['thumbnail_id'],
			// Hidden Fields
			'author_id' => (int)$this->user->id,
			'updated_on'=> (int)now()
        ));
    }
	
	function count_by($params = array())
	{
		$this->db->select('*');
		$this->db->from('estate');
		$this->db->join('estate_group g', 'estate.group_id = g.id', 'left');
		
		if (!empty($params['group']))
		{
			if (is_numeric($params['group']))
				$this->db->where('g.id', $params['group']);
			else
				$this->db->where('g.slug', $params['group']);
		}

		return $this->db->count_all_results();
	}
	
	function get_many_by($params = array())
	{
		//$this->db->select('blog.*, c.title AS category_title, c.slug AS category_slug');
		
		$this->db->select('estate.* , g.title AS group_title, g.slug AS group_slug');
		//$this->db->from('estate');
		$this->db->join('estate_group g', 'estate.group_id = g.id', 'left');

		if (!empty($params['group']))
		{
			if (is_numeric($params['group']))
				$this->db->where('g.id', $params['group']);
			else
				$this->db->where('g.slug', $params['group']);
		}
		
		// Is a status set?
		if (!empty($params['listing_status']))
		{
			$this->db->where('listing_status', $params['listing_status']);
		}


		// Limit the results based on 1 number or 2 (2nd is offset)
		if (isset($params['limit']) && is_array($params['limit']))
			$this->db->limit($params['limit'][0], $params['limit'][1]);
		elseif (isset($params['limit']))
			$this->db->limit($params['limit']);

		return $this->get_all();
	}
	
}