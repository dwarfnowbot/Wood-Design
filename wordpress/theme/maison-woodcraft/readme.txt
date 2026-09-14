=== Maison Woodcraft ===
Contributors: dwarfnowbot
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Version: 1.0.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: elementor, custom-menu, custom-logo, e-commerce, blog, one-column, two-columns, right-sidebar, translation-ready

A production WordPress theme converted 1:1 from the "Maison Woodcraft" React
website: custom kitchens, wardrobes and complete home woodwork.

== Description ==

Maison Woodcraft is the WordPress theme version of the original Vite + React +
TypeScript website. It keeps the original design system (Cormorant Garamond +
Jost, ivory/stone/espresso/bronze palette), the original page structure and copy,
and it renders every widget with the theme's own template parts.

The theme is built for Elementor:

* every original component (PageHero, SectionHeading, ServiceCard, ProjectCard,
  TestimonialCard, ProcessTimeline, CTASection, TrustStrip, galleries, forms,
  header, footer) exists as a native Elementor widget under the
  "Maison Woodcraft" widget category,
* the header, footer, single project and projects archive can be replaced by
  Elementor Pro Theme Builder templates (all core locations are registered),
* the built-in "Import Demo Content" tool creates the ten pages with editable
  Elementor layouts, the 38 photographs of the original site, ten sample
  projects and the three menus.

The theme also works without Elementor: every page is rendered natively from the
same JSON content, so nothing breaks if the plugin is missing.

== Installation ==

1. Appearance → Themes → Add New → Upload Theme → `maison-woodcraft.zip` → Activate.
2. Install and activate Elementor (and optionally Elementor Pro).
3. Appearance → Maison Woodcraft → Import Demo Content.

== Frequently Asked Questions ==

= Does the theme need Elementor? =

No. Elementor is recommended because it makes every section editable, but the
theme renders the full website on its own.

= Do the forms work? =

Yes. Quote and contact submissions are validated, stored as Form Entries
(including file uploads) and emailed with `wp_mail()`. An SMTP plugin is
recommended for reliable delivery.

= Where is the WhatsApp number set? =

Customize → Maison Woodcraft → WhatsApp (number and default message).

== Copyright ==

Maison Woodcraft WordPress theme, (C) 2026
Released under the terms of the GNU GPL v2 or later.

Photographs: the same image files the original React project referenced.

== Changelog ==

= 1.0.1 =
* Fixed a fatal "Cannot redeclare mw_primary_menu_fallback()" error that could
  occur on sites where an earlier copy of the theme was already installed
  (re-uploading the theme used to keep the old file in place). The three menu
  fallbacks now use function_exists() guards, and the duplicate definitions are
  gone.
* "Import Demo Content" no longer creates a /notfound/ page (the 404 copy is the
  404 template, not a page).
* Projects keep their own photograph when the images could not be copied into
  the Media Library: the original image key is stored on each project and used
  as the featured-image fallback, and project galleries render from attachments
  or image keys.
* Pages fall back to the theme's own sections when Elementor is not active.

= 1.0.0 =
* First release: complete conversion of the original React website.
