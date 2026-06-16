# Changelog

All notable changes to **Mrittika** are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] — 2026-06-16

### Added
- **Stunning Bengal 404 page** — Full-viewport abstract art hero with hand-crafted SVG:
  alpona radial mandala, Bishnupur terracotta diamond patterns, abstract ilish fish
  silhouette, mustard-field wave layers, and animated kantha floating dots.
  Bengali headline "হারানো পথ" (Lost Path) with warm terracotta/mustard accent palette.
  Two-column suggestions grid (recent posts + category pills). Fully dark-mode aware.
- **FAQ Builder** (`inc/faq.php`) — Repeatable metabox on posts: Question + Answer +
  optional AI-citation badge (model name, source, URL). M3 Expressive accordion on
  frontend with spring expand/collapse animation. Drag-to-reorder in admin.
  Emits `FAQPage` JSON-LD schema automatically (skips if SEO plugin active).
- **Expressive breadcrumbs** — Each item is an interactive chip/pill: hover fills,
  click scales with momentum spring (`scale(0.93)` + inverse-surface flash), separator
  changed from `/` to `›`. Frosted-glass wrap (`backdrop-filter: blur`) baked into
  the sticky site-header.

### Changed
- All featured image aspect ratios unified to **3:2** sitewide: hero thumbnail
  (`1200×800`), wide (`1600×1067`), home-hero feature card, single-post thumbnail,
  and mobile secondary card. Upload once, display correctly everywhere.
- **Duplicate date bug fixed**: `mrittika_posted_on()` now compares formatted date
  strings (`get_the_date()`) instead of Unix timestamps, so same-day saves no longer
  print "June 16, 2026June 16, 2026". Updated date shows only when actually a
  different calendar date, separated with `·`.
- Theme version bumped to `1.2.0`.

## [1.1.1] — 2026-06-16

### Fixed
- **Mobile header no longer breaks.** Rebuilt the responsive header: single non-wrapping
  row, truncating site title, tagline hidden on phones, and the hamburger moved into the
  right-hand action cluster (Apple/Google-style). Nav is now a height-anchored slide-down
  panel with scroll-lock; resets cleanly on resize. Header height tokenised (`--header-h`,
  64px → 56px on phones).
- Menu toggle markup moved out of `<nav>` into `.header-actions`; JS updated to match and
  to close the menu on link tap, Escape, and desktop resize.

### Changed
- **Homepage no longer shows the sidebar / widgets** — the blog home is now a full-width
  story grid. Sidebars remain on archives, search, and single posts.
- Tablet breakpoint raised to 900px; added a dedicated 560px phone breakpoint.

## [1.1.0] — 2026-06-16

### Added
- **Dedicated theme settings dashboard** (`Mrittika` admin menu) with tabbed UI —
  General · Design · Ads · SEO · Performance · Security. Backed by the Settings API,
  every field sanitized through a single central store (`inc/options.php`).
- **Webfonts**: Poppins (display) + Inter (body) with `display=swap` and preconnect;
  one-click disable to fall back to the system stack.
- **Google AdSense integration** (`inc/ads.php`): validated Publisher ID, optional Auto
  Ads, four policy-safe ad slots (after-header, in-content after Nth paragraph,
  after-post, sidebar), labeled non-intrusive containers, and a virtual `/ads.txt`.
- **Security hardening** (`inc/security.php`): security headers (X-Content-Type-Options,
  Referrer-Policy, X-Frame-Options, COOP, Permissions-Policy, HSTS over HTTPS),
  XML-RPC disable, user-enumeration blocking (`?author=N` + REST users), generic login
  errors, `DISALLOW_FILE_EDIT`, hardened comment links, defensive comment filtering.
  All toggleable.
- **Material 3 Expressive** expansion: shadow presets in `theme.json`, spring/emphasized
  motion tokens, and block style variations (M3 Card, Outline, Tonal/Text buttons,
  Pull Quote, Dotted separator, Checklist, Expressive Rounded image).
- **Table of contents**: auto-generated from h2/h3 on long posts, collapsible, with
  scrollspy highlighting and auto heading anchors.
- **Back-to-top FAB**, **copy-link** button, and **View Transitions** for navigation.
- Performance toggles: defer scripts, remove emoji bloat, lazy-load iframes.
- SEO additions: Twitter `site` handle, Search Console / Bing verification meta.
- Card-style options (soft / outline / flat) and default-scheme preference.
- Footer credit tag linking to the concept author.

### Changed
- Display font moved from Georgia to Poppins; body to Inter (system fallback retained).
- Theme options consolidated from the Customizer into the dedicated settings page;
  the Customizer now only handles live-preview of title/description.
- `theme.json` bumped with shadow presets, dimensions, and sticky-position support.
- Tested up to WordPress 6.8.

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

[Unreleased]: https://github.com/konkomaji/mrittika/compare/v1.2.0...HEAD
[1.2.0]: https://github.com/konkomaji/mrittika/compare/v1.1.1...v1.2.0
[1.1.1]: https://github.com/konkomaji/mrittika/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/konkomaji/mrittika/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/konkomaji/mrittika/releases/tag/v1.0.0
