<?php
/**
 * FAQ Builder — repeatable metabox, M3 Expressive accordion, FAQPage schema.
 *
 * @package Mrittika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ── Metabox registration ──────────────────────────────── */

function mrittika_faq_register_metabox() {
	add_meta_box(
		'mrittika_faq',
		__( 'Frequently Asked Questions', 'mrittika' ),
		'mrittika_faq_metabox_render',
		'post',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'mrittika_faq_register_metabox' );

/* ── Metabox HTML ──────────────────────────────────────── */

function mrittika_faq_metabox_render( $post ) {
	wp_nonce_field( 'mrittika_faq_save', 'mrittika_faq_nonce' );
	$items = get_post_meta( $post->ID, '_mrittika_faq', true );
	if ( ! is_array( $items ) ) {
		$items = array();
	}
	?>
	<style>
	#mrittika-faq-builder{font-family:-apple-system,sans-serif}
	.mfaq-item{display:flex;gap:8px;align-items:flex-start;padding:12px 14px;border:1px solid #dcdcde;border-radius:6px;margin-bottom:10px;background:#f9f9f9}
	.mfaq-item.mfaq-dragging{opacity:.4}
	.mfaq-handle{cursor:grab;color:#aaa;padding:4px 2px;font-size:16px;line-height:1;user-select:none}
	.mfaq-fields{flex:1;display:flex;flex-direction:column;gap:6px}
	.mfaq-fields label{font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;color:#555;margin:0}
	.mfaq-fields input,.mfaq-fields textarea{margin:0}
	.mfaq-ai-label{display:flex;align-items:center;gap:6px;font-weight:500;font-size:12px;color:#444;text-transform:none;letter-spacing:0;margin-top:4px;cursor:pointer}
	.mfaq-ai-fields{padding:10px;background:#fff;border-radius:4px;border:1px solid #dcdcde;display:flex;flex-direction:column;gap:6px;margin-top:2px}
	.mfaq-ai-fields label{font-size:11px}
	.mfaq-remove{color:#b32d2e;background:none;border:none;cursor:pointer;font-size:12px;padding:2px 6px;border-radius:4px;transition:background .15s}
	.mfaq-remove:hover{background:#fce8e8}
	.mfaq-add-btn{margin-top:4px}
	</style>

	<div id="mrittika-faq-builder">
		<div class="mfaq-items" id="mfaq-items">
			<?php foreach ( $items as $i => $item ) : ?>
			<div class="mfaq-item" data-index="<?php echo esc_attr( $i ); ?>" draggable="true">
				<span class="mfaq-handle" title="<?php esc_attr_e( 'Drag to reorder', 'mrittika' ); ?>">⠿</span>
				<div class="mfaq-fields">
					<label for="mfaq-q-<?php echo $i; ?>"><?php esc_html_e( 'Question', 'mrittika' ); ?></label>
					<input type="text" id="mfaq-q-<?php echo $i; ?>" name="mrittika_faq[<?php echo $i; ?>][q]"
						value="<?php echo esc_attr( $item['q'] ?? '' ); ?>"
						placeholder="<?php esc_attr_e( 'Type the question…', 'mrittika' ); ?>"
						class="widefat">

					<label for="mfaq-a-<?php echo $i; ?>"><?php esc_html_e( 'Answer', 'mrittika' ); ?></label>
					<textarea id="mfaq-a-<?php echo $i; ?>" name="mrittika_faq[<?php echo $i; ?>][a]"
						rows="3" class="widefat"
						placeholder="<?php esc_attr_e( 'Type the answer…', 'mrittika' ); ?>"><?php echo esc_textarea( $item['a'] ?? '' ); ?></textarea>

					<label class="mfaq-ai-label">
						<input type="checkbox" name="mrittika_faq[<?php echo $i; ?>][ai_cited]" value="1"
							<?php checked( ! empty( $item['ai_cited'] ) ); ?> class="mfaq-ai-check">
						<?php esc_html_e( 'AI-assisted answer', 'mrittika' ); ?>
					</label>

					<div class="mfaq-ai-fields" <?php echo empty( $item['ai_cited'] ) ? 'hidden' : ''; ?>>
						<label><?php esc_html_e( 'AI model', 'mrittika' ); ?></label>
						<input type="text" name="mrittika_faq[<?php echo $i; ?>][ai_model]"
							value="<?php echo esc_attr( $item['ai_model'] ?? '' ); ?>"
							placeholder="e.g. Claude Sonnet 4.6" class="widefat">
						<label><?php esc_html_e( 'Source name', 'mrittika' ); ?></label>
						<input type="text" name="mrittika_faq[<?php echo $i; ?>][ai_source]"
							value="<?php echo esc_attr( $item['ai_source'] ?? '' ); ?>"
							placeholder="e.g. Anthropic" class="widefat">
						<label><?php esc_html_e( 'Source URL (optional)', 'mrittika' ); ?></label>
						<input type="url" name="mrittika_faq[<?php echo $i; ?>][ai_url]"
							value="<?php echo esc_url( $item['ai_url'] ?? '' ); ?>"
							placeholder="https://…" class="widefat">
					</div>
				</div>
				<button type="button" class="mfaq-remove"><?php esc_html_e( '✕ Remove', 'mrittika' ); ?></button>
			</div>
			<?php endforeach; ?>
		</div>

		<button type="button" class="button button-primary mfaq-add-btn" id="mfaq-add">
			<?php esc_html_e( '+ Add FAQ item', 'mrittika' ); ?>
		</button>
	</div>

	<script>
	(function(){
		var wrap   = document.getElementById('mfaq-items');
		var addBtn = document.getElementById('mfaq-add');
		var tmpl   = <?php echo wp_json_encode( mrittika_faq_item_template() ); ?>;

		/* Toggle AI fields on checkbox change */
		wrap.addEventListener('change', function(e){
			if( e.target.classList.contains('mfaq-ai-check') ){
				var af = e.target.closest('.mfaq-fields').querySelector('.mfaq-ai-fields');
				e.target.checked ? af.removeAttribute('hidden') : af.setAttribute('hidden','');
			}
		});

		/* Remove item */
		wrap.addEventListener('click', function(e){
			if( e.target.classList.contains('mfaq-remove') ){
				if( confirm( '<?php echo esc_js( __( 'Remove this FAQ item?', 'mrittika' ) ); ?>' ) ){
					e.target.closest('.mfaq-item').remove();
					reindex();
				}
			}
		});

		/* Add item */
		addBtn.addEventListener('click', function(){
			var idx = wrap.querySelectorAll('.mfaq-item').length;
			var div = document.createElement('div');
			div.className = 'mfaq-item';
			div.setAttribute('data-index', idx);
			div.setAttribute('draggable', 'true');
			div.innerHTML = tmpl.replace(/__IDX__/g, idx);
			wrap.appendChild(div);
			div.querySelector('input[type="text"]').focus();
			bindDrag(div);
		});

		/* Re-index name attributes after remove / reorder */
		function reindex(){
			wrap.querySelectorAll('.mfaq-item').forEach(function(item, i){
				item.setAttribute('data-index', i);
				item.querySelectorAll('[name]').forEach(function(inp){
					inp.name = inp.name.replace(/mrittika_faq\[\d+\]/, 'mrittika_faq['+i+']');
				});
				item.querySelectorAll('[id]').forEach(function(el){
					el.id = el.id.replace(/-\d+-/, '-'+i+'-');
				});
				item.querySelectorAll('[for]').forEach(function(el){
					el.htmlFor = el.htmlFor.replace(/-\d+-/, '-'+i+'-');
				});
			});
		}

		/* Drag-to-reorder */
		var dragSrc = null;
		function bindDrag(el){
			el.addEventListener('dragstart', function(){ dragSrc = this; this.classList.add('mfaq-dragging'); });
			el.addEventListener('dragend',   function(){ this.classList.remove('mfaq-dragging'); reindex(); });
			el.addEventListener('dragover',  function(e){ e.preventDefault(); });
			el.addEventListener('drop', function(e){
				e.preventDefault();
				if( dragSrc !== this ){
					var allItems = Array.from(wrap.querySelectorAll('.mfaq-item'));
					var srcIdx   = allItems.indexOf(dragSrc);
					var tgtIdx   = allItems.indexOf(this);
					if( srcIdx < tgtIdx ) wrap.insertBefore(dragSrc, this.nextSibling);
					else wrap.insertBefore(dragSrc, this);
				}
			});
		}
		wrap.querySelectorAll('.mfaq-item').forEach(bindDrag);
	})();
	</script>
	<?php
}

function mrittika_faq_item_template() {
	ob_start();
	?>
<span class="mfaq-handle" title="Drag to reorder">⠿</span>
<div class="mfaq-fields">
	<label for="mfaq-q-__IDX__">Question</label>
	<input type="text" id="mfaq-q-__IDX__" name="mrittika_faq[__IDX__][q]" value="" placeholder="Type the question…" class="widefat">
	<label for="mfaq-a-__IDX__">Answer</label>
	<textarea id="mfaq-a-__IDX__" name="mrittika_faq[__IDX__][a]" rows="3" class="widefat" placeholder="Type the answer…"></textarea>
	<label class="mfaq-ai-label">
		<input type="checkbox" name="mrittika_faq[__IDX__][ai_cited]" value="1" class="mfaq-ai-check">
		AI-assisted answer
	</label>
	<div class="mfaq-ai-fields" hidden>
		<label>AI model</label>
		<input type="text" name="mrittika_faq[__IDX__][ai_model]" value="" placeholder="e.g. Claude Sonnet 4.6" class="widefat">
		<label>Source name</label>
		<input type="text" name="mrittika_faq[__IDX__][ai_source]" value="" placeholder="e.g. Anthropic" class="widefat">
		<label>Source URL (optional)</label>
		<input type="url" name="mrittika_faq[__IDX__][ai_url]" value="" placeholder="https://…" class="widefat">
	</div>
</div>
<button type="button" class="mfaq-remove">✕ Remove</button>
	<?php
	return trim( ob_get_clean() );
}

/* ── Save ──────────────────────────────────────────────── */

function mrittika_faq_save( $post_id ) {
	if (
		! isset( $_POST['mrittika_faq_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['mrittika_faq_nonce'] ), 'mrittika_faq_save' ) ||
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}

	if ( ! empty( $_POST['mrittika_faq'] ) && is_array( $_POST['mrittika_faq'] ) ) {
		$clean = array();
		foreach ( $_POST['mrittika_faq'] as $raw ) {
			$q = sanitize_text_field( $raw['q'] ?? '' );
			$a = wp_kses_post( $raw['a'] ?? '' );
			if ( '' === $q && '' === $a ) {
				continue;
			}
			$clean[] = array(
				'q'         => $q,
				'a'         => $a,
				'ai_cited'  => ! empty( $raw['ai_cited'] ) ? 1 : 0,
				'ai_model'  => sanitize_text_field( $raw['ai_model']  ?? '' ),
				'ai_source' => sanitize_text_field( $raw['ai_source'] ?? '' ),
				'ai_url'    => esc_url_raw( $raw['ai_url'] ?? '' ),
			);
		}
		update_post_meta( $post_id, '_mrittika_faq', $clean );
	} else {
		delete_post_meta( $post_id, '_mrittika_faq' );
	}
}
add_action( 'save_post', 'mrittika_faq_save' );

/* ── Frontend accordion ────────────────────────────────── */

function mrittika_faq_display() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$items = get_post_meta( get_the_ID(), '_mrittika_faq', true );
	if ( empty( $items ) || ! is_array( $items ) ) {
		return;
	}
	$pid = get_the_ID();
	?>
	<section class="post-faq" aria-label="<?php esc_attr_e( 'Frequently Asked Questions', 'mrittika' ); ?>">
		<h2 class="post-faq-heading"><?php esc_html_e( 'Frequently Asked Questions', 'mrittika' ); ?></h2>
		<div class="faq-list">
			<?php foreach ( $items as $i => $item ) :
				$qid = 'faq-' . $pid . '-' . $i . '-q';
				$aid = 'faq-' . $pid . '-' . $i . '-a';
			?>
			<div class="faq-item" itemscope itemtype="https://schema.org/Question">
				<button
					class="faq-question"
					id="<?php echo esc_attr( $qid ); ?>"
					aria-expanded="false"
					aria-controls="<?php echo esc_attr( $aid ); ?>"
					type="button"
				>
					<span class="faq-q-text" itemprop="name"><?php echo esc_html( $item['q'] ); ?></span>
					<svg class="faq-chevron" width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
						<path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
				<div
					class="faq-answer"
					id="<?php echo esc_attr( $aid ); ?>"
					role="region"
					aria-labelledby="<?php echo esc_attr( $qid ); ?>"
					hidden
					itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"
				>
					<div class="faq-answer-body" itemprop="text">
						<?php echo wp_kses_post( wpautop( $item['a'] ) ); ?>
					</div>

					<?php if ( ! empty( $item['ai_cited'] ) ) : ?>
					<div class="faq-citation">
						<span class="faq-citation-badge">
							<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
								<circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.2"/>
								<path d="M4 6C4 4.895 4.895 4 6 4S8 4.895 8 6 7.105 8 6 8" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
								<circle cx="6" cy="6" r=".8" fill="currentColor"/>
							</svg>
							<?php esc_html_e( 'AI-assisted', 'mrittika' ); ?>
						</span>
						<?php if ( $item['ai_model'] ) : ?>
						<span class="faq-citation-model"><?php echo esc_html( $item['ai_model'] ); ?></span>
						<?php endif; ?>
						<?php if ( $item['ai_source'] ) : ?>
						<span class="faq-citation-sep" aria-hidden="true">·</span>
						<?php if ( $item['ai_url'] ) : ?>
						<a class="faq-citation-source" href="<?php echo esc_url( $item['ai_url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $item['ai_source'] ); ?></a>
						<?php else : ?>
						<span class="faq-citation-source"><?php echo esc_html( $item['ai_source'] ); ?></span>
						<?php endif; ?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

/* ── FAQ accordion JS (inline, only when FAQ exists) ──── */

function mrittika_faq_frontend_js() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$items = get_post_meta( get_the_ID(), '_mrittika_faq', true );
	if ( empty( $items ) ) {
		return;
	}
	?>
	<script>
	(function(){
		document.querySelectorAll('.faq-question').forEach(function(btn){
			btn.addEventListener('click', function(){
				var open   = this.getAttribute('aria-expanded') === 'true';
				var panel  = document.getElementById(this.getAttribute('aria-controls'));
				var parent = this.closest('.faq-item');

				/* Close all others */
				document.querySelectorAll('.faq-question[aria-expanded="true"]').forEach(function(ob){
					if(ob !== btn){
						ob.setAttribute('aria-expanded','false');
						var op = document.getElementById(ob.getAttribute('aria-controls'));
						if(op) mrittika_faq_collapse(op);
					}
				});

				if(open){
					this.setAttribute('aria-expanded','false');
					mrittika_faq_collapse(panel);
				} else {
					this.setAttribute('aria-expanded','true');
					mrittika_faq_expand(panel);
				}
			});
		});

		function mrittika_faq_expand(el){
			el.removeAttribute('hidden');
			el.style.maxHeight = '0';
			el.style.overflow  = 'hidden';
			el.style.transition = 'max-height 320ms cubic-bezier(0.05,0.7,0.1,1)';
			requestAnimationFrame(function(){
				el.style.maxHeight = el.scrollHeight + 'px';
			});
			el.addEventListener('transitionend', function handler(){
				el.style.maxHeight = '';
				el.style.overflow  = '';
				el.style.transition = '';
				el.removeEventListener('transitionend', handler);
			});
		}

		function mrittika_faq_collapse(el){
			el.style.maxHeight  = el.scrollHeight + 'px';
			el.style.overflow   = 'hidden';
			el.style.transition = 'max-height 240ms cubic-bezier(0.3,0,0.8,0.15)';
			requestAnimationFrame(function(){
				el.style.maxHeight = '0';
			});
			el.addEventListener('transitionend', function handler(){
				el.setAttribute('hidden','');
				el.style.maxHeight  = '';
				el.style.overflow   = '';
				el.style.transition = '';
				el.removeEventListener('transitionend', handler);
			});
		}
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'mrittika_faq_frontend_js' );

/* ── FAQPage JSON-LD ───────────────────────────────────── */

function mrittika_faq_schema() {
	if ( ! is_singular( 'post' ) || mrittika_seo_plugin_active() ) {
		return;
	}
	$items = get_post_meta( get_the_ID(), '_mrittika_faq', true );
	if ( empty( $items ) || ! is_array( $items ) ) {
		return;
	}

	$entities = array();
	foreach ( $items as $item ) {
		if ( empty( $item['q'] ) || empty( $item['a'] ) ) {
			continue;
		}
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $item['q'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['a'] ),
			),
		);
	}
	if ( empty( $entities ) ) {
		return;
	}

	$data = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);

	echo "\n" . '<script type="application/ld+json">'
		. wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'mrittika_faq_schema', 5 );
