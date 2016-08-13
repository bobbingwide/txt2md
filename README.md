# txt2md 
![banner](https://raw.githubusercontent.com/bobbingwide/txt2md/master/assets/txt2md-banner-772x250.jpg)
* Contributors: bobbingwide, vsgloik
* Donate link: http://www.oik-plugins.com/oik/oik-donate/
* Tags: readme, convert, WordPress, GitHub
* Requires at least: 4.3
* Tested up to: 4.6-RC2
* Stable tag: 0.0.1
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description 
Convert a WordPress readme.txt file to Github README.md

When you're packaging a WordPress plugin or theme that's released to wordpress.org and GitHub
then you need a Read Me file created in GitHub flavoured Mark Down.

This simple routine does that.
Use it as part of your package routine.

This routine also inserts a banner image at the top of the README.md file.

## Installation 
1. Upload the contents of the txt2md plugin to the `/wp-content/plugins/txt2md' directory
1. Create a batch file
1. Use it to convert a readme.txt file to a README.md file

Don't activate this as a plugin. It's not intended to be run in your WordPress website
but as part of a batch process.

```
php \apache\htdocs\wordpress\wp-content\plugins\txt2md\txt2md.php
```

## Frequently Asked Questions 

# How does it work? 

Read the code

# What are the dependencies? 

None

## Screenshots 
1. txt2md in action - not actually taken

## Upgrade Notice 
# 0.0.1 
Now supports display of a banner image.

# 0.0.0 
New plugin, available from GitHub and oik-plugins.

## Changelog 
# 0.0.1 
* Added: Logic to display a banner image. Assumes the $owner is bobbingwide.

# 0.0.0 
* Added: New plugin on GitHub

