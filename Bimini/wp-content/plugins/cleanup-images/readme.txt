=== Cleanup Images ===
Contributors: delwinv
Donate link: https://www.paypal.com/cgi-bin/webscr?business=donation@delwinvriend.com&cmd=_xclick&currency_code=EUR&item_name=Donation%20for%20Cleanup%20Images%20WordPress%20Plugin
Tags: image, media, library, clean, delete
Requires at least: 3.0.1
Tested up to: 3.9
Stable tag: 1.04
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Cleanup your Media Library and disk storage by finding and deleting unused images in the Media Library.

== Description ==

A plugin to help in deciding which images in the Media Library you want to delete and then
deleting them from both the DB and the directory if you decide to do so. This plugin will
search the database for all images in the Media Library and determine whether or not they
are in use. They will then be listed. If not used, you will be able to select them in order
to delete them.

This plugin finds all standard (out of the box) image usage as well as images used by the
Pods plugin and images referenced in the WordPress options table.

This plugin is based on a partial rewrite and major extension of Delete Not Used Images (DNUI).
In addition to the functionality of that plugin, this plugin:

* accounts for images used by Pods (Pods 2.1+)
* accounts for any images specified in the WP options table
* provides for an image preview on hovering over the image names
* formats the screens in a nicer manner and starts to use standard WP CSS for formatting
* supports translation for most phrases with support for only three phrases still outstanding

Obviously, there is more to come:

* more complete Pods compatibility (table-mode)
* more complete translation support
* more fully styled to WordPress interface style

Any requests for features, please send them to me at [requests@delwinvriend.com](mailto:requests@delwinvriend.com)


== Installation ==

This section describes how to install the plugin and get it working.

1. Unzip the folder clean-up-images from the downloaded zip file into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to the usage screen through the 'Settings => Cleanup Images' menu

== Frequently Asked Questions ==

= Does this plug find all references to the image? =
Since any plugin and any development of a WordPress site can store image references in any number of ways, this
plugin cannot predict them all. This plugin only searches standard WordPress pages and posts, as well as entries
in the WordPress options table and standard usage of Pods 2.1+. It does not currenltly find any other sort of reference
or support the Pods tables option.

= What about Pods being used with their own tables? =
No, this plugin does not currently search Pods created tables for images. Only Pods 2.1 used in the meta-data mode
is supported.

= Can I undelete the images I delete with this plugin? =
No, there is no support to undelete the image. It is permanently removed from the database and the site upload
directory. Please make sure you always have a backup of your database and your site directory before using this
or any other plugin or making any changes on your site. Your data is valuable.

= Why are there child images for each of my uploaded images? =
By default, WordPress generates several sizes of images for each upload (up to three different sizes). If you don't
make use of this default functionality, you can turn it off on the Settings => Media page by setting
each width and height to 0. You'll have less to clean up in the future.

= Why do I get a session error? =
Often, on sites hosted on shared servers, the PHP's settings haven't been completely configured. In php.ini of your
site, you need to ensure that a valid directory is specified for the session data, and that this directory is
writable by the web server/PHP. See your hosting ISP for more information.


== Screenshots ==
1. Shows the cleanup screen before a search for images is performed
2. Shows the image preview feature available once a search is performed
3. Shows the one image not used having been checked using the "Select all..." checkbox
4. Shows the one selected image having been deleted and no longer showing in the list

== Changelog ==

= 1.04 =
* PHP Strict Standards compliance change in usage of array_pop() function. Thank-you overclokk.
* Changed deletion code to report attempted file deletion path if a file was not found to try to debug some file deletion issues.

= 1.03 =
* Updated compatibility to WordPress 3.9

= 1.02 =
* Updated compatibility to WordPress 3.6.1

= 1.0 =
* First public release - no change from 0.11 other than the readme file and site banner image

= 0.11 =
* Fixed pagination issue when deleting all images from the last page

= 0.1 =
* Initial version, used on production sites programmed by Delwin Vriend

== Upgrade Notice ==

= 1.02 =
* Updated compatibility to WordPress 3.6.1. No need to upgrade

= 1.0 =
* First public release - no change from 0.11 other than the readme file and site banner image

= 0.11 =
* Upgrade if you have an issue with the pagination when deleting all images from the last page. This can also be
corrected by simply re-running the query for unused images.

= 0.1 =
* Initial version

