<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Estate extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Estate Listings'
			),
			'description' => array(
				'en' => 'Real-Estate Listing Module'
			),
			'frontend' => TRUE,
			'backend'  => TRUE,
			'skip_xss' => TRUE,
			'menu'	  => 'content',
			'author' => 'Edward Meehan'
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('estate');
		$this->dbforge->drop_table('estate_group');
		$this->dbforge->drop_table('estate_images');
				
		$estate = "
			CREATE TABLE `estate` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `slug` varchar(50) NOT NULL,
			  `title` varchar(50) NOT NULL,
			  `intro` text,
			  `body` text,
			  `group_id` int(11) DEFAULT 0,
			  `address_1` varchar(150),
			  `address_2` varchar(150),
			  `city` varchar(30) NOT NULL,
			  `state` char(2) NOT NULL,
			  `zip` int(5),
			  `listing_lat` float(10,6),
			  `listing_lng` float(10,6),
			  `folder_id` int(11),
			  `thumbnail_id` int(11),
			  `listing_id` varchar(50),
			  `listing_price` varchar(11),
			  `listing_size` varchar(11),
			  `listing_lot_size` varchar(11),
			  `listing_type` int(11),
			  `listing_status` int(11),
			  `created_on` int(11) NOT NULL,
			  `updated_on` int(11) NOT NULL DEFAULT 0,
			  `author_id` int(11) NOT NULL,
			  PRIMARY KEY  (`id`)                     
           	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Estate Properties.';
		";
		
		$estate_group = "
			CREATE TABLE `estate_group` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `slug` varchar(255) NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `file_id` int(11),
			  `body` text,
			  PRIMARY KEY  (`id`)                       
           	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Estate Property Groups.';
		";
		
		$estate_images = "
			CREATE TABLE `estate_images` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `file_id` int(11),
			  `estate_id` int(11),
			  `order` int(11) DEFAULT 0,
			  PRIMARY KEY  (`id`)                       
           	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Estate Property Images.';
		";
		
		if($this->db->query($estate) &&
		   $this->db->query($estate_group) &&
		   $this->db->query($estate_images))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		if($this->dbforge->drop_table('estate') &&
		   $this->dbforge->drop_table('estate_group') &&
		   $this->dbforge->drop_table('estate_images'))
		{
			return TRUE;
		}
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.";
	}
}
/* End of file details.php */