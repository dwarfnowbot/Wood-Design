# Wood-Design — Maison Woodcraft

The **Maison Woodcraft** website: a luxury custom-woodwork studio site
(custom kitchens, wardrobes and complete home woodwork, Lahore).

This repository holds both the original source and its WordPress conversion.

```
.
├── luxury-custom-woodwork-website.zip     the original delivery (untouched)
├── original-source/                       that ZIP, extracted — the design & content source of truth
├── wordpress/                             the WordPress implementation
│   ├── theme/maison-woodcraft/            the installable theme
│   ├── elementor-templates/               importable Elementor templates + global kit
│   ├── tools/                             content extraction + lint scripts
│   ├── maison-woodcraft-theme.zip         ready-to-install theme ZIP
│   └── README.md                          install / setup / usage instructions
└── README.md                              this file
```

## The original project (`original-source/`)

A Vite + React + TypeScript single-page site built with Tailwind:

* 11 routes — Home, About, Kitchens, Wardrobes, Interior Woodwork, Projects,
  Materials & Finishes, Process, Get a Quote, Contact, NotFound (404)
* data modules: `siteConfig.ts`, `services.ts`, `projects.ts`, `content.ts`, `media.ts`
* design system in `src/index.css` (Tailwind v4 tokens): ivory/stone/espresso/
  walnut/bronze palette, Cormorant Garamond + Jost
* components: Header, Footer, Layout, PageHero, SectionHeading, ServiceCard,
  ProjectCard, TestimonialCard, ProcessTimeline, CTASection, TrustStrip,
  WhatsAppButton, ScrollToTop, galleries, forms

It is kept exactly as delivered and is **not** modified by the conversion.

## The WordPress theme (`wordpress/`)

`wordpress/theme/maison-woodcraft/` is a complete, production-ready WordPress
theme that reproduces the original design, structure, copy and imagery without
React, Vite, npm or node at runtime:

* every original route becomes a real WordPress page (plus a 404 template),
* every original component becomes a native **Elementor widget** (21 of them) and
  a matching PHP template part, so pages stay fully editable,
* the header, footer, single project and archive templates can be replaced from
  Elementor Pro's Theme Builder,
* quote and contact forms are functional (validation, spam protection, entries in
  the admin, file uploads, `wp_mail()` + SMTP guidance),
* the WhatsApp float/buttons are real `wa.me` links with a customizer setting,
* projects are a real post type with categories, location, materials and gallery,
* a one-click importer recreates the ten pages (with editable Elementor layouts),
  the 38 original photographs, the ten sample projects and the menus.

### Quick start

1. Install `wordpress/maison-woodcraft-theme.zip` (or `maison-woodcraft-theme-1.0.3.zip`, the same build with the version in the file name) in WordPress
   (*Appearance → Themes → Add New → Upload Theme*) and activate it.
2. Install **Elementor** (free; Elementor Pro optional for the Theme Builder).
3. *Appearance → Maison Woodcraft → Import Demo Content*
   (or `wp mw import-demo`).
4. Optionally import the templates and global kit from `wordpress/elementor-templates/`.

Full documentation: [`wordpress/README.md`](wordpress/README.md).

### Rebuilding the content JSON

The PHP templates read the theme's content from
`wordpress/theme/maison-woodcraft/inc/content/site-content.json`, generated from
the original TypeScript data modules:

```bash
node wordpress/tools/build-content.mjs    # site-content.json + media-map.json
node wordpress/tools/lint-php.mjs wordpress/theme/maison-woodcraft   # PHP 8.3 syntax check
```

## License

Theme code: GPL v2 or later (as required by WordPress).
Photographs: the same files the original project referenced.
