<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Estate controller
 *
 * @author 		Edward Meehan - wetumka.net
 * @package 	PyroCMS
 * @subpackage 	Estate module
 * @category	Modules
 */
class Admin extends Admin_Controller
{

	/**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
	private $estate_validation_rules = array(
		// Content Fields
		array(
			'field' => 'title',
			'label' => 'lang:estate_admin_title',
			'rules' => 'trim|max_length[50]|required'
		),
		array(
			'field' => 'slug',
			'label' => 'lang:estate_admin_slug',
			'rules' => 'trim|max_length[50]|required'
		),
		array(
			'field' => 'listing_status',
			'label' => 'lang:estate_prop_listing_status',
			'rules' => 'trim'
		),
		array(
			'field' => 'body',
			'label' => 'lang:estate_admin_disc',
			'rules' => 'trim'
		),
		array(
			'field' => 'intro',
			'label' => 'lang:estate_admin_intro',
			'rules' => 'trim'
		),
		array(
			'field' => 'group_id',
			'label' => 'lang:estate_admin_group',
			'rules' => 'trim|max_length[11]|integer'
		),
		// Detail Fields
		array(
			'field' => 'listing_id',
			'label' => 'lang:estate_prop_listing_id',
			'rules' => 'trim|max_length[50]'
		),
		array(
			'field' => 'listing_type',
			'label' => 'lang:estate_prop_listing_type',
			'rules' => 'trim|max_length[11]|integer'
		),
		array(
			'field' => 'listing_price',
			'label' => 'lang:estate_prop_listing_price',
			'rules' => 'trim|max_length[11]|numeric'
		),
		array(
			'field' => 'listing_size',
			'label' => 'lang:estate_prop_listing_size',
			'rules' => 'trim|max_length[11]|numeric'
		),
		array(
			'field' => 'listing_lot_size',
			'label' => 'lang:estate_prop_listing_lot_size',
			'rules' => 'trim|max_length[11]|numeric'
		),
		array(
			'field' => 'listing_size_m',
			'label' => 'lang:estate_prop_listing_size',
			'rules' => 'trim|max_length[11]|numeric'
		),
		array(
			'field' => 'listing_lot_size_m',
			'label' => 'lang:estate_prop_listing_lot_size',
			'rules' => 'trim|max_length[11]|numeric'
		),
		// Location Fields
		array(
			'field' => 'listing_map_marker',
			'label' => 'lang:estate_prop_listing_map_icon',
			'rules' => 'trim|max_length[11]|integer'
		),
		array(
			'field' => 'address_1',
			'label' => 'lang:estate_prop_address_1',
			'rules' => 'trim|max_length[150]'
		),
		array(
			'field' => 'address_2',
			'label' => 'lang:estate_prop_address_2',
			'rules' => 'trim|max_length[150]'
		),
		array(
			'field' => 'city',
			'label' => 'lang:estate_prop_city',
			'rules' => 'trim|max_length[30]'
		),
		array(
			'field' => 'state',
			'label' => 'lang:estate_prop_state',
			'rules' => 'trim|max_length[2]'
		),
		array(
			'field' => 'listing_lat',
			'label' => 'lang:estate_prop_mapclient',
			'rules' => 'trim'
		),
		array(
			'field' => 'listing_lng',
			'label' => 'lang:estate_prop_mapclient',
			'rules' => 'trim'
		),
		array(
			'field' => 'zip',
			'label' => 'lang:estate_prop_zip',
			'rules' => 'trim|max_length[5]|numeric'
		),
		// Images Fields
		array(
			'field' => 'thumbnail_id',
			'label' => 'lang:estate_prop_thumbnail',
			'rules' => 'trim|max_length[11]|integer'
		),
		array(
			'field' => 'folder_id',
			'label' => 'lang:estate_admin_gallery',
			'rules' => 'trim|max_length[11]|integer'
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
		$this->load->model('estate_m');
		$this->load->model('estate_images_m');
		$this->load->model('files/file_folders_m');
		
		$this->config->load('estate_config');
		
		$this->lang->load('estate');
	    
		$this->load->library('form_validation');
		
		$this->load->helper('html');
		
		$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
		
		// Build groups array
		$this->data->estate_groups = array(0 => lang('select.pick'));
		if ($estate_groups = $this->estate_group_m->order_by('id')->get_all())
		{
			foreach ($estate_groups as $group)
			{
				$this->data->estate_groups[$group->id] = $group->title;
			}
		}
		
		// Build type array
		$estate_type = $this->config->item('estate_listing_type');
		
		array_unshift($estate_type,lang('select.pick'));
		
		$this->data->estate_type =& $estate_type;
		
		// Build map marker arrays
		$estate_map_marker_list = $this->config->item('estate_map_marker');

		$estate_map_marker = array();
		$this->estate_map_marker_url = array();
		
		foreach($estate_map_marker_list as $item)
		{
			array_push($estate_map_marker,$item[0]);
			array_push($this->estate_map_marker_url, image_path($item[1],'estate')); 
		}	
		
		array_unshift($estate_map_marker,lang('estate_prop_default'));
		
		$this->data->estate_map_marker =& $estate_map_marker;
		
		// Build status array
		$estate_status = array("draft"=>lang('estate_prop_draft'),"live"=>lang('estate_prop_live'));
		$this->data->estate_status =& $estate_status;
		
		// Build measurement array
		$estate_measure = $this->config->item('estate_listing_measurement');
		
		$this->data->estate_measure =& $estate_measure;
		
		// Build folder id array
		$file_folders = $this->file_folders_m->get_folders();
		$folders_tree = array(lang('select.pick'));
		foreach($file_folders as $folder)
		{
			$indent = repeater('&raquo; ', $folder->depth);
			$folders_tree[$folder->id] = $indent . $folder->name;
		}
		$this->data->folder_options =& $folders_tree;
		
		// Build state array
		$estate_state = $this->config->item('estate_state');
		
		array_unshift($estate_state,lang('select.pick'));
		
		$this->data->estate_state =& $estate_state;
		
		// User currency value from settings
		$this->data->currency = $this->settings->currency;
	}
	
	/**
	 * Index method, lists all property groups
	 * @access public
	 * @return void
	 */
	public function index()
	{		
		// Update empty group text
		$this->data->estate_groups[0] = ' -- ';
		// Using this data, get the relevant results
		$estates = $this->estate_m->get_all();
		
		foreach($estates as $estate)
		{
			
			$estate->image_count =  count($this->estate_images_m->get_images_by_file_folder($estate->folder_id));
		}

		$this->data->estates =& $estates;
		
		$this->template
			->title($this->module_details['name'], lang('estate_prop_list'))
			->append_metadata( css('boxes.css') )
			->build('admin/index', $this->data);
	}
	
	/**
	 * Create method, creates a new property group
	 * @access public
	 * @return void
	 */
	public function create()
	{
		
		// Set the validation rules
		$this->form_validation->set_rules($this->estate_validation_rules);
		
		if ($this->form_validation->run() )
		{
			
			$id = $this->estate_m->insert($_POST);
			
			if ($id)
			{
				$this->session->set_flashdata('success', lang('estate_group_success'));
			}	
			else
			{
				$this->session->set_flashdata('error', lang('estate_group_fail'));
			}
			// Redirect back to the form or main page
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/estate/') : redirect('admin/estate/edit/' . $id);
		}

		// Required for validation
		foreach ($this->estate_validation_rules as $rule)
		{
			$post->{$rule['field']} = $this->input->post($rule['field']);
		}
		
		// Set some data that both create and edit forms will need
		$this->data->post =& $post;
		$this->data->estate_images = FALSE;
		
		$this->template
			->title($this->module_details['name'], lang('estate_prop_create'))
			->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
			->append_metadata('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>' . "\n")
			->append_metadata( js('form.js', 'estate') )
			->append_metadata('<script> estate_type_icon = '.json_encode($this->estate_map_marker_url).'</script>' . "\n")
			->append_metadata( css('estate_images.css', 'estate') )
			->append_metadata( css('boxes.css') )
			->build('admin/form', $this->data);
		
	}
	
	/**
	 * Edit method, edits an existing category
	 * @access public
	 * @param int id The ID of the category to edit 
	 * @return void
	 */
	public function edit($id = 0)
	{	
		$id or redirect('admin/estate/');
		
		// Get the category
		$post = $this->estate_m->get($id);
		
		$estate_images = $this->estate_images_m->get_images_by_gallery($id);
		
		// Set the validation rules
		$this->form_validation->set_rules($this->estate_validation_rules);
		
		// Set update date
		$updated_on = now();
		
		// Validate the results
		if ($this->form_validation->run())
		{		
			
			 $this->estate_m->update($id, $_POST)
			
				? $this->session->set_flashdata('success', sprintf( lang('estate_message_group_update_success'), $this->input->post('title')) )
				: $this->session->set_flashdata(array('error'=> lang('estate_group_update_fail')));
			
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/estate/') : redirect('admin/estate/edit/' . $id);
		}
		
		// Loop through each rule
		foreach($this->estate_validation_rules as $rule)
		{
			if($this->input->post($rule['field']) !== FALSE)
			{
				$post->{$rule['field']} = $this->input->post($rule['field']);
			}
		}

		// Render the view
		$this->data->post =& $post;
		$this->data->estate_images =& $estate_images;
		$this->template
			->title($this->module_details['name'], sprintf(lang('estate_message_group_edit'), $post->title))
			->append_metadata( $this->load->view('fragments/wysiwyg', $this->data, TRUE) )
			->append_metadata('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>' . "\n")
			->append_metadata( js('form.js', 'estate') )
			->append_metadata( js('drag_drop.js', 'estate') )
			->append_metadata('<script> estate_type_icon = '.json_encode($this->estate_map_marker_url).'</script>' . "\n")
			->append_metadata( css('estate_images.css', 'estate') )
			->append_metadata( css('boxes.css') )
			->build('admin/form', $this->data);
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
				if($this->estate_m->delete($id))
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
		
		redirect('admin/estate/');
	}
	
	/**
	 * Sort images in an existing gallery
	 *
	 * @author Jerel Unruh - PyroCMS Dev Team
	 * @access public
	 */
	public function ajax_update_order()
	{
		$ids = explode(',', $this->input->post('order'));

		$i = 1;
		foreach ($ids as $id)
		{
			$this->estate_images_m->update($id, array(
				'order' => $i
			));
			/* USED FOR PREVIEW... but don't use it
			if ($i === 1)
			{
				$preview = $this->estate_images_m->get($id);

				if ($preview)
				{
					$this->db->where('id', $preview->estate_id);
					$this->db->update('galleries', array(
						'preview' => $preview->filename
					));
				}
			}
			*/
			++$i;
		}
	}
	/**
	 * Get images in an existing gallery
	 *
	 * @author Phil Sturgeon - PyroCMS Dev Team
	 * @access public
	 */
	public function ajax_select_folder($folder_id)
	{
		$folder = $this->file_folders_m->get($folder_id);
		
		if (isset($folder->id))
		{
			$folder->images = $this->estate_images_m->get_images_by_file_folder($folder->id);
			
			echo json_encode($folder);
			
			return;
		}
		echo FALSE;
	}
	
}
