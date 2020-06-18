# moodle-filter_imagecannon

An image canonicalization Moodle filter. A what now?

This is a Moodle filter that looks for copies of the same image which have been uploaded multiple times an 'canonicalizes' them so that they all have the same url. This means that your browser will only download them once. Additionally these images will be served as public immutable so they can be cached by a reverse proxy like Varnish or a CDN.

So blast away those pesky duplicates!

