=== Manage Pages Custom Columns ===
Contributors: scompt
Donate link: http://www.scompt.com/projects/manage-pages-custom-columns-in-wordpress
Tags: manage, edit, pages
Requires at least: 2.2
Tested up to: 2.2
Stable tag: trunk

Replicates the custom column feature of the manage posts page.

== Description ==

Replicates the custom column feature of the manage posts page.  This is in response to the long-standing [enhancement request](http://trac.wordpress.org/ticket/2284).  It provides a `manage_pages_custom_column` action and `manage_pages_columns` filter which can be used similarly to the `manage_posts_custom_column` action and `manage_posts_columns` filter provided by WordPress.

== Installation ==

Just copy the `managepages.php` file to your plugins directory and activate it in WordPress.  You'll then have access to the `manage_pages_custom_column` action and `manage_pages_columns` filter.

Alternatively, you can include the `managepages.php` file with your plugin.  It can then be included using `require_once('managepages.php')` and used just as above.

== Frequently Asked Questions ==

= None yet! =

[Ask a question!](mailto:scompt@scompt.com)

== Screenshots ==

None yet

== Future Plans ==

* None!

== Version History ==

= Version 1.0 =

* Initial work on plugin
