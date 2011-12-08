<?php
/*
Plugin Name: Simple Calendar
Plugin URI: http://www.unfocus.com/projects/simple-calendar/
Description: Makes a simple Event System.
Author: unFocus Projects
Author URI: http://www.unfocus.com/
Version: 0.1
License: GPLv2 or later
*/

/*  Copyright 2010-2011  Kenneth Newman  www.unfocus.com

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
class Simple_Calendar
{
	static $file = __FILE__;
	
	function __construct() {
		add_action( 'init', array( __CLASS__, 'register_events' ) );
	}
	
	function register_events() {
		$labels = array(
			'name'               => _x( 'Event',              'simple_event general name',   'simple-calendar' ),
			'singular_name'      => _x( 'Events',             'simple_event singular name',  'simple-calendar' ),
			'add_new'            => _x( 'Add New',            'simple_event',                'simple-calendar' ),
			'add_new_item'       => __( 'Add New Event',      'simple-calendar' ),
			'edit_item'          => __( 'Edit Event',         'simple-calendar' ),
			'new_item'           => __( 'New Event',          'simple-calendar' ),
			'view_item'          => __( 'Veiw Event',         'simple-calendar' ),
			'search_items'       => __( 'Search Events',      'simple-calendar' ),
			'not_found'          => __( 'No Events Found',    'simple-calendar' ),
			'not_found_in_trash' => __( 'No Events in Trash', 'simple-calendar' ),
			'parent_item_colon'  => null,
			//'parent_item_colon'  => __( 'Parent Event:',      'simple-calendar' ), // If hierarchical
			'all_items'          => _x( 'All Events',         'simple-calendar' ),
			'menu_name'          => _x( 'Event',              'simple_event menu name',      'simple-calendar' ),
			'name_admin_bar'     => _x( 'Events',             'simple_event admin bar name', 'simple-calendar' )
		);
		$supports = array(
			'title',
			'thumbnail',
			'editor',
			'comments',
			'revisions'
		);
		$args = array(
			'labels' => $labels,
			'description' => __( 'This is the Event description.', 'simple-calendar' ),
			'public' => true,
			'supports' => $supports,
			'has_archive' => true, 
			'taxonomies' => array( 'category', 'post_tag' ),
			'rewrite' => array( 'slug' => 'event' ),
			'menu_icon' => null
		);
		register_post_type( 'simple_event', $args );
	}
}
new Simple_Calendar();
?>