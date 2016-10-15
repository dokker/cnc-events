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

<?php
var_dump($events_calendar);
?>
</div>
