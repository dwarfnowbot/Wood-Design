#!/usr/bin/env node
/**
 * build-content.mjs
 * ---------------------------------------------------------------------------
 * Extracts the original project's structured content (src/data/*.ts) into the
 * JSON files that are the single source of truth for BOTH:
 *
 *   1. the WordPress theme fallback rendering  (PHP reads the JSON)
 *   2. the generated Elementor page blueprints (Node reads the JSON)
 *
 * The original React/TypeScript project is never modified.
 *
 * Input  : original-source/src/data/{media,siteConfig,services,projects,content}.ts
 * Output : wordpress/theme/maison-woodcraft/inc/content/media-map.json
 *          wordpress/theme/maison-woodcraft/inc/content/site-content.json
 *          (site-content.json is written once and then kept/merged so that the
 *           hand-authored page copy is preserved on re-runs.)
 */

import fs from 'node:fs';
import path from 'node:path';
import vm from 'node:vm';
import { fileURLToPath } from 'node:url';
import { pageCopy, formCopy } from './page-copy.mjs';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = path.resolve(__dirname, '..', '..');
const SRC = path.join(ROOT, 'original-source', 'src', 'data');
const OUT_DIR = path.join(ROOT, 'wordpress', 'theme', 'maison-woodcraft', 'inc', 'content');

function read(file) {
  return fs.readFileSync(path.join(SRC, file), 'utf8');
}

/** Very small, targeted TypeScript → JS reducer for the project's data files. */
function toModuleSource(tsSource) {
  let out = tsSource;
  // Drop import statements (the data files import each other's values).
  out = out.replace(/^import[\s\S]*?from\s+["'][^"']+["'];?$/gm, '');
  // Drop `export type X = { ... };` blocks.
  out = out.replace(/^export type\s+\w+\s*=\s*\{[\s\S]*?^\};?$/gms, '');
  // Drop `export type X = ...;` one liners.
  out = out.replace(/^export type\s+\w+\s*=.*;$/gm, '');
  // Drop type annotations on exported consts.
  out = out.replace(/^export const (\w+)\s*:\s*[\w.<>\[\]"']+(\[\])?\s*=/gm, 'export const $1 =');
  // Drop `as const`.
  out = out.replace(/\bas const\b/g, '');
  // Drop simple arrow-function parameter type annotations, e.g. `(message?: string) =>`.
  out = out.replace(/(\(\s*\w+)\??:\s*[\w<>\[\]|"'\s]+(\s*(?:,\s*\w+\??:\s*[\w<>\[\]|"'\s]+)?\)\s*=>)/g, '$1$2');
  return out;
}

function evaluate(tsSource, sandbox = {}) {
  const js = toModuleSource(tsSource).replace(/^export const /gm, 'globalThis.__exports.');
  const exportsObject = {};
  const context = vm.createContext({
    __exports: exportsObject,
    encodeURIComponent,
    ...sandbox,
  });
  new vm.Script(js, { filename: 'data.js' }).runInContext(context);
  return exportsObject;
}

/* ---------------------------------------------------------------------------
 * 1. media.ts → media map
 * ------------------------------------------------------------------------- */
const images = evaluate(read('media.ts')).images;

/** Human curated alt text (taken from the alt attributes used in the original pages). */
const ALTS = {
  heroKitchen: 'Custom luxury kitchen with warm wood cabinetry and stone countertops',
  introKitchen: 'Bright custom kitchen interior with wooden cabinetry and natural light',
  about: 'Contemporary wood cabinetry detail in a Lahore home interior',
  aboutSecondary: 'Custom kitchen cabinetry detail with warm wood tones',
  'kitchen.0': 'Custom kitchen cabinetry with a warm, contemporary finish',
  'kitchen.1': 'Modern kitchen with integrated storage and a stone counter',
  'kitchen.2': 'Kitchen project interior with sculpted lighting and wood fronts',
  'kitchen.3': 'Custom kitchen island with pendant lighting',
  'kitchen.4': 'Open-plan kitchen with island counter and full-height units',
  'kitchen.5': 'Kitchen with integrated storage and tall units',
  'kitchen.6': 'Custom kitchen designed for a Lahore residence',
  'kitchen.7': 'Contemporary kitchen with warm timber cabinetry',
  'wardrobe.0': 'Custom wardrobe with refined interior detailing',
  'wardrobe.1': 'Built-in wardrobe with organised internal storage',
  'wardrobe.2': 'Custom wardrobe with organised internal shelving',
  'wardrobe.3': 'Custom wardrobe interior detail',
  'wardrobe.4': 'Walk-in wardrobe with shelving and hanging sections',
  'wardrobe.5': 'Wardrobe drawers and internal storage detail',
  'wardrobe.6': 'Bedroom wardrobe with sliding doors',
  'wardrobe.7': 'Wardrobe project with warm wood finish',
  'living.0': 'Living room interior with custom woodwork',
  'living.1': 'Television unit and media wall in wood',
  'living.2': 'Living room with coordinated wood joinery',
  'living.3': 'Elegant custom woodwork interior',
  'living.4': 'Living space with custom joinery and warm lighting',
  'living.5': 'Interior with custom woodwork and soft natural light',
  'living.6': 'Fluted wood panelling on a feature wall',
  'living.7': 'Wall panelling and custom wood features',
  'vanity.0': 'Custom bathroom vanity with stone counter',
  'vanity.1': 'Vanity unit pairing wood cabinetry with stone',
  'vanity.2': 'Custom vanity with integrated storage',
  'vanity.3': 'Bathroom cabinetry with warm wood finish',
  'entryway.0': 'Entryway shoe cabinet in custom woodwork',
  'entryway.1': 'Entryway storage cabinetry with custom joinery',
  'entryway.2': 'Custom entryway unit with concealed storage',
  'study.0': 'Study and workspace unit in custom woodwork',
  'study.1': 'Custom shelving unit with open display',
  'study.2': 'Home office joinery with integrated storage',
};

function altFor(key) {
  return ALTS[key] || 'Custom woodwork interior by Maison Woodcraft';
}

const mediaMap = {};
for (const [key, value] of Object.entries(images)) {
  if (Array.isArray(value)) {
    value.forEach((url, i) => {
      mediaMap[`${key}.${i}`] = { url, alt: altFor(`${key}.${i}`) };
    });
  } else {
    mediaMap[key] = { url: value, alt: altFor(key) };
  }
}

/* ---------------------------------------------------------------------------
 * 2. siteConfig.ts, services.ts, projects.ts, content.ts
 * ------------------------------------------------------------------------- */
const siteConfig = evaluate(read('siteConfig.ts')).siteConfig;
const services = evaluate(read('services.ts'), { images }).services;
const projectsData = evaluate(read('projects.ts'), { images });
const content = evaluate(read('content.ts'));

const mediaKeyOf = (url) => {
  const hit = Object.entries(mediaMap).find(([, v]) => v.url === url);
  return hit ? hit[0] : null;
};

const outDir = OUT_DIR;
fs.mkdirSync(outDir, { recursive: true });

const mediaMapOut = {
  $comment:
    'Image library extracted from the original project (original-source/src/data/media.ts). ' +
    'The theme ships the exact same remote URLs as the original website. Use ' +
    'Appearance → Maison Woodcraft → Import Demo Content (or the media importer in the admin) ' +
    'to copy them into the WordPress Media Library so they become editable/replaceable.',
  images: mediaMap,
};
fs.writeFileSync(path.join(outDir, 'media-map.json'), JSON.stringify(mediaMapOut, null, 2) + '\n');

const contentFile = path.join(outDir, 'site-content.json');

const siteContent = {
  $comment:
    'Content extracted from the original project. `pages` holds the copy that lives inline in the ' +
    'original page components. Regenerate the extracted blocks with: node wordpress/tools/build-content.mjs',
  site: siteConfig,
  nav: [
    { label: 'Home', path: '/' },
    { label: 'Kitchens', path: '/kitchens' },
    { label: 'Wardrobes', path: '/wardrobes' },
    { label: 'Interior Woodwork', path: '/interior-woodwork' },
    { label: 'Projects', path: '/projects' },
    { label: 'About Us', path: '/about' },
    { label: 'Contact', path: '/contact' },
  ],
  footer: {
    blurb:
      'Custom kitchens, wardrobes, and complete home woodwork for homeowners across Lahore — designed around your space and crafted with care.',
    bottomNote: 'Custom Kitchens & Complete Home Woodwork — Lahore, Pakistan',
  },
  services: services.map((s) => ({
    slug: s.slug,
    title: s.title,
    shortDescription: s.shortDescription,
    image: mediaKeyOf(s.image),
    path: s.to,
  })),
  projects: projectsData.projects.map((p) => ({
    id: p.id,
    title: p.title,
    category: p.category,
    location: p.location,
    description: p.description,
    materials: p.materials || '',
    image: mediaKeyOf(p.image),
  })),
  projectCategories: projectsData.projectCategories,
  process: content.processSteps,
  detailedProcess: content.detailedProcessSteps,
  whyChooseUs: content.whyChooseUs,
  trustPoints: content.trustPoints,
  testimonials: content.testimonials,
  materialCategories: content.materialCategories,
  materialsDisclaimer: content.materialsDisclaimer,
  kitchen: {
    styles: content.kitchenStyles,
    features: content.kitchenFeatures,
    faqs: content.kitchenFaqs,
  },
  wardrobe: {
    types: content.wardrobeTypes,
    features: content.wardrobeFeatures,
    faqs: content.wardrobeFaqs,
  },
  interiorWoodwork: {
    categories: content.interiorWoodworkCategories,
  },
  forms: formCopy,
  pages: pageCopy,
};

fs.writeFileSync(contentFile, JSON.stringify(siteContent, null, 2) + '\n');

console.log('media-map.json      :', Object.keys(mediaMap).length, 'images');
console.log('site-content.json   :', Object.keys(siteContent).join(', '));
console.log('projects            :', siteContent.projects.length);
console.log('pages               :', Object.keys(pageCopy).join(', '));
