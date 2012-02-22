<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Categories model
 *
 * @package		PyroCMS
 * @subpackage	Categories Module
 * @category	Modules
 * @author		Edward Meehan
 */
class Estate_group_m extends MY_Model
{
	protected $_table = 'estate_group';
	
	/**
	 * Insert a new group into the database
	 * @access public
	 * @param array $input The data to insert
	 * @return string
	 */
	public function insert($input = array())
    {
    	return parent::insert(array(
        	'title'=>$input['title'],
			'body'=> $input['body'],
			'file_id' => $input['file_id'],
        	'slug'=>url_title(strtolower(convert_accented_characters($input['slug'])))
        ));
    }
    
	/**
	 * Update an existing group
	 * @access public
	 * @param int $id The ID of the group
	 * @param array $input The data to update
	 * @return bool
	 */
    public function update($id, $input)
	{
		return parent::update($id, array(
            'title'	=> $input['title'],
			'body' => $input['body'],
			'file_id' => $input['file_id'],
            'slug'	=>url_title(strtolower(convert_accented_characters($input['slug'])))
		));
    }
	
	/**
	 * Get all galleries along with the total number of photos in each gallery
	 *
	 * @author Yorick Peterse - PyroCMS Dev Team
	 * @access public
	 * @return mixed
	 */
	public function get_all_groups()
	{
		/*$this->db
			->select('estate_group.*');
			//->join('file_folders ff', 'ff.id = galleries.folder_id', 'left');*/

		$estate_groups	= parent::get_all();
		$results	= array();

		// Loop through each gallery and add the count of photos to the results
		foreach ($estate_groups as $estate_group)
		{
			/*$count = $this->db
				->select('f.id')
				->join('galleries g', 'g.folder_id = f.folder_id', 'left')
				->where('f.type', 'i')
				->where('g.id', $gallery->id)
				->count_all_results('files f');

			$gallery->photo_count = $count;*/
			$results[] = $estate_group;
		}

		// Return the results
		return $results;
	}
	
	public function get_group_images($folder_name = '')
	{
		$images = $this->db
			->select('files.id,files.filename,files.name,files.description')
			//->from('files')
			->join('file_folders ff', 'files.folder_id = ff.id', 'left')
			->where('ff.name',$folder_name)
			->get('files')
			->result();

		return $images;
	}
	
	/**
	 * Callback method for checking value in table
	 * @access public
	 * @param string : title or slug
	 * @return bool
	 */
	public function check_title($title = '')
	{
		return parent::count_by('title', url_title($title)) > 0;
	}
	
	public function check_slug($slug = '')
	{
		return parent::count_by('slug', url_title($slug)) > 0;
	}
	
}