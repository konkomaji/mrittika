# Changelog

All notable changes to **Mrittika** are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] — 2026-06-16

### Added
- Initial public release.
- Hybrid theme: classic PHP templates + `theme.json` (v3) for the block editor.
- Material Expressive 3 monochrome design system (`assets/css/material-tokens.css`):
  full neutral tonal ramp, fluid type scale, shape/elevation/motion tokens.
- Automatic light/dark color scheme with persisted manual toggle (no flash on load).
- Templates: `index`, `home`, `single`, `page`, `page-wide`, `archive`, `search`,
  `404`, `comments`, plus header/footer and reusable `template-parts`.
- Magazine-style blog home with featured hero + post grid.
- Editorial single-post layout: standfirst, reading time, category pills, share row,
  author bio, related posts, prev/next navigation, reading-progress bar.
- SEO module (`inc/seo.php`): meta description, canonical, Open Graph, Twitter cards.
  Fully Yoast-friendly — auto-disables when Yoast, Rank Math, SEOPress, or AIOSEO is active.
- JSON-LD structured data (`inc/schema.php`): WebSite, Organization, Article, BreadcrumbList.
- Accessible breadcrumb trail with category hierarchy.
- Four footer widget areas + main sidebar; four nav menus (primary, footer, social, topics).
- Custom logo, responsive image crops, accessible mobile navigation, skip link.
- Customizer options: default scheme, footer text, default share image.
- Data-table styling tuned for property price guides and indices.
- GPL-2.0-or-later, translation-ready (`mrittika` text domain).

[Unreleased]: https://github.com/bengalpropertyindex/mrittika/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/bengalpropertyindex/mrittika/releases/tag/v1.0.0
