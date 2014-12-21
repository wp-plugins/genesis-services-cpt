<?php
/**
 * Plugin Name: Genesis Servies CPT
 * Plugin URI: https://llama-press.com
 * Description: Use this plugin to add a Services CPT to be used with the "services" sortcode or a LlamaPress services page template,
 *              this plugin can only be used with the Genesis framework.
 * Version: 1.0
 * Author: LlamaPress
 * Author URI: https://llama-press.com  
 * License: GPL2
 */

/*  Copyright 2014  LlamaPress LTD  (email : info@llama-press.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//include plugins
include( plugin_dir_path( __FILE__ ) . 'inc/plugins/plugins.php');

/**
 * This class creates a custom post type lp-services, this post type allows the user to create 
 * services to display in the services page template.
 *
 * @since 1.0
 * @link https://llama-press.com
 */
class lpServices {
    /**
    * Initiate functions.
    *
    * @since 1.0
    * @link https://llama-press.com
    */
    public function __construct( ){
        
        /** Create testimonial custom post type */
        add_action( 'genesis_init', array( $this, 'services_post_type' ) );
        
        /** Creates testimonials featured image for archive grid */
        add_image_size( 'lp-services', 330, 230, TRUE );
        
        /* create text domain */
        load_plugin_textdomain( 'lp', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
        
        /* Add Genesis layout options */
        add_post_type_support( 'lp-services', 'genesis-layouts' );
        
        /* check what template to use for the single display */
        add_filter( 'single_template', array( $this, 'get_custom_post_type_template' ) );

    }

    /**
    * Creates lp-services custom post type.
    * 
    * @since 1.0
    * @link https://llama-press.com
    */
    public function services_post_type() {
        register_post_type( 'lp-services',
            array(
                'labels' => array(
                    'name' => __( 'Services', 'lp' ),
                    'singular_name' => __( 'Service', 'lp' ),
                    'all_items' => __( 'All Services', 'lp' ),
                    'add_new' => _x( 'Add new Service', 'Service', 'lp' ),
                    'add_new_item' => __( 'Add new Service', 'lp' ),
                    'edit_item' => __( 'Edit Service', 'lp' ),
                    'new_item' => __( 'New Service', 'lp' ),
                    'view_item' => __( 'View Service', 'lp' ),
                    'search_items' => __( 'Search Services', 'lp' ),
                    'not_found' =>  __( 'No Services found', 'lp' ),
                    'not_found_in_trash' => __( 'No Services found in trash', 'lp' ), 
                    'parent_item_colon' => ''
                ),
                'exclude_from_search' => true,
                'has_archive' => true,
                'hierarchical' => true,
                'public' => true,
                'menu_icon' => 'dashicons-admin-generic',
                'rewrite' => array( 'slug' => 'services' ),
                'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'page-attributes', 'genesis-seo' ),
            )
        );
        flush_rewrite_rules(); 
    }    
    
    /**
    * Loads the correct template.
    * 
    * Checks to see if the current theme has a template titled single-lp-service, if it does then that file is used and if not then the plugin template file is used.
    * 
    * @since 1.0
    * @link https://llama-press.com
    */
    public function get_custom_post_type_template($single_template) {
         global $post;

         $located = locate_template( 'single-lp-service.php' );
         if ($post->post_type == 'lp-services' && empty( $located )) {
              $single_template = dirname( __FILE__ ) . '/single-service.php';
         }
         return $single_template;
    }
}
 
$services = new lpServices();

?>