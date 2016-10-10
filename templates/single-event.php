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
					<?php echo apply_filters('cnc_format_date_field', get_field('event_date', false, false), 'Y.m.d.'); ?> -
					<?php echo get_field('event_time'); ?>
					<?php echo (!empty(get_field('event_date_end'))) ? ' - ' . apply_filters('cnc_format_date_field', get_field('event_date_end'), 'Y.m.d.') : ''; ?>
				</time>
				<div class="content"><?php the_content( $more_link_text, $strip_teaser ); ?></div>
				<?php $location = get_field('event_location');
				if( !empty($location) ): ?>
					<div class="acf-map">
						<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
					</div>
				<?php endif; ?>
			</article>
		</div>
		<aside id="mk-sidebar" class="mk-builtin">
			<div class="sidebar-wrapper">
				<?php if (is_active_sidebar('sidebar-1')): ?>
					<?php dynamic_sidebar('sidebar-1'); ?>
				<?php endif; ?>
			</div>
		</aside>
		<div class="clearboth"></div>
	</div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
