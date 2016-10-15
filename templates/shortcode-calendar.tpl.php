<div class="cnc-events-calendar">
<h3 class="calendar--title">
	<span class="calendar-text active">Naptár</span> <span class="separator">/</span> <span class="locations-text">Helyszínek</span>
</h3>
<p class="calendar--date"><span class="next-month calendar--pager"><</span><span class="calendar-date-label"><?php echo date_i18n('Y F', time()); ?></span><span class="prev-month calendar--pager">></span></p>
<ul class="cnc-events-calendar">
<?php foreach($events_calendar as $daynum => $day): ?>
	<li class="calendar--day<?php echo isset($day['today']) ? ' today' : ''; ?><?php echo isset($day['weekend']) ? ' weekend' : ''; ?><?php echo !empty($day['events']) ? ' event' : ''; ?>">
		<span class="dayname"><?php echo $day['dow'] ?></span>
		<span class="daynum"><?php echo $daynum; ?></span>
	</li>
<?php endforeach; ?>
</ul>
<div class="calendar--details">
	<?php foreach($events_calendar as $event_day): ?>
		<?php if(!empty($event_day['events'])): ?>
			<div class="calendar--event-day">
				<?php foreach($event_day['events'] as $event): ?>
					<p class="date"><?php the_field('event_date', $event->ID); ?></p>
					<p class="title"><a href="<?php echo get_permalink($event->ID); ?>"><?php echo $event->post_title; ?></a></p>
					<p><?php echo wp_strip_all_tags($event->post_content); ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>

<?php
var_dump($events_calendar);
?>
</div>
