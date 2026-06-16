# Mrittika

> **মৃত্তিকা** — "earth / soil." A monochrome, Material Expressive 3 WordPress theme
> built for data-driven real-estate publishing.

Mrittika is the open-source theme behind [Bengal Property Index](https://bengalpropertyindex.in)
— *"The Number Behind West Bengal Property."* It is designed for editorial property
journalism: price guides, district reports, circle-rate trackers, buyer guides, and
data tables. The aesthetic is deliberately restrained — black, white, and shades of
gray — in the spirit of editorial blogs from Google, Apple, and Bloomberg.

![Theme: black & white editorial](.github/preview.png)

## Highlights

- **Monochrome by design** — a full neutral tonal ramp; zero accent color.
- **Material Expressive 3 tokens** — fluid type, generous shape radii, shadow presets,
  spring/emphasized motion curves, plus block style variations (M3 Card, Outline, Tonal
  buttons, Pull Quote, Checklist…), all in CSS custom properties.
- **Poppins + Inter** webfonts (display + body) with system-stack fallback, one-click
  disable for max speed / privacy.
- **Light / dark mode** — follows the device, with a persisted manual toggle and no
  flash of the wrong theme on load.
- **Dedicated settings dashboard** — a tabbed admin page (General · Homepage · Design · Ads ·
  SEO · Performance · Security · Tools) backed by the Settings API; no code needed to configure.
- **Dynamic homepage controls** — toggle the featured hero, the "Explore our Topics"
  slider, and the "Our Archives" button; set the number of hero posts and topics, and
  rename the section headings — all from the **Homepage** tab.
- **Explore our Topics slider** — a draggable (mouse) / swipeable (touch) strip of
  top-level categories with optional per-category images; fully crawlable, no hidden duplicates.
- **Infinite-scroll blog home** — loads every published post as you scroll, with a
  progressively-enhanced, crawlable paginated fallback for search engines.
- **FAQ builder** — a repeatable per-post metabox (question + answer + optional AI-citation),
  an M3 Expressive accordion on the front end, and automatic `FAQPage` JSON-LD.
- **One-click thumbnail regeneration** — rebuild every image to the theme's 3:2 crops and
  800×800 category tiles from **Tools**, batched so it never times out.
- **Google AdSense ready** — validated Publisher ID, optional Auto Ads, four policy-safe
  ad slots (after-header, in-content, after-post, sidebar), labeled containers, virtual
  `/ads.txt`. Ads never render inside navigation.
- **Security hardening** — security headers (incl. HSTS), XML-RPC disable, user-enumeration
  blocking, generic login errors, `DISALLOW_FILE_EDIT`, hardened comment links. All toggleable.
- **SEO-first** — meta description, canonical, Open Graph, Twitter cards, verification
  meta, and JSON-LD (WebSite, Organization, Article, BreadcrumbList). **Yoast-friendly**:
  built-in SEO auto-disables when Yoast / Rank Math / SEOPress / AIOSEO is active.
- **Reading experience** — auto table of contents with scrollspy, reading-progress bar,
  reading time, related posts, copy-link, back-to-top, View Transitions.
- **Mobile-first & accessible** — semantic landmarks, skip link, keyboard-friendly nav,
  `prefers-reduced-motion` support, AA-contrast monochrome palette.
- **Hybrid architecture** — classic PHP templates plus `theme.json` v3 for the block editor.
- **Fast** — preconnected webfonts, no heavy CDN, deferred vanilla JS, no jQuery on the front end.
- **Built for data** — styled tables for price-per-bigha / circle-rate indices.

## Requirements

| | |
|---|---|
| WordPress | 6.4+ |
| PHP | 7.4+ |
| Build step | none — ships plain CSS/JS |

## Installation

### From a release
1. Download `mrittika.zip` from the [Releases](https://github.com/konkomaji/mrittika/releases) page.
2. WordPress admin → **Appearance → Themes → Add New → Upload Theme**.
3. Activate.

### From source
```bash
git clone https://github.com/konkomaji/mrittika.git
# copy the inner mrittika/ folder into wp-content/themes/
```

## Setup checklist

1. **Appearance → Menus** — assign menus to *Primary*, *Topics / Categories Bar*,
   *Footer*, and *Social Links*.
2. **Mrittika** (admin menu) — the dedicated settings dashboard. Configure homepage,
   design, AdSense, SEO, performance, and security across the eight tabs.
3. **Settings → Reading** — set a static front page or use the latest-posts blog home.
4. **Posts → Categories** — optionally upload an 800×800 image per category for the
   Explore Topics slider.
5. **Mrittika → Tools** — after changing image sizes, run **Regenerate thumbnails** once.
6. **Widgets** — populate the main sidebar and up to four footer columns.

## Project structure

```
mrittika/
├── style.css              Theme header + safety baseline
├── theme.json             Block editor settings & styles (v3)
├── functions.php          Loads inc/ modules
├── inc/                   options, setup, enqueue, template tags, SEO, schema, breadcrumbs,
│                          blocks, security, ads, customizer, widgets, faq
│   └── admin/             settings.php (tabbed dashboard), thumbnails.php (regen tool)
├── template-parts/        content cards, single, search, author bio, related posts
├── assets/css/            material-tokens.css, main.css, editor.css
├── assets/admin/          admin.css, admin.js (settings page)
├── assets/js/             theme.js, navigation.js, customizer.js
├── *.php                  header, footer, index, home, single, page, archive, search, 404, comments
├── CHANGELOG.md
└── LICENSE
```

## Design tokens

Everything visual is driven by custom properties in
[`assets/css/material-tokens.css`](assets/css/material-tokens.css). Re-theme by editing
the grayscale ramp and semantic role variables — no build step, no preprocessing.

## Contributing

Issues and PRs welcome. Please:
- Keep the palette monochrome (the brand constraint).
- Follow WordPress PHP coding standards; escape all output.
- Add a `CHANGELOG.md` entry under **Unreleased**.

## License

[GPL-2.0-or-later](LICENSE). © 2026 Bengal Property Index.
