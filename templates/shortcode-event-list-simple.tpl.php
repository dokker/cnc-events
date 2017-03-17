<div class="mk-grid event-listing event-list-simple">
<?php while ($events_query->have_posts()) : $events_query->the_post(); ?>

	<article class="mk-col-3-12 col-md-3 col-sm-4 grid-item <?php echo (apply_filters('cnc_is_before_today', get_field('event_date_start', false, false)) ? 'older' : 'actual'); ?> <?php echo join( ' ', get_post_class() ); ?>">
		<div class="grid-item-inner">
			<div class="grid-item-content event-item dark-div">
				<div class="event-thumbnail">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<img src="<?php echo get_the_post_thumbnail_url(null, 'event-portrait'); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>">
					</a>
				</div>
				<div class="date-block ">
					<div class="month"><?php echo date_i18n('M', strtotime(apply_filters('cnc_format_date_field', get_field('event_date_start', false, false), 'd-m-y'))); ?></div>
					<div class="day"><?php echo apply_filters('cnc_format_date_field', get_field('event_date_start', false, false), 'd'); ?></div>
				</div>
				<div class="event-overlay">
					<a class="overlay-top" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<h4><?php the_title(); ?></h4>
					</a>
					<div class="overlay-bottom">
						<div><?php echo apply_filters('cnc_format_date_field', get_field('event_date_start', false, false), 'H:i'); ?></div>
						<div><?php the_field('event_location_name'); ?></div>
					</div>
				</div>
			</div><!--/event-item-->
		</div><!-- .grid-item-inner -->
	</article>


<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
</div>