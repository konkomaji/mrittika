# Changelog

All notable changes to **Mrittika** are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.4.0] — 2026-06-17

### Changed
- **"Explore our Topics" → single-line horizontal marquee** — replaced the multi-row
  auto-fill grid with a continuous CSS marquee strip (`animation: topics-marquee 40s
  linear infinite`). Items duplicated in PHP for seamless loop. Pauses on hover and on
  touch (mobile). Edge fades via `mask-image` gradient. Each cube is a fixed 160×160 px
  flex item instead of a grid cell — works on all screen widths.
- **Hero uses a dedicated `WP_Query`** — hero section (latest 3 posts) no longer
  consumes posts from the main query. "Start to Read" now shows all posts from page 1
  of the main query (posts 1–N), so the infinite scroll cycle covers every published
  post without gaps or skipped entries.
- **Infinite scroll URL builder hardened** — switched from `data-base-url` to
  `data-page-url` (seeded with `get_pagenum_link(2)`). JS now regex-replaces the page
  number in the template URL, supporting both pretty (`/page/N/`) and plain (`?paged=N`)
  permalink structures reliably. Falls back to constructing the URL from `location.href`.
- **`data-current-page` respected by JS** — infinite scroll reads the actual current
  page from the data attribute (set by `get_query_var('paged')`), so landing on a paged
  URL directly starts fetching from the next correct page.
- Hero and topics sections skipped on paged requests (`is_paged()`) — keeps server
  response lean when JS fetches page 2+ for infinite scroll.
- Theme version bumped to `1.4.0`.

## [1.3.1] — 2026-06-17

## [1.3.0] — 2026-06-17

### Added
- **"Explore our Topics"** section on homepage — responsive grid of category cubes
  (1:1 square, rounded `shape-2xl`). Each cube shows category image or a cycling
  WB-inspired gradient (terracotta/indigo/mustard/sage) with large decorative initial
  and post count. Hover: `translateY(-5px) scale(1.03)` spring lift. Click triggers
  View Transitions API (`::view-transition-old/new`) with slide-fade animation; graceful
  fallback to normal navigation.
- **Category image admin field** — Upload 800×800 image per category from the WP
  category edit screen. Stored in term meta `mrittika_cat_image`. New image size
  `mrittika-cat-tile` (800×800, hard crop). Helper: `mrittika_get_cat_image_url()`.
- **Infinite scroll** on homepage — IntersectionObserver watches a sentinel element;
  fetches next page via `fetch()`, parses HTML, appends cards with staggered
  `card-appear` animation (60ms delay per card). Shows spinner while loading.
  "You've read everything ✦" end message when all pages consumed. Works with both
  plain and pretty WordPress permalink structures.
- **"Our Archives" button** — Expressive pill button before footer, links to current
  year archive. Hover: fills solid with `translateY(-3px) + shadow-lg`; arrow icon
  slides right on hover.
- **Search restricted to posts only** — `pre_get_posts` hook excludes pages, CPTs,
  and attachments from front-end search results.
- Section renamed **"Start to Read"** (was "More stories").

### Fixed
- **Card image not filling container** — `<a class="post-thumbnail">` was `display:inline`
  (default anchor), preventing `width:100%` on the image from working. Added
  `.card-media a, .card-media .post-thumbnail { display:block; line-height:0; }`.

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

[Unreleased]: https://github.com/konkomaji/mrittika/compare/v1.3.0...HEAD
[1.3.0]: https://github.com/konkomaji/mrittika/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/konkomaji/mrittika/compare/v1.1.1...v1.2.0
[1.1.1]: https://github.com/konkomaji/mrittika/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/konkomaji/mrittika/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/konkomaji/mrittika/releases/tag/v1.0.0
