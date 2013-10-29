<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * The galleries module enables users to create albums, upload photos and manage their existing albums.
 *
 * @author 		PyroCMS Dev Team
 * @modified	Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Gallery Module
 * @category 	Modules
 * @license 	Apache License v2.0
 */
class Estate_images_m extends MY_Model
{
	protected $_table = 'estate_images';
	
	/**
	 * Constructor method
	 * 
	 * @author PyroCMS Dev Team
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load all required classes
		//$this->config->load('estate_config');
		//$this->load->library('upload');
		//$this->load->library('image_lib');
	}
	
	/**
	 * Get all gallery images in a folder
	 *
	 * @author PyroCMS Dev Team
	 * @access public
	 * @param int $id The ID of the gallery
	 * @return mixed
	 */
	public function get_images_by_gallery($id)
	{
		// Find new images on files
		$this->set_new_image_files($id);

		// Clear old files on images
		$this->unset_old_image_files($id);

		// Grand finale, do what you gotta do!!
		$images = $this->db
				// Select fields on gallery images table
				->select('estate_images.*, files.name, files.filename, files.extension, files.description, files.name as title, estate.folder_id, estate.slug as estate_slug')
				// Set my gallery by id
				->where('estate.id', $id)
				// Filter images from my gallery
				->join('estate', 'estate.id = estate_images.estate_id', 'left')
				// Filter files from my images
				->join('files', 'files.id = estate_images.file_id', 'left')
				// Filter files type image
				->where('files.type', 'i')
				// Order by user order
				//->order_by('`estate_images`.`order`', 'asc')
				->order_by('`order`', 'asc')
				// Get all!
				->get('estate_images')
				->result();

		return $images;
		
	}

	public function set_new_image_files($estate_id = 0)
	{
		$this->db
			// Select fields on files table
			->select('files.id as file_id, estate.id as estate_id')
			->from('files')
			// Set my gallery by id
			->where('estate.id', $estate_id)
			// Filter files from my gallery folder
			->join('estate', 'estate.folder_id = files.folder_id', 'left')
			// Filter files type image
			->where('files.type', 'i')
			// Not require image files from my gallery in gallery images, prevent duplication
			->ar_where[] = "AND `" . $this->db->dbprefix('files') . "`.`id` NOT IN (SELECT file_id FROM (" . $this->db->dbprefix('estate_images') . ") WHERE `estate_id` = '$estate_id')";

		// Already updated, nothing to do here..
		if ( ! $new_images = $this->db->get()->result())
		{
			return FALSE;
		}

		// Get the last position of order count
		$last_image = $this->db
			->select('`order`')
			->order_by('`order`', 'desc')
			->limit(1)
			->get_where('estate_images', array('estate_id' => $estate_id))
			->row();

		$order = isset($last_image->order) ? $last_image->order + 1: 1;

		// Insert new images, increasing the order
		foreach ($new_images as $new_image)
		{
			$this->db->insert('estate_images', array(
				'estate_id'		=> $new_image->estate_id,
				'file_id'		=> $new_image->file_id,
				'`order`'		=> $order++
			));
		}

		return TRUE;
	}

	public function unset_old_image_files($estate_id = 0)
	{
		$not_in = array();

		// Get all image from folder of my gallery...
		$images = $this->db
			->select('files.id')
			->from('files')
			->join('estate', 'estate.folder_id = files.folder_id')
			->where('files.type', 'i')
			->where('estate.id', $estate_id)
			->get()
			->result();

		if (count($images) > 0)
		{
			foreach ($images AS $item)
			{
				$not_in[] = $item->id;
			}
		
			$this->db
				// Select fields on gallery images table
				->select('estate_images.id')
				->from('estate_images')
				// Set my gallery by id
				->where('estate.id', $estate_id)
				// Filter images from my gallery
				->join('estate', 'estate.id = estate_images.estate_id')
				// Not required images that exists on my files table
				->where_not_in('file_id', $not_in);
	
			// Already updated, nothing to do here..
			if ( ! $old_images = $this->db->get()->result())
			{
				return FALSE;
			}

			// Remove missing files images
			foreach ($old_images as $old_image)
			{
				$this->gallery_image_m->delete($old_image->id);
			}
		}

		return TRUE;
	}
	
	
	/**
	 * Get an image along with the gallery slug
	 * 
	 * @author PyroCMS Dev Team
	 * @access public
	 * @param int $id The ID of the image
	 * @return mixed
	 */
	public function get($id)
	{
		$query = $this->db
			->select('estate_images.*, files.name, files.filename, files.extension, files.description, files.name as title, estate.folder_id, estate.slug as estate_slug')
			->join('estate', 'estate_images.estate_id = estate.id', 'left')
			->join('files', 'files.id = estate_images.file_id', 'left')
			->where('estate_images.id', $id)
			->get('estate_images');
				
		if ( $query->num_rows() > 0 )
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Preview images from folder
	 *
	 * @author Jerel Unruh - PyroCMS Dev Team
	 * @access public
	 * @param int $id The ID of the folder
	 * @param array $options Options
	 * @return mixed
	 */
	public function get_images_by_file_folder($id, $options = array())
	{

		if (isset($options['offset']))
		{
			$this->db->limit($options['offset']);
		}

		if (isset($options['limit']))
		{
			$this->db->limit($options['limit']);
		}

		// Grand finale, do what you gotta do!!
		$images = $this->db
				->select('files.*')
				->where('folder_id', $id)
				->where('files.type', 'i')
				->get('files')
				->result();

		return $images;
	}
	
}