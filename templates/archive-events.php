<?php get_header(); ?>
<?php get_template_part('content', 'page'); ?>

<div class="cnc-event-archive">
    <?php if (!have_posts()) : ?>
      <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
      </div>
      <?php // get_search_form(); ?>
    <?php endif; ?>

    <?php
        switch (get_field('cnc-event-archive-style', 'option')) {
        	case 'minimal':
        		echo do_shortcode('[cnc_events_list style="minimal"]');
        		break;
        	case 'simple':
        		echo do_shortcode('[cnc_events_list style="simple"]');
        		break;
        	case 'calendar':
        		echo do_shortcode('[cnc_events_list style="calendar"]');
        		break;
        	default:
        		$list_style = 'minimal';
        		break;
        }
    ?>

    <?php //the_posts_navigation(); ?>
    <?php the_posts_pagination(['mid-size' => 5]); ?>
</div>

<?php get_footer(); ?>
