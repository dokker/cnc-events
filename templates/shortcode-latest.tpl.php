<div class="mk-grid">
<?php if($events_query->have_posts()): ?>
	<?php while ($events_query->have_posts()) : $events_query->the_post(); ?>
		<article class="mk-col-3-12 col-md-3 col-sm-4 <?php echo (apply_filters('cnc_is_before_today', get_field('event_date')) ? 'older' : 'actual'); ?> <?php echo join( ' ', get_post_class() ); ?>">
			<div class="entry-wrap">
				<header>
					<address><?php the_field('event_location_name'); ?></address>
					<time class="date">
						<a href="<?php the_permalink(); ?>"><span><?php echo apply_filters('cnc_format_date_field', get_field('event_date'), 'y/m/d'); ?></span></a>
					</time>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php echo apply_filters('cnc_limit_string', get_the_title(), 52); ?></a></h2>
					<time class="time">
						<span><?php echo get_field('event_time'); ?></span>
					</time>
				</header>
			</div>
		</article>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
</div>
