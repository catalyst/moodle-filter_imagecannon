# moodle-filter_imagecannon

![image](https://user-images.githubusercontent.com/187449/84968362-a401ab80-b159-11ea-8b03-f708a323bc19.png)

An image canonicalization Moodle filter. A what now?

This is a Moodle filter that looks for copies of the same image which have been uploaded multiple times an 'canonicalizes' them so that they all have the same url. This means that your browser will only download them once. Additionally these images will be served as public immutable so they can be cached by a reverse proxy like Varnish or a CDN.

So blast away those pesky duplicates!

