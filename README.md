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
- **Material Expressive 3 tokens** — fluid type, generous shape radii, elevation and
  motion easing curves, all in CSS custom properties.
- **Light / dark mode** — follows the device, with a persisted manual toggle and no
  flash of the wrong theme on load.
- **SEO-first** — meta description, canonical, Open Graph, Twitter cards, and JSON-LD
  (WebSite, Organization, Article, BreadcrumbList). **Yoast-friendly**: the built-in SEO
  output auto-disables when Yoast / Rank Math / SEOPress / AIOSEO is active.
- **Mobile-first & accessible** — semantic landmarks, skip link, keyboard-friendly nav,
  `prefers-reduced-motion` support, AA-contrast monochrome palette.
- **Hybrid architecture** — classic PHP templates plus `theme.json` for the block editor.
- **Fast** — system fonts only, no external CDN, deferred vanilla JS, no jQuery on the front end.
- **Built for data** — styled tables for price-per-bigha / circle-rate indices.

## Requirements

| | |
|---|---|
| WordPress | 6.4+ |
| PHP | 7.4+ |
| Build step | none — ships plain CSS/JS |

## Installation

### From a release
1. Download `mrittika.zip` from the [Releases](https://github.com/bengalpropertyindex/mrittika/releases) page.
2. WordPress admin → **Appearance → Themes → Add New → Upload Theme**.
3. Activate.

### From source
```bash
git clone https://github.com/bengalpropertyindex/mrittika.git
# copy the inner mrittika/ folder into wp-content/themes/
```

## Setup checklist

1. **Appearance → Menus** — assign menus to *Primary*, *Topics / Categories Bar*,
   *Footer*, and *Social Links*.
2. **Appearance → Customize → Mrittika Options** — set default color scheme, footer
   text, and a default social share image.
3. **Settings → Reading** — set a static front page or use the latest-posts blog home.
4. **Widgets** — populate the main sidebar and up to four footer columns.

## Project structure

```
mrittika/
├── style.css              Theme header + safety baseline
├── theme.json             Block editor settings & styles (v3)
├── functions.php          Loads inc/ modules
├── inc/                   setup, enqueue, template tags, SEO, schema, breadcrumbs, customizer, widgets
├── template-parts/        content cards, single, search, author bio, related posts
├── assets/css/            material-tokens.css, main.css, editor.css
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
