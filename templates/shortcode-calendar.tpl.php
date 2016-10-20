<div class="cnc-events-calendar">
<h3 class="calendar--title">
	<span class="calendar-text active">Naptár</span> <span class="separator">/</span> <span class="locations-text">Helyszínek</span>
</h3>
<p class="calendar--date"><span class="prev-month calendar--pager"><</span><span class="calendar-date-label"><?php echo $events_calendar['date_label']; ?></span><span class="next-month calendar--pager">></span></p>
<ul class="calendar--days">
<?php foreach($events_calendar['days'] as $daynum => $day): ?>
	<li data-daynum="<?php echo $daynum; ?>"
		class="calendar--day<?php echo isset($day['today']) ? ' today' : ''; ?>
		<?php echo isset($day['weekend']) ? ' weekend' : ''; ?>
		<?php echo !empty($day['events']) ? ' event' : ''; ?>">
		<span class="dayname"><?php echo $day['dow'] ?></span>
		<span class="daynum"><?php echo $daynum; ?></span>
	</li>
<?php endforeach; ?>
</ul>
<div class="calendar--details">
	<?php foreach($events_calendar['days'] as $daynum => $event_day): ?>
		<?php if(!empty($event_day['events'])): ?>
			<ul data-daynum="<?php echo $daynum; ?>" class="calendar--event-day">
				<?php foreach($event_day['events'] as $event): ?>
					<li>
					<p class="date"><?php echo $event->c_date_start; ?> - <?php echo $event->c_date_end; ?></p>
					<p class="title"><a href="<?php echo $event->c_permalink; ?>"><?php echo $event->post_title; ?></a></p>
					<p><?php echo $event->c_excerpt; ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php endforeach; ?>
</div><!-- .calendar--details -->
<div class="calendar--overlay"></div>
</div><!-- .cnc-events-calendar -->
