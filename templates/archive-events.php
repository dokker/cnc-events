<?php get_header(); ?>
<?php get_template_part('content', 'page'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<div class="mk-grid">
<?php while (have_posts()) : the_post(); ?>
	<article class="mk-col-3-12 col-md-3 col-sm-4 <?php echo (apply_filters('cnc_is_before_today', get_field('event_date_start', false, false)) ? 'older' : 'actual'); ?> <?php echo join( ' ', get_post_class() ); ?>">
		<div class="entry-wrap">
			<header>
				<address><?php the_field('event_location_name'); ?></address>
				<time class="date">
					<h2><a href="<?php the_permalink(); ?>"><span><?php echo apply_filters('cnc_format_date_field', get_field('event_date_start', false, false), 'y/m/d'); ?></span></a></h2>
				</time>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php echo apply_filters('cnc_limit_string', get_the_title(), 70); ?></a></h2>
				<time class="time">
					<span><?php echo get_field('event_time'); ?></span>
				</time>
			</header>
		</div>
	</article>
<?php endwhile; ?>
</div>

<?php //the_posts_navigation(); ?>
<?php the_posts_pagination(['mid-size' => 5]); ?>

<?php get_footer(); ?>
