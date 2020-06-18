<a href="https://www.catalyst-au.net/"><img align=right alt="Catalyst IT" src="https://cdn.rawgit.com/CatalystIT-AU/moodle-auth_saml2/master/pix/catalyst-logo.svg" width="150"></a>

<a href="https://travis-ci.org/catalyst/moodle-filter_imagecannon">
<img src="https://travis-ci.org/catalyst/moodle-filter_imagecannon.svg?branch=master">
</a>

# moodle-filter_imagecannon

<img align=right width=400 src="https://user-images.githubusercontent.com/187449/84968362-a401ab80-b159-11ea-8b03-f708a323bc19.png" >

* [What is this?](#what-is-this)
* [Branches](#branches)
* [Installation](#installation)
* [Configuration](#configuration)
* [Support](#support)
* [Warm thanks](#warm-thanks)

What is this?
-------------

An image canonicalization Moodle filter. A what now?

This is a Moodle filter that looks for copies of the same image which have been uploaded multiple times an 'canonicalizes' them so that they all have the same url. This means that your browser will only download them once. Additionally these images will be served as public immutable so they can be cached by a reverse proxy like Varnish or a CDN.

So blast away those pesky duplicates!

Branches
--------

| Moodle verion      | Branch      | PHP  |
| -----------------  | ----------- | ---- |
| Moodle 3.5 to 3.9+ | master      | 7.0+ |
| Totara 12+         | master      | 7.0+ |

Installation
------------

1. Install the plugin the same as any standard moodle plugin either via the
Moodle plugin directory, or you can use git to clone it into your source:

   ```sh
   git clone git@github.com:catalyst/moodle-filter_imagecannon.git filter/imagecannon
   ```

   Or install via the Moodle plugin directory:
    
   https://moodle.org/plugins/moodle-filter_imagecannon (TBA)


Configuration
-------------

The filter only has a couple simple options:

* Duplicity - how many times should a file be duplicated in the File API for it to be
  considered a public image and for the filter to take effect

* File areas - Not all file areas are supported by this filter


Support
-------

If you have issues please log them in github here

https://github.com/catalyst/moodle-filter_imagecannon/issues

Please note our time is limited, so if you need urgent support or want to
sponsor a new feature then please contact Catalyst IT Australia:

https://www.catalyst-au.net/contact-us


Warm thanks
-----------

This plugin was developed by Catalyst IT Australia:

https://www.catalyst-au.net/

<a href="https://www.catalyst-au.net/"><img alt="Catalyst IT" src="https://cdn.rawgit.com/CatalystIT-AU/moodle-auth_saml2/master/pix/catalyst-logo.svg" width="400"></a>
