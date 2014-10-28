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
        
        /** Creates shortcode */
        add_shortcode( 'services', array( $this, 'services_shortcode' ) );
        
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
    }
    
    /**
    * Creates shortcode to display services on any post or page.
    * 
    * @since 1.0
    * @link https://llama-press.com
    */
    public function services_shortcode( $atts ) {
            
          $atts = shortcode_atts( array(
                    'amount' => '',
                    'orderby' => '',
                    'order' => ''
            ), $atts );
            $amount = $atts['amount'];
            $orderby = $atts['orderby'];
            if( $orderby == "" ) $orderby = 'post_date';
            $order = $atts['order'];
            if( $order == "" ) $order = 'DESC';
            
            if( $amount != '' ){
                $args = array(
                    'post_type' => 'lp-services',
                    'orderby'       => $orderby,
                    'order'         => $order,
                    'posts_per_page' => $amount
                );
            }
            else{
                $args = array(
                    'post_type' => 'lp-services',
                    'orderby'       => $orderby,
                    'order'         => $order,
                );
            }
            
             $id = $post->ID;
             $layout = genesis_site_layout();
             if($layout == "full-width-content"){
                 $classMain = "one-fourth";
                 $num = 4;
             }
             else{
                 $classMain = "one-third";
                 $num = 3;
                 $caption_push = " caption_push ";
             }
             $loop = new WP_Query( $args );
             if( $loop->have_posts() ){
                 //loop through services
                 while( $loop->have_posts() ): $loop->the_post();
                     if( 0 == $loop->current_post || 0 == $loop->current_post % $num )
                     $class = $classMain . ' first';
                     $excerpt = get_the_excerpt();
                     if($excerpt != ""){
                         $text = substr($excerpt, 0, 60);
                     }
                     else{
                            if( get_the_content()){
                                $text = substr(get_the_content, 0, 60);
                            }
                            else{
                                $text = "";
                            }
                     }
                     $content .= "<div class='lp-grid-item lp-service $class'>";
                        $content .= "<div class='lp-service'>";
                            if( has_post_thumbnail( ) ){
                                $content .= get_the_post_thumbnail( get_the_id(), 'lp-services' );
                            }
                            else{
                                $content .= "<img class='attachment-lp-services wp-post-image' src='" . get_stylesheet_directory_uri() . "/img/grid-bg.png' alt='background' />";
                            }
                            $content .= "<strong><a href='" . get_the_permalink() . "'>" . get_the_title() . "</a></strong>";
                            $content .= "<div class='lp-caption'>";
                                $content .= "<div class='caption_info$caption_push'>";
                                    $content .= "<p class='lp-hidden-md'>" . $text . "</p>";
                                    $content .= "<a class='lp-btn lp-btn-white' href='". get_the_permalink() ."'>Read More&nbsp;&nbsp;<i class='fa fa-arrow-circle-right'></i></a>";
                                $content .= "</div>";
                            $content .= "</div>";
                        $content .= "</div>";
                     $content .= "</div>";
                     $class = $classMain;
                 endwhile;
                 
                $content .= "<div class='clearfix'></div>";
             } 
             wp_reset_postdata();
              
             
             if( $content )
             return $content;
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