<?php
/**
 * Main file for Manage Pages Custom Columns.  Includes everything needed to
 * enable the 'manage_pages_custom_column' action and the 'manage_pages_column' 
 * filter.  Upon inclusion, all actions/filters will have already been hooked.
 *
 * Usage:
 *    require_once('managepages.php');
 *
 * That's it!
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

if( !class_exists('Services_JSON') )
    require_once('JSON.php');

// If the class is already defined, then we assume that everything is already
// handled and basically do nothing.
if( !class_exists('Scompt_Manage_Pages') ) {

    /**
     * This class enables the 'manage_pages_custom_column' action and the
     * 'manage_pages_column' filter to provide custom columns on the Manage Pages
     * subpanel of the administration screen.  Usage is analageous to that of
     * the 'manage_posts_custom_column' action and the 'manage_pages_column'
     * filter, which can be read about at 
     * http://scompt.com/archives/2007/10/20/adding-custom-columns-to-the-wordpress-manage-posts-screen
     */
    class Scompt_Manage_Pages {
    
        /**
         * Initializes things and hooks onto the load-edit-pages.php action.
         */
        function Scompt_Manage_Pages() {
            add_action( 'load-edit-pages.php', array(&$this, 'load_edit_pages'));

            $this->changes = array('deletions' => array(), 
                                   'additions' => array(),
                                        'data' => array());
        }
    
        /**
         * Figures out all the content that should be added after the page is loaded.
         *
         * This function is called by the load-edit-pages.php action hook.  It applies
         * the manage_pages_columns filter to get the column changes the user wants to 
         * make.  If there are any changes, then the changes are saved to be replayed 
         * by Javascript later.  The data for the columns is then generated and saved
         * also.
         *
         * If anything needs to be done, then the admin_head action is hooked onto.
         */
        function load_edit_pages() {
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
                    $this->changes['deletions'] = $deletions;
                }
                if( $diff = array_diff($columns, $default_columns) ) {
                    // Any column additions can just be saved as is for later
                    $this->changes['additions'] = $diff;
                }

                // Set things up for the header display
                add_action('admin_footer', array(&$this, 'output'));
            	wp_enqueue_script('jquery');

                // The title is always shown in a row, so use the the_title filter
                // to capture the additional details for the row
                add_filter('the_title', array(&$this, 'do_title'));
            }
        }

        /**
         * A dummy filter for the_title that captures additional column info.
         *
         * Called by the the_title filter.  Always returns the parameter that
         * was passed to it.
         */
        function do_title($title) {
            global $post;
            $id = (int) $post->ID;

            foreach( $this->changes['additions'] as $column_name=>$column_display_name) {
                // For each addition, do the 'manage_pages_custom_column' action
                // capturing the results and storing them for the Javascript later
                ob_start();
                do_action('manage_pages_custom_column', $column_name, $id);
                $output = ob_get_clean();
                $this->changes['data'][$id][$column_name] = $output;
            }

            return $title;
        }


        /**
         * Outputs everything to the pages and executes it on page load.
         *
         * Goes through all the data stored in the $changes variable, converts
         * it to JSON and writes it on the page.  On page load, jQuery runs through
         * all the data adding and deleting columns and data.
         *
         * Called by the admin_footer action.
         */
        function output() {
            // Convert everything to JSON
            $json = new Services_JSON();
            $deletions_json = $json->encode($this->changes['deletions']);
            $additions_json = $json->encode(array_reverse($this->changes['additions']));
            $data_json = $json->encode($this->changes['data']);
            // magic number for where a cell should be inserted to account for action buttons
            $position = 5-count($this->changes['deletions']);

            ?>
            <script type="text/javascript">
            //<![CDATA[
                addLoadEvent( function() {
                    <?php
                    echo "var additions=$additions_json;\n";
                    echo "var deletions=$deletions_json;\n";
                    echo "var data=$data_json;\n";            
                    echo "var position=$position;\n";            
                    ?>

                    deletions.forEach(function(x,i,a){
                        x++;
                        jQuery('thead/tr/*:nth-child('+x+')').remove();
                        jQuery('tbody/tr/*:nth-child('+x+')').remove();
                    });
            
                    for( a in additions ) {
                        jQuery('<th>'+additions[a]+'</th>').insertBefore('thead/tr/th:last');
                    }
            
            
                    for( d in data ) {
                        for( e in data[d] ) {
                            jQuery('<td>'+data[d][e]+'</td>').insertBefore('#page-'+d+'/td:nth-child('+position+')');
                        }
                    }
                });
            //]]>
            </script>
            <?php
        }
    }

    // Get things going when the edit-pages.php file is loaded
    new Scompt_Manage_Pages();
}
?>