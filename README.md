# Kent Blog Theme
A new responsive, on-brand theme for WordPress blogs at blogs.kent.ac.uk

## Usage
The Kent Blog Theme is free and distributed under the GNU GPL version 2 or later. This repository contains the license, which is also available from the [GNU website](http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html).

## Installing
Download and install to the your themes folder within wp-content.

To install via composer see [composer installers](https://github.com/composer/installers) and add the following lines to your composer.json

```

	{
	   "repositories": [
		 { 
           "type":"package",
           "package": {
             "name": "unikent/blog-theme-kent",
             "type": "wordpress-theme",
             "version":"master",
             "source": {
                 "url": "https://github.com/unikent/blog-theme-kent",
                 "type": "git",
                 "reference":"master"
               }
           }
         }
	   ],
	   "require":{
	    "unikent/blog-theme-kent":"dev-master"
	   }
	}
   
```


## Developing
The theme will load compressed and minified JS, and compiled minified CSS by default.

To load prettier development versions instead set the `WP_ENV` php constant to *development* or *local*.

This is best done in your wp-config.php file

**This is done for you automagically on blogs-test.ac.uk**

## Creating A Build
The theme assets are pre-built however there is a grunt task to rebuild if developing.

1. Install Node.js - this includes npm by default.

2. Install Grunt globally - its quite useful! `npm install -g grunt` or `npm install -g grunt-cli` for the cli version. 

4. Install the dependencies of our Grunt task - `npm install` from the themes directory.

3. Run Grunt - `grunt dev` from the theme root for development assets, or `grunt build` for production.


## Customising
There are currently few customisation options available.


### Blog Header Image
Access the customizer from *Appearance -> Customize* in the wordpress dashboard

The heaader image is an option under *Site Title &amp; Tagline*.

**Images should be at least 1200px x 480px.**
