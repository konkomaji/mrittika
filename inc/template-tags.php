<?php
/**
 * Template tags — reusable display helpers.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mrittika_posted_on' ) ) {
	/**
	 * Published / updated date with machine-readable <time>.
	 */
	function mrittika_posted_on() {
		$time = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		if ( get_the_date() !== get_the_modified_date() ) {
			$time .= ' &bull; <time class="entry-date updated" datetime="%3$s">%4$s</time>';
		}
		printf(
			'<span class="posted-on">%s</span>',
			sprintf(
				$time, // phpcs:ignore
				esc_attr( get_the_date( DATE_W3C ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( DATE_W3C ) ),
				esc_html( get_the_modified_date() )
			)
		);
	}
}

if ( ! function_exists( 'mrittika_posted_by' ) ) {
	/**
	 * Author with link.
	 */
	function mrittika_posted_by() {
		printf(
			'<span class="byline">%1$s <a class="author-link" href="%2$s" rel="author">%3$s</a></span>',
			esc_html_x( 'By', 'post author', 'mrittika' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
}

if ( ! function_exists( 'mrittika_reading_time' ) ) {
	/**
	 * Estimated reading time from the post content.
	 */
	function mrittika_reading_time( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();
		$content = get_post_field( 'post_content', $post_id );
		$words   = str_word_count( wp_strip_all_tags( $content ) );
		$minutes = max( 1, (int) ceil( $words / 220 ) );
		return sprintf(
			/* translators: %d: minutes */
			_n( '%d min read', '%d min read', $minutes, 'mrittika' ),
			$minutes
		);
	}
}

if ( ! function_exists( 'mrittika_entry_meta' ) ) {
	/**
	 * Full meta row for an article.
	 */
	function mrittika_entry_meta() {
		echo '<div class="entry-meta">';
		mrittika_posted_by();
		echo '<span class="meta-sep" aria-hidden="true">·</span>';
		mrittika_posted_on();
		if ( ! function_exists( 'mrittika_get_option' ) || mrittika_get_option( 'show_reading_time', true ) ) {
			echo '<span class="meta-sep" aria-hidden="true">·</span>';
			echo '<span class="reading-time">' . esc_html( mrittika_reading_time() ) . '</span>';
		}
		echo '</div>';
	}
}

if ( ! function_exists( 'mrittika_category_pills' ) ) {
	/**
	 * Render the post's categories as pill links.
	 */
	function mrittika_category_pills( $limit = 2 ) {
		$cats = get_the_category();
		if ( empty( $cats ) ) {
			return;
		}
		$cats = array_slice( $cats, 0, $limit );
		echo '<div class="category-pills">';
		foreach ( $cats as $cat ) {
			printf(
				'<a class="pill" href="%1$s">%2$s</a>',
				esc_url( get_category_link( $cat->term_id ) ),
				esc_html( $cat->name )
			);
		}
		echo '</div>';
	}
}

if ( ! function_exists( 'mrittika_post_thumbnail' ) ) {
	/**
	 * Responsive, lazy, linked post thumbnail.
	 */
	function mrittika_post_thumbnail( $size = 'mrittika-card' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}
		$attr = array(
			'loading'  => is_singular() ? 'eager' : 'lazy',
			'decoding' => 'async',
		);
		if ( is_singular() ) {
			echo '<figure class="post-thumbnail">';
			the_post_thumbnail( 'post-thumbnail', $attr );
			$caption = get_the_post_thumbnail_caption();
			if ( $caption ) {
				echo '<figcaption class="thumb-caption">' . esc_html( $caption ) . '</figcaption>';
			}
			echo '</figure>';
		} else {
			printf(
				'<a class="post-thumbnail" href="%1$s" aria-hidden="true" tabindex="-1">%2$s</a>',
				esc_url( get_permalink() ),
				get_the_post_thumbnail( null, $size, $attr ) // phpcs:ignore
			);
		}
	}
}

if ( ! function_exists( 'mrittika_table_of_contents' ) ) {
	/**
	 * Auto-generated table of contents from h2/h3 in the post content.
	 * Adds anchor ids to the headings and renders a collapsible nav.
	 * Only shows when the post has at least 3 headings.
	 */
	function mrittika_table_of_contents() {
		$content = get_the_content();
		if ( ! $content ) {
			return;
		}
		if ( ! preg_match_all( '/<h([23])[^>]*>(.*?)<\/h\1>/is', $content, $m, PREG_SET_ORDER ) ) {
			return;
		}
		$items = array();
		$seen  = array();
		foreach ( $m as $h ) {
			$text = trim( wp_strip_all_tags( $h[2] ) );
			if ( '' === $text ) {
				continue;
			}
			$slug = sanitize_title( $text );
			if ( isset( $seen[ $slug ] ) ) {
				$seen[ $slug ]++;
				$slug .= '-' . $seen[ $slug ];
			} else {
				$seen[ $slug ] = 1;
			}
			$items[] = array( 'level' => (int) $h[1], 'text' => $text, 'slug' => $slug );
		}
		if ( count( $items ) < 3 ) {
			return;
		}
		?>
		<nav class="toc" aria-label="<?php esc_attr_e( 'Table of contents', 'mrittika' ); ?>" data-toc>
			<button class="toc-toggle" type="button" aria-expanded="true">
				<span class="toc-title"><?php esc_html_e( 'In this article', 'mrittika' ); ?></span>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<ol class="toc-list">
				<?php foreach ( $items as $item ) : ?>
					<li class="toc-item toc-level-<?php echo (int) $item['level']; ?>">
						<a href="#<?php echo esc_attr( $item['slug'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ol>
		</nav>
		<?php
	}
}

if ( ! function_exists( 'mrittika_add_heading_ids' ) ) {
	/**
	 * Add matching anchor ids to h2/h3 in the rendered content so TOC links resolve.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	function mrittika_add_heading_ids( $content ) {
		if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		$seen = array();
		return preg_replace_callback(
			'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
			function ( $h ) use ( &$seen ) {
				$text = trim( wp_strip_all_tags( $h[3] ) );
				if ( '' === $text || strpos( $h[2], 'id=' ) !== false ) {
					return $h[0];
				}
				$slug = sanitize_title( $text );
				if ( isset( $seen[ $slug ] ) ) {
					$seen[ $slug ]++;
					$slug .= '-' . $seen[ $slug ];
				} else {
					$seen[ $slug ] = 1;
				}
				return '<h' . $h[1] . $h[2] . ' id="' . esc_attr( $slug ) . '">' . $h[3] . '</h' . $h[1] . '>';
			},
			$content
		);
	}
	add_filter( 'the_content', 'mrittika_add_heading_ids', 8 );
}

if ( ! function_exists( 'mrittika_pagination' ) ) {
	/**
	 * Numbered pagination for archives.
	 */
	function mrittika_pagination() {
		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => __( '&larr; Newer', 'mrittika' ),
				'next_text'          => __( 'Older &rarr;', 'mrittika' ),
				'screen_reader_text' => __( 'Posts navigation', 'mrittika' ),
				'aria_label'         => __( 'Posts', 'mrittika' ),
				'class'              => 'mrittika-pagination',
			)
		);
	}
}
