<?php 

# Secure Entry

defined( 'UADMIN_DIR' ) OR DIE;

# Define Class

class Uadmin {
	
	/**
	 * This directory contains css files, javascript files, images and third party libraries
	 * @var string 
	 */
	const ASSETS_DIR = UADMIN_DIR . "/ua-assets";
	
	/**
	 * This directory contain files which are responsible for handling ajax request such as:
	 * @var string
	 * @ignore
	 */
	const AJAX_DIR = UADMIN_DIR . "/ud-ajax";
	
	/**
	 * This directory contain files which helps to render several pages
	 * @var string
	 */
	const PAGES_DIR = UADMIN_DIR . "/ua-pages";
	
	/**
	 * This directory contains important class files and libraries used by user synthetics dashboard
	 * @var string
	 */
	const RES_DIR = UADMIN_DIR . "/ua-resources";
	
	/**
	 * This is where the admin visuality files are contained. Such as:
	 * @var string
	 */
	const VIEW_DIR = UADMIN_DIR . "/ua-views";
	
}