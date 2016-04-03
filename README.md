# oik-tip 
* Contributors: bobbingwide, vsgloik
* Donate link: http://www.oik-plugins.com/oik/oik-donate/
* Tags: zip, 7-zip, theme, package, oik-batch, CLI
* Requires at least: 4.3
* Tested up to: 4.5-RC1
* Stable tag: 0.0.1
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description 

oik-tip.php packages the source files for a WordPress theme into a .zip file ready for release to the general public.

The packaging process ensures up to date versions are released

* update the root plugin file
* update the plugin's readme.txt
* build a new version of the README.md file for GitHub
* update language files, if applicable
* reconcile shared library files
* update the "oik-activation" logic

What it does not do:

* Create minimised versions of .css and .js files
* run Unit Tests
* perform translation
* update the API reference


## Installation 
1. Upload the contents of the oik-zip plugin to the `/wp-content/plugins/oik-zip' directory
1. Create a batch file called tip.bat to invoke the oik-tip routine

```
php c:\apache\htdocs\wordpress\wp-content\plugins\oik-tip\oik-tip.php %*
```

## Frequently Asked Questions 

# How does it work? 

Read the code

# What are the dependencies? 

* 7-ZIP
* an editor
* t2m - convert a readme.txt file to README.md ( github.com/bobbingwide/txt2md )

# Does it use Composer? 

No. But it may be enabled for use with Composer

# Why not WP-CLI? 

Now that I need most of WordPress to do all the things I'm working towards using WP-CLI
primarily to handle command line parameters.

# Is it integrated with Git? 

It will be, when I've made more progress with the oik-git shared library.

# Is it integrated with SVN? 

No. Updating the SVN version is currently a manual process performed after creating the .zip and updating GitHub.



## Screenshots 
1. oik-tip in action - not actually taken

## Upgrade Notice 
# 0.0.1 
Now includes the custom.css file.

# 0.0.0 
Finally put under version control.
First version of the plugin, available from GitHub and oik-plugins.

## Changelog 
# 0.0.1 
* Changed: Includes the custom.css file in the .zip file. First part [github bobbingwide oik-tip issue 1]

# 0.0.0 
* Added: First version on GitHub


