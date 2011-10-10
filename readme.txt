=== Plugin Name ===
Contributors: Godwinh Lopez (gwinh.dev[at]gmail.com)
Tags: seo, anchor text, hyperlink
Requires at least: 2.6
Tested up to: 3.2.1
Stable tag: 1.0.1

Plugin that automatically converts a text/keyword in a post or page into an anchor text.

== Description ==

Plugin that automatically converts a text/keyword in a post or page into an anchor text.
Just Add the text/keyword in the backend of G Auto Hyperlink section and add the following:
* Text/Keyword
* URL
* Title
* Rel
* Target
* Appearance (on specific post, on specific page, on specific category, on all posts, on all pages, on all posts and pages)

Note: The appearance is an easy configuration feature. Here's how it works, once you have added a keyword and specified its appearance
on a specific category, on a post under that category, it will search for the keyword entered and hyperlinked it. 
 
If you find any bug or want to make suggestions, email me at gwinh.dev[at]gmail.com

== Installation ==
1. Unzip the file and upload it in `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Now you can manage your links under Auto-Hyperlink Menu

== Frequently Asked Questions ==

= Does it replace words that are the same but different cases? =

Yes, I made the search feature case insensitive but replacements are not. For instance your keyword is "hyperlink" and
it finds a "Hyperlink", it will then replace "Hyperlink" with a "Hyperlink" too.

= If I have added a keyword "hyperlink" with an appearance of specific category and I have added a "hyperlink2" with an appearance of specific post, what configuration will be used? =

I have set a priority for that feature and the order of priorities are:
1. Specific Post
2. Specific Page
3. Specific Category
4. All Posts
5. All Pages
6. All Posts and Pages

For your case, the configuration that will be used is the "hyperlink2"

= What if I have added "hyperlink" under a specific category (category1) and "hyperlink2" under a category2 still, what configuration will be used?  =

For that case, it will randomly pick from the two configuration. Same thing goes with two configuration under a specific post or page, it will
randomly pick from configurations added.

= How many instance of the keyword will it replace? =

It will only replace 2 instances of the keyword.

== Changelog ==

= 1.0.1 =

Replaced the wordpress function plugins_url() to WP_CONTENT_URL and WP_CONTENT_DIR in getting the path/url of images or template files.