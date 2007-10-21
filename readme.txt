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

Copy the `managepages` directory to your plugins directory and activate the Manage Pages Custom Columns plugin from WordPress.  You'll then have access to the `manage_pages_custom_column` action and `manage_pages_columns` filter.

Alternatively, you can copy the `managepages.php` and `JSON.php` files to your own plugin and distribute them.  To use, just include `managepages.php` using something like `require_once('managepages.php')` and continue as detailed above.  Don't worry about other plugins including the code also, you're protected.

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
