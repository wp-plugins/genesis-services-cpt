<?php
/**
 * Single service template.
 *
 * This template is only used if the theme does not have a template titled "single-lp-service dot php", all it does is remove the post info, meta and author box.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

// remove post info, post meta, and title from cpt
add_action ('get_header', 'remove_post_info');


function remove_post_info() {
	if ('lp-services' == get_post_type()) {
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
                remove_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 );
		}
}

genesis();
?>