<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package  	PyroCMS
 * @subpackage  Categories
 * @category  	Module
 * @author  	Edward Meehan
 */
class Admin_Group extends Admin_Controller
{
	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	private $group_validation_rules = array(
		array(
			'field' => 'title',
			'label' => 'lang:estate_admin_title',
			'rules' => 'trim|max_length[255]|required'
		),
		array(
			'field' => 'slug',
			'label' => 'lang:estate_admin_slug',
			'rules' => 'trim|max_length[255]|required'
		),
		array(
			'field' => 'file_id',
			'label' => 'lang:estate_admin_img',
			'rules' => 'trim'
		),
		array(
			'field' => 'body',
			'label' => 'lang:estate_admin_disc',
			'rules' => 'trim'
		)
	);
	
	/** 
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('estate_group_m');
		$this->lang->load('estate');
	    $this->config->load('estate_config');
		$this->load->library('form_validation');
		
		$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
	}
	
	/**
	 * Index method, lists all property groups
	 * @access public
	 * @return void
	 */
	public function index()
	{		
		// Using this data, get the relevant results
		$groups = $this->estate_group_m->get_all();
		
		$this->data->groups =& $groups;
		$this->template
			->title($this->module_details['name'], lang('estate_group_list'))
			->build('admin/group/index', $this->data);
	}
	
	/**
	 * Create method, creates a new property group
	 * @access public
	 * @return void
	 */
	public function create()
	{
		$group_images = $this->estate_group_m->get_group_images($this->config->item('estate_group_folder'));
		$this->data->group_images =& $group_images;
		
		
		// Set the validation rules
		$this->form_validation->set_rules($this->group_validation_rules);

		if ($this->form_validation->run() )
		{
			$id = $this->estate_group_m->insert($_POST);
			if ($id)
			{
				$this->session->set_flashdata('success', lang('estate_group_success'));
			}	
			else
			{
				$this->session->set_flashdata('error', lang('estate_group_fail'));
			}
			// Redirect back to the form or main page
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/estate/group') : redirect('admin/estate/group/edit/' . $id);
		}

		// Required for validation
		foreach ($this->group_validation_rules as $rule)
		{
			$post->{$rule['field']} = $this->input->post($rule['field']);
		}
		
		// Set some data that both create and edit forms will need
		$this->data->post =& $post;
		$this->data->group_images =& $group_images;
		$this->template
			->title($this->module_details['name'], lang('estate_group_create'))
			->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
			->append_metadata( js('form.js', 'estate') )
			->build('admin/group/form', $this->data);
	}
	
	/**
	 * Edit method, edits an existing category
	 * @access public
	 * @param int id The ID of the category to edit 
	 * @return void
	 */
	public function edit($id = 0)
	{	
		$id or redirect('admin/estate/group/index');
		
		$group_images = $this->estate_group_m->get_group_images($this->config->item('estate_group_folder'));
		$this->data->group_images =& $group_images;
		
		// Get the category
		$post = $this->estate_group_m->get($id);
		
		// Set the validation rules
		$this->form_validation->set_rules($this->group_validation_rules);
		
		// Validate the results
		if ($this->form_validation->run())
		{		
			$this->estate_group_m->update($id, $_POST)
				? $this->session->set_flashdata('success', sprintf( lang('estate_message_group_update_success'), $this->input->post('title')) )
				: $this->session->set_flashdata(array('error'=> lang('estate_group_update_fail')));
			
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/estate/group') : redirect('admin/estate/group/edit/' . $id);
		}
		
		// Loop through each rule
		foreach($this->group_validation_rules as $rule)
		{
			if($this->input->post($rule['field']) !== FALSE)
			{
				$post->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		// Render the view
		$this->data->post =& $post;
		$this->template
			->title($this->module_details['name'], sprintf(lang('estate_message_group_edit'), $post->title))
			->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
			->build('admin/group/form', $this->data);
	}		

	/**
	 * Delete method, deletes an existing group
	 * @access public
	 * @param int id The ID of the group to delete 
	 * @return void
	 */
	public function delete($id = 0)
	{	
		$id_array = (!empty($id)) ? array($id) : $this->input->post('action_to');
		
		// Delete multiple
		if(!empty($id_array))
		{
			$deleted = 0;
			$to_delete = 0;
			foreach ($id_array as $id) 
			{
				if($this->estate_group_m->delete($id))
				{
					$deleted++;
				}
				else
				{
					$this->session->set_flashdata('error', sprintf($this->lang->line('estate_message_delete_fail'), $id));
				}
				$to_delete++;
			}
			
			if( $deleted > 0 )
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('estate_message_delete_success'), $deleted, $to_delete));
			}
		}		
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('estate_group_delete_none'));
		}
		
		redirect('admin/estate/group/index');
	}
		
	/**
	 * Callback method that checks the title of an post
	 * @access public
	 * @param string title The Title to check
	 * @return bool
	 */

	public function _check_title($title = '')
	{

		if ($this->estate_group_m->check_title($title))
		{
			$this->form_validation->set_message('_check_title', sprintf(lang('estate_message_exist'),$title, strtolower(lang('estate_admin_title'))));
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Callback method that checks the slug of an post
	 * @access public
	 * @param string slug The Slug to check
	 * @return bool
	 */
	 
	public function _check_slug($slug = '')
	{

		if ($this->estate_group_m->check_slug($slug))
		{
			$this->form_validation->set_message('_check_slug', sprintf(lang('estate_message_exist'),$slug, strtolower(lang('estate_admin_slug'))));
			return FALSE;
		}

		return TRUE;
	}
	
}