<?php get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div id="theme-page" class="master-holder clear">
	<div class="mk-grid theme-page-wrapper right-layout">
		<div class="theme-content">
			<article id="<?php the_ID(); ?>" itemscope="itemscope" itemtype="https://schema.org/Event" itemprop="event">
				<div class="featured-area">
				<?php if (has_post_thumbnail()): ?>
					<?php the_post_thumbnail('event-medium'); ?>
				<?php else: ?>
				<?php endif; ?>
				</div>
				<address><?php the_field('event_location_name'); ?></address>
				<time>
					<?php echo apply_filters('cnc_format_date_field', get_field('event_date_start', false, false), 'Y.m.d.'); ?>
					<?php echo (!empty(get_field('event_date_end'))) ? ' - ' . apply_filters('cnc_format_date_field', get_field('event_date_end', false, false), 'Y.m.d.') : ''; ?>
				</time>
				<div class="content"><?php the_content( $more_link_text, $strip_teaser ); ?></div>
				<?php $location = get_field('event_location');
				if( !empty($location) ): ?>
					<div class="acf-map">
						<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
					</div>
				<?php endif; ?>
				<?php $related_product = get_field('related_product');
				if( !empty($related_product) ): ?>
					<div class="related-product-container">
						<a href="<?php echo get_permalink($related_product); ?>" class="single_add_to_cart_button shop-skin-btn shop-flat-btn alt related-product-button"><i class="mk-moon-cart-plus"></i><?php echo get_the_title($related_product); ?></a>
					</div>
				<?php endif; ?>
			</article>
		</div>
		<div class="clearboth"></div>
	</div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
