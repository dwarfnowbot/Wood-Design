# Maison Woodcraft — WordPress implementation

This folder contains the complete WordPress implementation of the original
**Maison Woodcraft** website that lives in `../luxury-custom-woodwork-website.zip`
(extracted, untouched, in `../original-source/`).

Nothing about the design was invented: colours, typography, spacing, section
order, copy, images, navigation and forms come straight from the original Vite +
React + TypeScript project. React, Vite, npm and node are **not** used at runtime —
the theme is plain PHP, CSS and one small vanilla JS file.

```
wordpress/
├── theme/maison-woodcraft/      ← the installable theme
├── elementor-templates/         ← importable Elementor templates (header, footer, single project, archive, global kit)
├── tools/                       ← build scripts used to extract the original content
└── maison-woodcraft-theme.zip   ← the finished, installable theme ZIP
```

---

## 1. Install

1. **Theme** — WordPress admin → *Appearance → Themes → Add New → Upload Theme* →
   choose `wordpress/maison-woodcraft-theme.zip (`maison-woodcraft-theme-1.0.2.zip` is the same build with the version in the file name)` → **Activate**
   (or copy `wordpress/theme/maison-woodcraft/` into `wp-content/themes/`).

   > **Upgrading from an earlier copy?** Delete the old theme first
   > (*Appearance → Themes → switch to any other theme → Theme Details → Delete*,
   > or remove `wp-content/themes/maison-woodcraft/` over FTP / cPanel File
   > Manager) and then upload the new ZIP. WordPress merges uploads into an
   > existing folder instead of replacing it, so files that no longer belong to
   > the theme can otherwise stay behind.
2. **Plugins**
   * **Elementor** (free) — required if you want to edit the pages visually.
   * **Elementor Pro** (optional) — required only for the Theme Builder
     (header/footer/single/archive templates).
   * **An SMTP plugin** (WP Mail SMTP, FluentSMTP, Post SMTP, …) — recommended so
     the quote and contact forms are delivered reliably.
   * No page builder, slider, gallery or form plugin is needed: the theme ships
     its own widgets, galleries and working forms.
3. **Demo content** — *Appearance → Maison Woodcraft → Import Demo Content*
   (or `wp mw import-demo`). This creates:
   * the 10 pages with their original titles and slugs, each with an **editable
     Elementor layout** that mirrors the original React page section by section,
   * the 38 photographs of the original site in the Media Library,
   * the 10 sample projects (Projects post type) with category, location,
     materials and gallery,
   * the Primary, Footer Quick Links and Footer Services menus,
   * the static front page and pretty permalinks.

   Importing the photographs requires the server to be able to reach the image
   URLs listed in `inc/content/media-map.json` (the same files the original site
   used). If you would rather upload your own photographs, skip the media step —
   every image is replaceable in Elementor or in the Media Library.

**Requirements:** WordPress 6.0+, PHP 7.4+ (PHP 8.x recommended).

---

## 2. Pages (all converted, none skipped)

| Original route | WordPress page | Template |
| --- | --- | --- |
| `/` | Home (front page) | `front-page.php` → Elementor layout or `mw_render_fallback_page('home')` |
| `/about` | About Us | `page.php` + Elementor layout |
| `/kitchens` | Kitchens | `page.php` + Elementor layout |
| `/wardrobes` | Wardrobes | `page.php` + Elementor layout |
| `/interior-woodwork` | Interior Woodwork | `page.php` + Elementor layout |
| `/projects` | Projects | `page.php` + Elementor layout |
| `/materials-finishes` | Materials & Finishes | `page.php` + Elementor layout |
| `/process` | Process | `page.php` + Elementor layout |
| `/get-a-quote` | Get a Quote | `page.php` + Elementor layout |
| `/contact` | Contact | `page.php` + Elementor layout |
| `*` (NotFound) | — | `404.php` |

Every page keeps the original section order, headings and copy. If Elementor is
not installed, or a page has no Elementor data, `inc/fallback-pages.php` renders
the identical sections natively from `inc/content/site-content.json`.

---

## 3. Editing with Elementor

### Widgets

All original components are available as native Elementor widgets in the
**Maison Woodcraft** category (text, images, links, repeaters, colours and
spacing stay editable — nothing is a hard-coded HTML block):

| Widget | Original component |
| --- | --- |
| Hero (Home / Page) | `pages/Home.tsx` hero + `components/PageHero.tsx` |
| Section Heading | `components/SectionHeading.tsx` |
| Button (all styles) | `components/Button.tsx`, `WhatsAppButton.tsx` |
| Framed Image | the 4:5 / 4:3 / 16:9 / square image frames |
| Image + Text Section | the two-column blocks used on every page |
| Trust Strip | `components/TrustStrip.tsx` |
| Service Cards | `components/ServiceCard.tsx` |
| Image Card Grid | Kitchen styles, Wardrobe types, Woodwork categories |
| Feature Grid | "Why Clients Choose Us", "Our Approach" |
| Checklist | "Features Included" lists |
| Process Timeline | `components/ProcessTimeline.tsx` (horizontal + vertical) |
| Testimonials | `components/TestimonialCard.tsx` |
| Material Swatches | `data/content.ts` materialCategories |
| Gallery Grid | kitchen/wardrobe galleries (with lightbox) |
| FAQ Accordion | Kitchens / Wardrobes FAQs |
| CTA Section | `components/CTASection.tsx` |
| Projects Grid | `components/ProjectCard.tsx` + Projects page filters/modal |
| Project Details | single project fields (category, location, materials, gallery) |
| Quote Request Form | `pages/GetQuote.tsx` form |
| Contact Form | `pages/Contact.tsx` form |
| Contact Details & Map | `pages/Contact.tsx` panels |

Each widget renders through the same template part as the PHP fallback, so an
Elementor page and a fallback page are pixel-identical.

### Header, footer, single project, archive (Elementor Pro)

* The theme registers **all core Elementor locations** (`elementor/theme/register_locations`
  → `register_all_core_location()`), so *Elementor Pro → Theme Builder* can
  replace the header, footer, single post, single project and archive templates.
* Until a template is assigned, the theme's own header
  (`template-parts/header/site-header.php`) and footer
  (`template-parts/footer/site-footer.php`) are used — the original logo
  (brand name + subtitle), nav, quote CTA, sticky/scroll behaviour, mobile
  hamburger and the 4-column footer (brand + socials, Quick Links, Services,
  Contact) with the copyright bar.
* `wordpress/elementor-templates/` contains ready-made templates:
  * `header.json` — logo, nav menu, quote button
  * `footer.json` — 4 columns + copyright bar
  * `single-project.json` — featured image, project details, CTA
  * `projects-archive.json` — heading + projects grid with filters
  * `global-kit.json` — the original palette and font stacks as Site Settings

  Import them from *Elementor → Templates → Saved Templates → Import Templates*.
  The kit is imported from *Elementor → Tools → Import / Export Kit* (or simply
  copy the hex values into *Site Settings → Global Colors / Global Fonts*).

### Design system

The original Tailwind tokens are mirrored as CSS custom properties
(`--mw-ivory`, `--mw-stone`, `--mw-espresso`, `--mw-bronze`, `--mw-champagne`, …)
in `style.css`, together with `.mw-*` classes for buttons, cards, sections and
the layout grid. Elementor containers created by the importer carry the same
padding, container widths (1280/1152/1024/896 px) and background colours as the
original sections, so they can be edited visually without losing the design.

---

## 4. Forms (they really work)

* Quote request form — name, phone, email, project type, budget range, timeline
  stage, location, project requirements + reference image upload.
* Contact form — name, email, phone, message.
* Submission paths:
  * **AJAX** to `wp-json/mw/v1/form` (no page reload, inline success message),
  * **no-JS fallback** to `admin-post.php` (standard POST + redirect back with
    the success/error state).
* Spam protection: nonce, honeypot field, time trap (submissions faster than
  2 seconds are rejected) and a per-IP rate limit (5 per 10 minutes).
* Every submission is stored as a **Form Entry** (*Form Entries* in the admin,
  with all fields, the page it came from and any uploaded files) and emailed to
  the recipient.
* Uploads: up to 8 files, 8 MB each, jpg/png/webp/gif/pdf, stored in the Media
  Library and attached to the entry.
* Configuration:
  * *Customize → Maison Woodcraft → Forms* — recipient address, subject prefix,
    success message,
  * *Customize → Maison Woodcraft → Business Details* — phone, email, address.
* **Email delivery:** WordPress emails come from `wp_mail()`. On most hosts you
  should install an SMTP plugin and connect it to your mailbox or an email
  service. The theme also exposes the filters `mw_form_recipient`,
  `mw_form_email_body` and the actions `mw_form_submitted`,
  `mw_form_email_sent` if you prefer a service integration.

---

## 5. Projects (portfolio)

* Post type **Projects** (`mw_project`, rewrite `/project/…`) with:
  * title, editor content, excerpt, featured image,
  * taxonomy **Project Categories** (`mw_project_cat`),
  * meta box fields: Location, Materials & Finishes, Gallery (multi-select).
* The *Projects Grid* widget/`mw_get_project_cards()` queries the post type, and
  falls back to the ten sample concepts from the original site when no projects
  exist yet — so the design is never empty.
* A single project page is rendered by `single-mw_project.php`
  (featured image, category, title, location, description, materials, gallery
  with lightbox, "All Projects" link, CTA).
* The archive template `archive-mw_project.php` is provided; enable the archive
  with `add_filter( 'mw_project_has_archive', '__return_true' );` in a child
  theme if you want `/project/` as a real archive (the original site used the
  Projects *page* for this, which is what the theme does by default).

---

## 6. WhatsApp

* The floating button (`mw_whatsapp_float()` on `wp_footer`) reproduces
  `components/WhatsAppButton.tsx` — same icon, size, colour `#25D366`, position
  and hover scale.
* Every "Chat on WhatsApp" button builds a real `https://wa.me/<number>?text=…`
  link with a pre-filled message; the number and default message are editable in
  *Customize → Maison Woodcraft → WhatsApp* and per-button in Elementor
  (hero, CTA, contact panels).
* The header shows the phone number, the mobile menu adds a "Call …" button
  using `tel:` links.

---

## 7. Assets, images and fonts

* All 38 photographs are the **original files** (Pexels URLs recorded in
  `inc/content/media-map.json`). `mw_import_images()` copies them into the Media
  Library once and every template resolves the media key to the local attachment
  afterwards — no hotlinking, no stock replacements.
* Images are output through `mw_image_html()` / `wp_get_attachment_image()` with
  `srcset`, `sizes`, `loading="lazy"` (hero images use `loading="eager"` +
  `fetchpriority="high"`), explicit `alt` text from the original data and the
  original `object-fit: cover` cropping.
* Fonts are the original two families — **Cormorant Garamond** and **Jost** —
  loaded with the exact Google Fonts URL and preconnects used by the original
  `index.html`. Self-hosting instructions: download the two families
  ([Google Fonts](https://fonts.google.com/)) into
  `theme/maison-woodcraft/assets/fonts/`, add `@font-face` rules and dequeue
  `mw-fonts` (see `inc/enqueue.php`).
* One stylesheet (`style.css` + `assets/css/components.css`) and one small
  no-dependency script (`assets/js/theme.js`, ~6 KB) replace the Vite bundle:
  header scroll behaviour, mobile menu, FAQ accordion, project filter + modal,
  gallery lightbox and AJAX form submission.

---

## 8. Responsive behaviour

Breakpoints follow the original Tailwind defaults (640 px, 768 px, 1024 px,
1280 px). Navigation collapses into the hamburger panel, two-column sections
reflow to a single column, card grids go 3 → 2 → 1, the hero drops to its mobile
height with smaller type, galleries become a 2-column grid with the feature tile
spanning, tables/panels stack, and the footer columns go 4 → 2 → 1. All Elementor
containers created by the importer also carry tablet/mobile padding, so editing
in Elementor keeps the responsive rhythm.

---

## 9. SEO & performance

* Semantic HTML5 landmarks (`<header>`, `<main>`, `<section>`, `<article>`,
  `<footer>`), one `<h1>` per page, ordered headings.
* `wp_head()` / `wp_footer()`, `title-tag`, `html5` markup, `wp_enqueue_*` only on
  the front end, `preconnect` resource hints, lazy loading, no jQuery dependency,
  no page-builder bloat, no external iframes (the Contact map is the original
  Google Maps embed, loaded lazily — remove the panel in Elementor if you prefer
  not to load it).
* Clean URLs: `/kitchens/`, `/wardrobes/`, `/interior-woodwork/`, `/projects/`,
  `/materials-finishes/`, `/process/`, `/get-a-quote/`, `/contact/`.

---

## 10. Theme structure

```
theme/maison-woodcraft/
├── style.css                     theme header + design system (--mw-* tokens)
├── functions.php                 bootstrap (MW_THEME_VERSION, mw_require)
├── header.php footer.php index.php front-page.php page.php single.php
├── single-mw_project.php archive.php archive-mw_project.php
├── 404.php search.php searchform.php screenshot.png readme.txt
├── assets/css/{components.css,admin.css}
├── assets/js/theme.js
├── inc/
│   ├── setup.php               theme supports, menus, image sizes, body classes
│   ├── enqueue.php             styles, scripts, fonts
│   ├── content.php             site-content.json accessors (mw_content, mw_*)
│   ├── media.php               media map, image helpers, media importer
│   ├── template-tags.php       buttons, icons, WhatsApp, tel/mail links
│   ├── blocks.php              mw_render() + template-part argument helpers
│   ├── customizer.php          business details, WhatsApp, header, forms
│   ├── post-types.php          Projects CPT + taxonomy + meta
│   ├── forms.php               working forms: CPT, REST, admin-post, email
│   ├── fallback-pages.php      native rendering of all ten original pages
│   ├── admin.php               "Maison Woodcraft" setup screen + status
│   ├── elementor/              Elementor integration + 21 widgets
│   ├── demo/                   demo importer + Elementor blueprints
│   └── content/                site-content.json, media-map.json
└── template-parts/
    ├── sections/               hero, heading, split, grids, timeline, faq, cta …
    ├── header/site-header.php  original header (logo, nav, CTA, burger)
    ├── footer/site-footer.php  4-column footer + copyright bar
    ├── forms/                  quote + contact forms
    └── content/content-card.php
```

---

## 11. How the content was extracted

`tools/build-content.mjs` reads the original TypeScript data modules
(`siteConfig.ts`, `media.ts`, `services.ts`, `projects.ts`, `content.ts`) and
writes `inc/content/site-content.json` + `inc/content/media-map.json`.
`tools/page-copy.mjs` holds the page-level copy (the JSX page text) for the same
build. Re-run with:

```bash
node wordpress/tools/build-content.mjs
```

The original project is never modified; the generated JSON is the single source
of truth the PHP templates read at runtime.

---

## 12. QA checklist (verified)

* [x] All eleven original routes exist and render (10 pages + 404).
* [x] No React/Vite/npm/node dependency at runtime — the theme is PHP/CSS/JS only.
* [x] No iframes except the original Google Maps embed on the Contact page.
* [x] Header, footer, nav, mobile menu, buttons and links use real `wp_nav_menu`
      / `home_url()` / `esc_url()` output.
* [x] Quote and contact forms validate, store entries and send email; honeypot,
      time trap and rate limiting are in place.
* [x] WhatsApp button and links are functional `wa.me` links with the number from
      the Customizer.
* [x] Projects are a real post type with archive/grid + single template.
* [x] Every element of the original design is editable: 21 Elementor widgets plus
      the PHP fallback that renders the same markup.
* [x] Theme activates without PHP fatals: the shipped ZIP was installed and
      activated inside a real WordPress install (WordPress master + PHP 8.3 +
      SQLite), the demo importer ran and all eleven URLs rendered with no
      warnings, notices or fatals. Every PHP file also passes a syntax check
      under PHP 8.3 **and** PHP 7.4 (`node tools/lint-php.mjs <theme>`), there are
      no missing functions, no duplicate function definitions and no dead
      template parts.
* [x] Responsive behaviour, image cropping and alt text preserved from the
      original project.

## 13. Troubleshooting

**"There has been a critical error on this website" right after activation.**

1. The site is fine — the error is an uncaught PHP error in the theme. Get the
   exact message from any of these:
   * the *"Your site is experiencing a technical issue"* email WordPress sends
     to the admin address,
   * `wp-content/debug.log` after adding
     `define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true );
     define( 'WP_DEBUG_DISPLAY', false );` to `wp-config.php`,
   * your host's error log (cPanel → *Metrics → Errors*, or the log viewer).
2. Recover access: switch to another theme. If the admin area is also broken,
   rename `wp-content/themes/maison-woodcraft/` to `maison-woodcraft-broken/`
   in cPanel File Manager or over FTP — WordPress then falls back to a default
   theme.
3. Delete the broken folder completely and upload
   `wordpress/maison-woodcraft-theme.zip (`maison-woodcraft-theme-1.0.2.zip` is the same build with the version in the file name)` again through *Appearance → Themes →
   Add New → Upload Theme*.

**Fatal: "Cannot redeclare mw_primary_menu_fallback()"** — this can only happen
with a stale copy of an early build of the theme (`inc/template-tags.php` kept in
place by WordPress's merge-on-upload behaviour). Delete the theme folder and
install the current ZIP; from version 1.0.1 the three menu fallbacks are wrapped
in `function_exists()` guards so a leftover file can never break the site again.

**The pages look like plain text** — Elementor is not active, so the theme is
rendering its own sections instead of the stored Elementor layouts. Install and
activate Elementor, then open any page with *Edit with Elementor*.

**Images are missing after the import** — the server could not download the
original photographs. Upload them into the Media Library and set them as the
featured image / replace the image in Elementor; everything else keeps working.

---

## 14. Uninstall

Deactivating the theme leaves your content, entries and projects untouched.
Deleting it removes the code only; pages, media, projects and form entries stay
in the database, and the original React project remains in `original-source/`.
