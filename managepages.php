<?php
/*
Plugin Name: Manage Pages Custom Columns
Plugin URI: http://www.scompt.com/projects/manage-pages-custom-columns-in-wordpress
Description: Replicates the custom column feature of the manage posts page.
Author: Edward Dale
Version: 1.0-beta1
Author URI: http://www.scompt.com
*/

/**
 * Main plugin file for Zensor which initializes and includes all other files
 *
 * LICENSE
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Manage Pages Custom Columns
 * @author     Edward Dale <scompt@scompt.com>
 * @copyright  Copyright 2007 Edward Dale
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt LGPL 3.0
 * @version    $Id$
 * @link       http://www.scompt.com/projects/manage-pages-custom-columns-in-wordpress
 * @since      1.0
 */

function scompt_load_page() {
    global $scompt_changes;
    $scompt_changes = array();
    
    // Defaults copied from edit-pages.php line 34
    $default_columns = array('id' => __('ID'),
                          'title' => __('Title'),
                          'owner' => __('Owner'),
                        'updated' => __('Updated'));

    // Let the user change the list
    $columns = apply_filters('manage_pages_columns', $default_columns);
    
    // Check if they made any changes at all first.  If not, then we're done.
    if( $default_columns !== $columns ) {
        if( $diff = array_diff($default_columns, $columns) ) {
            // Grab the deleted columns and save the reverse-sorted indices for later
            $deletions = array();
            foreach( $diff as $diff_key=>$diff_value ) {
                $deletions []= array_search($diff_key, array_keys($default_columns));
            }
            rsort($deletions);
            $scompt_changes['deletions'] = $deletions;
        }
        if( $diff = array_diff($columns, $default_columns) ) {
            // Any column additions can just be saved as is for later
            $scompt_changes['additions'] = $diff;
        }

        // Set things up for the header display
        add_action('admin_head', 'scompt_head');
    	wp_enqueue_script('jquery');

        // Run the same wp function call as edit-pages does, with actions added
        // so the data can be grabbed as it happens.
        add_action('wp', 'scompt_wp');
    	wp('post_type=page&orderby=menu_order&what_to_show=posts&posts_per_page=-1&posts_per_archive_page=-1&order=asc');
        remove_action('wp', 'scompt_wp');
    }
}

function scompt_wp($the_wp) {
    global $posts;
    
    // From edit-pages.php line 24
    if ( $_GET['s'] )
    	$all = false;
    else
    	$all = true;

    if ($posts) {
        scompt_page_rows(0, 0, $posts, $all);
    }
}

function scompt_page_rows( $parent = 0, $level = 0, $pages = 0, $hierarchy = true ) {
	global $wpdb, $class, $post, $scompt_changes;

	if (!$pages )
		$pages = get_pages( 'sort_column=menu_order' );

	if (! $pages )
		return false;

	foreach ( $pages as $post) {
		setup_postdata( $post);
		if ( $hierarchy && ($post->post_parent != $parent) )
			continue;

		$post->post_title = wp_specialchars( $post->post_title );
		$pad = str_repeat( '&#8212; ', $level );
		$id = (int) $post->ID;
		$class = ('alternate' == $class ) ? '' : 'alternate';

        foreach( $scompt_changes['additions'] as $column_name=>$column_display_name) {
            ob_start();
            do_action('manage_pages_custom_column', $column_name, $id);
            $output = ob_get_clean();
            $scompt_changes['data'][$id][$column_name] = $output;
        }

		if ( $hierarchy ) scompt_page_rows( $id, $level + 1, $pages );
	}
}

function scompt_head() {
    global $scompt_changes;

    // magic number for where a cell should be inserted to account for action buttons
    $position = 5-count($scompt_changes['deletions']);
    ?>
            <script type="text/javascript">
            //<![CDATA[
              jQuery.noConflict();
                addLoadEvent( function() {
    <?php
    if( !empty($scompt_changes['deletions'] ) ) {
        foreach( $scompt_changes['deletions'] as $deletion ) {
            $deletion++; // 0-based to 1-based
            echo "jQuery('thead/tr/*:nth-child($deletion)').remove();\n";
            echo "jQuery('tbody/tr/*:nth-child($deletion)').remove();\n";
        }
    }
    if( !empty($scompt_changes['additions'])) {
        foreach( $scompt_changes['additions'] as $name=>$display_name ) {
            echo "jQuery('<th>$display_name</th>').insertBefore('thead/tr/th:last');\n";
        }
    }
    if( !empty($scompt_changes['data'])) {
        foreach( $scompt_changes['data'] as $post_id=>$new_data ) {
            foreach( array_reverse($new_data) as $field=>$value ) {
                echo "jQuery('<td>$value</td>').insertBefore('#page-$post_id/td:nth-child($position)');\n";
            }
        }
    }
    
    ?>
            });
        //]]>
        </script>
    <?php
}


add_action( 'load-edit-pages.php', 'scompt_load_page');
?>