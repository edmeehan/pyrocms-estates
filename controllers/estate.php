<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Estate extends Public_Controller
{
	public $limit = 5; // TODO: PS - Make me a settings option
	
	public function __construct()
	{
		parent::__construct();		
		// Load the required classes
		$this->load->model('estate_m');
		$this->load->model('estate_images_m');
		$this->load->model('estate_group_m');
		$this->lang->load('estate');
		$this->load->helper('html');
		$this->config->load('estate_config');
		$this->template
			->append_metadata( js('geo.js','estate') );
		//$this->output->enable_profiler(TRUE);
		
		// Build map marker array
		$estate_map_marker_list = $this->config->item('estate_map_marker');

		$this->estate_map_marker_url = array();
		
		foreach($estate_map_marker_list as $item)
		{
			array_push($this->estate_map_marker_url, image_path($item[1],'estate')); 
		}
	}
	
	/**
	 * Default listing
	 */
	public function index()
	{	
		$this->data->estate_groups = $this->estate_group_m->get_all_groups();

		$this->template
			//->title($this->module_details['name'])
			->set_breadcrumb(lang('estate_default_header'))
			->build('index', $this->data);
	}
	
	/**
	 * View a list of property listings
	 * Default view: Group
	 */
	public function listing($slug = NULL)
	{	
		$slug OR redirect('estate');
		
		// Get category data
		$group = $this->estate_group_m->get_by('slug', $slug) or show_404();
		
		// Count total estate listings and work out how many pages exist
		$pagination = create_pagination('estate/'.$slug, $this->estate_m->count_by(array(
			'group'=>$slug
		)), NULL, 4);
		
		// Get the current page of blog posts
		$estates = $this->estate_m->limit($pagination['limit'])->get_many_by(array(
			'group'=> $slug,
			'listing_status' => 'live'
		));
			
		$this->data->estates =& $estates;
		$this->data->pagination =& $pagination;
		$this->data->group =& $group;
		$this->data->currency = $this->settings->currency;

		$this->template
			//->title($this->module_details['name'])
			->set_breadcrumb(lang('estate_default_header'),'estate')
			->set_breadcrumb($group->title)
			->append_metadata('<script> map_array = '.json_encode($estates).'</script>' . "\n")
			->append_metadata('<script> estate_type_icon = '.json_encode($this->estate_map_marker_url).'</script>' . "\n")
			->append_metadata('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>' . "\n")
			->append_metadata( css('estate.css', 'estate') )
			->build('listing', $this->data);
	}
	
	/**
	 * View a single property listing
	 */
	public function detail($group_slug = NULL, $estate_slug = NULL)
	{
		// Got the required variables?
		if ( empty($group_slug) OR empty($estate_slug) )
		{
			redirect('estate');
		}
		
		// Build type array
		$estate_type = $this->config->item('estate_listing_type');
		array_unshift($estate_type,'');
		
		// Build measurement units array
		$measure_units = $this->config->item('estate_listing_measurement');
		
		$group = $this->estate_group_m->get_by('slug', $group_slug) or show_404();
		$estate = $this->estate_m->get_by('slug', $estate_slug) or show_404();
		$estate_images = $this->estate_images_m->get_images_by_gallery($estate->id);
		
		//print_r($estate_type);
		$this->data->measure_units =& $measure_units;
		$this->data->estate_type =& $estate_type;
		$this->data->group =& $group;
		$this->data->estate =& $estate;
		$this->data->estate_images =& $estate_images;
		$this->data->currency = $this->settings->currency;
		
		$this->template
			//->title($this->module_details['name'])
			->set_breadcrumb(lang('estate_default_header'),'estate')
			->set_breadcrumb($group->title, 'estate/' . $group->slug)
			->set_breadcrumb($estate->title)
			->append_metadata('<script> map_array = '.json_encode($estate).'</script>' . "\n")
			->append_metadata('<script> estate_type_icon = '.json_encode($this->estate_map_marker_url).'</script>' . "\n")
			->append_metadata('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>' . "\n")
			->append_metadata( css('estate.css', 'estate') )
			->build('detail', $this->data);
	}

}