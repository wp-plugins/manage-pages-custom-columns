=== Manage Pages Custom Columns ===
Contributors: scompt
Donate link: http://www.scompt.com/projects/manage-pages-custom-columns-in-wordpress
Tags: manage, edit, pages, admin, plugin
Requires at least: 2.2
Tested up to: 2.3
Stable tag: trunk

Replicates the custom column feature of the Manage Posts page for the Manage
Pages page.  Obsolete as of WP 2.4.

== Description ==

This plugin is obsolete as of WordPress 2.4.

Replicates the custom column feature of the manage posts page.  This is in response to the long-standing [enhancement request](http://trac.wordpress.org/ticket/2284).  It provides a `manage_pages_custom_column` action and `manage_pages_columns` filter which can be used similarly to the `manage_posts_custom_column` action and `manage_posts_columns` filter provided by WordPress.

A tutorial on [how to add custom columns to the manage posts screen](http://scompt.com/archives/2007/10/20/adding-custom-columns-to-the-wordpress-manage-posts-screen) can be viewed on my website.

== Installation ==

Copy the `managepages` directory to your plugins directory and activate the Manage Pages Custom Columns plugin from WordPress.  You'll then have access to the `manage_pages_custom_column` action and `manage_pages_columns` filter.

Alternatively, you can copy the `managepages.php` and `JSON.php` files to your own plugin and distribute them.  To use, just include `managepages.php` using something like `require_once('managepages.php')` and continue as detailed above.  Don't worry about other plugins including the code also, you're protected.

== Frequently Asked Questions ==

= None yet! =

[Ask a question](mailto:scompt@scompt.com)

== Screenshots ==

1. The Manage Pages screen with two additional columns: the [Zensor](http://www.scompt.com/projects/zensor) moderation status and post attachments.

== Future Plans ==

* None, [suggest something!](mailto:scompt@scompt.com)

== Version History ==

= Version 1.0 =

* Initial work on plugin
