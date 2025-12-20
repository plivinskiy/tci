=== Embed Google Photos album ===
Contributors: pavex
Donate link: https://www.publicalbum.org/blog/about-pavex
Tags: Google Photos, Embed Google Photos, Wordpress carousel, Carousel slideshow, Embed gallery
Requires at least: 5.0
Tested up to: 6.4.3
Requires PHP: 5.3
License: GPLv2 or later

Embed Google Photos album using Player widget.

== Description ==

This plugin requires a shared Google Photos album link to view photos using gallery/player or carousel. This widget is free to use for Wordpress users.

It is very easy to use. Just write a **shortcode** to your post and include a link of shared Google Photos album as a parameter. Instead of shortcode, html code with links to the photo will be inserted and it will be decorated using Public album javascript.


== Example ==

`[embed-google-photos-album link="https://photos.app.goo.gl/CSV7NDstShTUwUZq5"]`

`[embed-google-photos-album link="https://photos.app.goo.gl/CSV7NDstShTUwUZq5" mode="carousel"]`

`[embed-google-photos-album link="https://photos.app.goo.gl/CSV7NDstShTUwUZq5" mediaitems-cover="true"]`

`[embed-google-photos-album link="https://photos.app.goo.gl/CSV7NDstShTUwUZq5" background-color="#007acc"]`

- **link** - [string] public link of Google Photos album
- **mode** - [carousel | gallery-player] setup decorator mode, default id `gallery-player`
- **width** - [int | 'auto'] set widget width in pixel or "auto" to stretch to 100%
- **height** - [int | 'auto'] set widget height in pixels or "auto" to stretch to 100%
- **image-width** - [int] image max-width in pixels, default is 1920
- **image-height** - [int] image max-height in pixels, default is 1080
- **autoplay** - [true | false] start slideshow in normal view (currently not allowed by decorator)
- **delay** - [true | false] slideshow delay in seconds, default is 5 seconds.
- **repeat** - [true | false] Enable or disable repeat slideshow, delfault is `true`
- **mediaitems-aspectration** - [true | false], Keep asspect ration of images delfault is `true`
- **mediaitems-enlarge** - [true | false], Turn on/off image enlarge, delfault is `true`
- **mediaitems-stretch** - [true | false], Tunr on/off image stretch, delfault is `true`
- **mediaitems-cover** - [true | false], Cover full canvas. Combine with aspect ratio parameter. Delfault is `false`
- **background-color** - [#RRGGBB | transparent], Setup background color to RGB or transparent. Default is `#000000`
- **expiration** - [int] setup expiration timeout in secons; default is 0; min. custom value is 86400s (experimental property)

This widget, unsupported some features like a picture's timestamp and captions. For a more detailed description of the component, go to [Wordpress Google Photos album plugin](https://www.publicalbum.org/blog/wordpress-google-photos-album-plugin).

In some cases, it may be better to use the following code directly in the template.

`<?php
	echo (new Pavex_embed_google_photos_album()) -> getcode(
		'https://photos.app.goo.gl/CSV7NDstShTUwUZq5', 0, 480, 1920, 1080
	);
?>
`


== How do I update my album? ==

The album will update automatically as soon as you save or update your post.



=== Javascript decorator ===

External javascript decorator is stored on CDN and loading and running of them is **optimized for performance**.

Javascript widget can be used without photos from Google photos. Detailed information on how to use javascript is available in one of older posts about [carusel slideshow](https://www.publicalbum.org/blog/carousel-slideshow-gallery-widget-july-update) on my blog.



== About Public album photo sharing website ==

**Public album** is now a [photo sharing website](https://www.publicalbum.org/blog/photo-sharing-website) and service for sharing short photosets available in single user profile.

The service can also be used as an alternative to [public Google Photos](https://www.publicalbum.org/blog/public-google-photos). It is very suitable for the blogs focused mainly on photos. For example, a **photoblog** or a [**online bookmark manager**](https://www.reabr.com).



== Important links ==

- **Public album sharing website** [https://www.publicalbum.org](https://www.publicalbum.org)
- **Blog** [https://www.publicalbum.org/blog](https://www.publicalbum.org/blog)
- **Facebook** [https://www.facebook.com/publicalbumapp](https://www.facebook.com/publicalbumapp)
- **Reabr.com** [online bookmark manager](https://www.reabr.com)



== Screenshots ==

1. Result of this Wordpress plugin - gallery player with Mr. Monstro album
2. Preview of source Google Photos shared album
3. Similar photoset with Mr. Monstro in Public album photo sharing website.
4. Another photosets like a blog in Public album.



== Changelog ==

= 2.2.1 =
*Release Date - 19 March 2024*

* preg_match pattern upgrade
* Wordpress 6.4.3 test

= 2.2 =
*Release Date - 7 March 2024*

* security fix

= 2.1.9 =
*Release Date - 20 July 2023*

* fix missing link attr

= 2.1.8 =
*Release Date - 9 June 2023*

* fix object close tag
* fix version count

= 2.1.7 =
*Release Date - 7 June 2023*

* fix img element, replaced by object
* Wordpress 6.2.2 test

= 2.1.6 =
*Release Date - 19 March 2023*

* Wordpress 6.1.1 test

= 2.1.5 =
*Release Date - 7 September 2022*

* Wordpress 6.0.2 test

= 2.1.4 =
*Release Date - 7 September 2022*

* Fixed inconsistency with get_transient and expiration

= 2.1.3 =
*Release Date - 11 May 2022*

* Worpress 5.9 test 

= 2.1.2 =
*Release Date - 24 August 2020*

* add background-color property
* Readme sections about js and photo sharing website.
* Worpress 5.5 test 

= 2.1.1 =
*Release Date - 24 April 2020*

* repair expiration property
* Worpress 5.4 test 

= 2.1.0 =
*Release Date - 12 July 2019*

* use embed-ui.min.js decorator
* Carousel or Gallery player support
* slideshow parameters shorten names
* mediaitems parameters for image setup
* function getcode() accept complex props

= 2.0.9 =
*Release Date - 4 June 2019*

* load javascript in the footer

= 2.0.8 =
*Release Date - 2 June 2019*

* set_transient expiration support

= 2.0.7 =
*Release Date - 10 December 2018*
 
* getcode() for templates inline php

= 2.0.6 =
*Release Date - 21 November 2018*
 
* wp_remote_get

= 2.0.5 =
*Release Date - 15 November 2018*
 
* fix to use the latest version of decorator

= 2.0.4 =
*Release Date - 10 November 2018*
 
* using decorator from cdn.jsdelivr.net service

= 2.0.3 =
*Release Date - 9 November 2018*
 
* fix bug with widget size.
* set widget dimensions to int or 'auto' to stretch to parent element size
* remove stretch

= 2.0.2 =
*Release Date - 25 October 2018*
 
* rename and add new properties to setup slideshow. 

= 2.0.1 =
*Release Date - 19 August 2018*
 
* imageWidth/imageHeight options to set better quality of images. 

= 2.0.0 =
*Release Date - 9 August 2018*

* Based on simple grabber of the album. Not backward compatibility with previous versions.

= 0.9 =

* Proxy of Publicalbum.org embeds iframe.
