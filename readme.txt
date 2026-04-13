=== Satori Post Carousel ===
Contributors: stevemason
Tags: carousel, posts, slider, block, gutenberg
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 8.2
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An accessible, configurable Gutenberg block that displays posts from any public post type in a carousel.

== Description ==

Satori Post Carousel adds a single Gutenberg block (`satori-digital/post-carousel`) that renders an accessible, touch-friendly carousel from any registered public post type.

**Features**

* Works with any public post type — posts, pages, custom types
* Configurable: number of posts, featured image, title, excerpt, and read-more link
* Fully accessible: follows the ARIA Authoring Practices Guide Carousel Pattern
* Keyboard navigable (ArrowLeft / ArrowRight)
* Touch swipe support
* Screen-reader friendly: live region, slide labels, dot indicators with roles
* Respects block editor colour, spacing, and typography supports
* Dynamic block — no content stored in the database beyond the block comment
* Zero runtime JavaScript dependencies

== Installation ==

1. Upload the `satori-post-carousel` folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. In the block editor, search for **Post Carousel** and insert it.

== Frequently Asked Questions ==

= Can I use this with custom post types? =

Yes. Any registered public post type will appear in the **Post type** dropdown in the block inspector.

= How do I change the carousel styles? =

Override the CSS custom properties in your theme:

    .satori-post-carousel {
        --spc-image-ratio: 4 / 3;
        --spc-btn-size: 3rem;
        --spc-gap: 2rem;
    }

= What is the maximum number of posts? =

20 posts per carousel instance.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
