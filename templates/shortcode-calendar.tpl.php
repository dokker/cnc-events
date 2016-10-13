<div class="mk-grid">
<ul class="cnc-events-calendar">
<?php foreach($events_calendar as $daynum => $day): ?>
	<li class="calendar--day<?php echo isset($day['today']) ? ' today' : ''; ?><?php echo isset($day['weekend']) ? ' weekend' : ''; ?><?php echo !empty($day['events']) ? ' event' : ''; ?>">
		<span class="dayname"><?php echo $day['label'] ?></span>
		<span class="daynum"><?php echo $daynum; ?></span>
	</li>
<?php endforeach; ?>
</ul>

<?php
var_dump($events_calendar);
?>
</div>
