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
	const POST_TYPE = 'simple_event';
	const VERSION = '0.1';
	static $file = __FILE__;
	
	function __construct() {
		add_action( 'init', array( __CLASS__, 'register_events' ) );
		add_filter( 'post_updated_messages', array( __CLASS__, 'post_updated_messages' ) );
	}
	function post_updated_messages( $messages ) {
		global $post, $post_ID;
		
		$messages[ self::POST_TYPE ] = array(
			 0 => '', // Unused. Messages start at index 1.
			 1 => sprintf( __( 'Event updated. <a href="%s">View event</a>' ), esc_url( get_permalink( $post_ID ) ) ),
			 2 => __( 'Custom field updated.' ),
			 3 => __( 'Custom field deleted.' ),
			 4 => __( 'Event updated.' ),
				/* translators: %s: date and time of the revision */
			 5 => isset( $_GET[ 'revision' ] )
				? sprintf( __( 'Event restored to revision from %s' ), wp_post_revision_title( (int) $_GET[ 'revision' ], false ) )
				: false,
			 6 => sprintf( __( 'Event published. <a href="%s">View event</a>' ), esc_url( get_permalink( $post_ID ) ) ),
			 7 => __( 'Event saved.' ),
			 8 => sprintf( __( 'Event submitted. <a target="_blank" href="%s">Preview event</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			 9 => sprintf( __( 'Event scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview event</a>' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
			10 => sprintf( __( 'Event draft updated. <a target="_blank" href="%s">Preview event</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		);
		
		return $messages;
	}
	
	function register_events() {
		$labels = array(
			'name'               => _x( 'Events',             'post type general name',  'simple-calendar' ),
			'singular_name'      => _x( 'Event',              'post type singular name', 'simple-calendar' ),
			'add_new'            => _x( 'Add New',            'post type',               'simple-calendar' ),
			'add_new_item'       => __( 'Add New Event',      'simple-calendar' ),
			'edit_item'          => __( 'Edit Event',         'simple-calendar' ),
			'new_item'           => __( 'New Event',          'simple-calendar' ),
			'view_item'          => __( 'Veiw Event',         'simple-calendar' ),
			'search_items'       => __( 'Search Events',      'simple-calendar' ),
			'not_found'          => __( 'No Events Found',    'simple-calendar' ),
			'not_found_in_trash' => __( 'No Events in Trash', 'simple-calendar' ),
			'parent_item_colon'  => null,
			'all_items'          => _x( 'All Events',         'simple-calendar' ),
			'menu_name'          => _x( 'Events',             'post type menu name',     'simple-calendar' ),
			'name_admin_bar'     => _x( 'Event',              'add new on admin bar',    'simple-calendar' )
		);
		$supports = array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'trackbacks',
			'custom-fields',
			'comments',
			'revisions'
		);
		$args = array(
			'labels'               => $labels,
			'supports'             => $supports,
			'description'          => __( 'An Event for the Simple Calendar.', 'simple-calendar' ),
			'public'               => true,
			'has_archive'          => true,
			'rewrite'              => array( 'slug' => 'event' ),
			'taxonomies'           => array( 'category', 'post_tag' ),
			'register_meta_box_cb' => array( __CLASS__, 'add_meta_box' ),
			'menu_icon'            => null
		);
		register_post_type( self::POST_TYPE, $args );
	}
	
	function add_meta_box() {
		add_meta_box(
			'simple_calendar',
			__( 'Simple Calendar', 'simple-calendar' ),
			array( __CLASS__, 'admin_meta_box' ),
			self::POST_TYPE,
			'normal',
			'high',
			''
		);
		
		add_filter( 'default_hidden_meta_boxes', array( __CLASS__,  'default_hidden_meta_boxes' ), 10, 2  );
		add_action( "admin_print_styles", array( __CLASS__, 'meta_box_styles'));
		add_action( "admin_print_scripts", array( __CLASS__, 'meta_box_scripts'));
		add_filter( 'contextual_help', array( __CLASS__, 'help' ) );
	}
	static function admin_meta_box( $post ) {
		?>
			<div>
				<p>Hello</p>
				<input class="simple-datepicker" type="date" />
				<input class="" type="time" />
			</div>
		<?php
	}
	
	static function default_hidden_meta_boxes( $hidden, $screen ) {
		//$hidden[] = 'simple_calendar';
		$hidden[] = 'trackbacksdiv';
		$hidden[] = 'postcustom';
		$hidden[] = 'postexcerpt';
		$hidden[] = 'commentstatusdiv';
		$hidden[] = 'commentsdiv';
		$hidden[] = 'authordiv';
		$hidden[] = 'revisionsdiv';
		//$hidden[] = 'tagsdiv-post_tag';
		//$hidden[] = 'categorydiv';
		//$hidden[] = 'postimagediv';
		
    	return $hidden;
	}
	static function meta_box_styles() {
		wp_enqueue_style( 'jquery-ui-smoothness', plugins_url( 'css/smoothness/jquery-ui-1.8.16.custom.css', self::$file), array(), '1.8.16' );
	}
	static function meta_box_scripts() {
		wp_enqueue_script( 'simple-calendar', plugins_url( 'js/simple-calendar.js', self::$file), array( 'jquery-ui-datepicker' ), '0.1' );
		//wp_localize_script( 'simple-calendar-meta-box-scripts', 'simple_event', array( 'something' => $important ) );
	}
	function help() {
		global $wp_version; // Back Compat for now
		if ( version_compare( $wp_version, '3.2.1', '>') ) {
			get_current_screen()->add_help_tab( array(
				'title' => __( 'Scripts n Styles', 'simple-calendar' ),
				'id' => 'simple-calendar',
				'content' =>
					'<p>' . __( 'Some help text.', 'simple-calendar' ) . '</p>'
				)
			);
			get_current_screen()->set_help_sidebar(
				'<p><strong>' . __( 'For more information:', 'simple-calendar' ) . '</strong></p>' .
				'<p>' . __( '<a href="http://wordpress.org/extend/plugins/simple-calendar/faq/" target="_blank">Frequently Asked Questions</a>', 'simple-calendar' ) . '</p>' .
				'<p>' . __( '<a href="https://github.com/unFocus/Simple-Calendar" target="_blank">Source on github</a>', 'simple-calendar' ) . '</p>' .
				'<p>' . __( '<a href="http://wordpress.org/tags/simple-calendar" target="_blank">Support Forums</a>', 'simple-calendar' ) . '</p>'
			);
		}
	}
}
new Simple_Calendar();
?>