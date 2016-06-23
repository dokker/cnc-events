<?php get_header(); ?>
<?php get_template_part('content', 'page'); ?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
<?php endwhile; ?>

<?php //the_posts_navigation(); ?>
<?php the_posts_pagination(['mid-size' => 5]); ?>

<?php get_footer(); ?>
