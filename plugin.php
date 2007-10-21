<?php
/*
Plugin Name: Manage Pages Custom Columns
Plugin URI: http://www.scompt.com/projects/manage-pages-custom-columns-in-wordpress
Description: Replicates the custom column feature of the manage posts page.
Author: Edward Dale
Version: 1.0-beta1
Author URI: http://www.scompt.com
*/

require_once('managepages.php');

// add_action('manage_pages_custom_column', 'ed_custom_column', 10, 2);
// function ed_custom_column($column_name, $id) {
//     global $post;
//     
//     if( $column_name == 'woo_id' ) {
//       echo $id;  
//     } else if( $column_name == 'blah' ) {
//         var_dump( $post);
//     }
// }
// 
// add_filter( 'manage_pages_columns', 'ed_test');
// function ed_test($defaults) {
//     unset($defaults['id']);
//     unset($defaults['title']);
//     $defaults['woo_id'] = 'Woo ID';
//     $defaults['blah'] = 'Page';
//     return $defaults;
// }

?>