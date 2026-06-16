<?php
/**
 * Accessible breadcrumb trail. Emits visible nav + BreadcrumbList JSON-LD.
 * Yields to Yoast/Rank Math breadcrumbs if those are configured.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mrittika_breadcrumbs' ) ) {
	/**
	 * Output breadcrumbs.
	 */
	function mrittika_breadcrumbs() {
		if ( is_front_page() ) {
			return;
		}

		// Defer to SEO plugin breadcrumbs if available.
		if ( function_exists( 'yoast_breadcrumb' ) && yoast_breadcrumb( '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'mrittika' ) . '">', '</nav>', false ) ) {
			yoast_breadcrumb( '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'mrittika' ) . '">', '</nav>' );
			return;
		}

		$items = mrittika_get_breadcrumb_items();
		if ( count( $items ) < 2 ) {
			return;
		}

		echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'mrittika' ) . '">';
		echo '<ol class="breadcrumb-list">';
		$last = count( $items ) - 1;
		foreach ( $items as $i => $item ) {
			if ( $i === $last || empty( $item['url'] ) ) {
				printf(
					'<li class="breadcrumb-item is-current" aria-current="page">%s</li>',
					esc_html( $item['label'] )
				);
			} else {
				printf(
					'<li class="breadcrumb-item"><a href="%1$s">%2$s</a></li>',
					esc_url( $item['url'] ),
					esc_html( $item['label'] )
				);
			}
		}
		echo '</ol>';
		echo '</nav>';

		mrittika_breadcrumb_jsonld( $items );
	}
}

if ( ! function_exists( 'mrittika_get_breadcrumb_items' ) ) {
	/**
	 * Build the trail as an ordered array of [label, url].
	 *
	 * @return array
	 */
	function mrittika_get_breadcrumb_items() {
		$items   = array();
		$items[] = array( 'label' => __( 'Home', 'mrittika' ), 'url' => home_url( '/' ) );

		if ( is_category() || is_single() ) {
			$cat = is_single() ? ( get_the_category() ? get_the_category()[0] : null ) : get_queried_object();
			if ( $cat && ! empty( $cat->term_id ) ) {
				$ancestors = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
				foreach ( $ancestors as $anc_id ) {
					$anc = get_term( $anc_id, 'category' );
					if ( $anc && ! is_wp_error( $anc ) ) {
						$items[] = array( 'label' => $anc->name, 'url' => get_category_link( $anc->term_id ) );
					}
				}
				$items[] = array( 'label' => $cat->name, 'url' => get_category_link( $cat->term_id ) );
			}
			if ( is_single() ) {
				$items[] = array( 'label' => get_the_title(), 'url' => '' );
			}
		} elseif ( is_page() ) {
			$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
			foreach ( $ancestors as $anc_id ) {
				$items[] = array( 'label' => get_the_title( $anc_id ), 'url' => get_permalink( $anc_id ) );
			}
			$items[] = array( 'label' => get_the_title(), 'url' => '' );
		} elseif ( is_tag() ) {
			$items[] = array( 'label' => single_tag_title( '', false ), 'url' => '' );
		} elseif ( is_search() ) {
			$items[] = array( 'label' => sprintf( __( 'Search: %s', 'mrittika' ), get_search_query() ), 'url' => '' );
		} elseif ( is_author() ) {
			$items[] = array( 'label' => get_the_author(), 'url' => '' );
		} elseif ( is_year() || is_month() || is_day() ) {
			$items[] = array( 'label' => get_the_archive_title(), 'url' => '' );
		} elseif ( is_post_type_archive() ) {
			$items[] = array( 'label' => post_type_archive_title( '', false ), 'url' => '' );
		} elseif ( is_404() ) {
			$items[] = array( 'label' => __( 'Not Found', 'mrittika' ), 'url' => '' );
		} elseif ( is_home() && ! is_front_page() ) {
			$items[] = array( 'label' => get_the_title( get_option( 'page_for_posts' ) ), 'url' => '' );
		}

		return $items;
	}
}

if ( ! function_exists( 'mrittika_breadcrumb_jsonld' ) ) {
	/**
	 * Emit BreadcrumbList structured data.
	 *
	 * @param array $items Breadcrumb items.
	 */
	function mrittika_breadcrumb_jsonld( $items ) {
		if ( mrittika_seo_plugin_active() ) {
			return; // Let the SEO plugin own structured data.
		}
		$list = array();
		foreach ( $items as $i => $item ) {
			$entry = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => $item['label'],
			);
			if ( ! empty( $item['url'] ) ) {
				$entry['item'] = $item['url'];
			}
			$list[] = $entry;
		}
		$data = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $list,
		);
		echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}
