(function($) {
    function scrollToElement ($target_element, duration, offset) {
      var destination = $target_element.offset().top - offset;
      var itemHeight = $target_element.height();
      $("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination}, duration, "easeInOutCubic");
    }

	function hide_day_events() {
		$('.calendar--event-day').hide();
	}

	function show_day_event(daynum) {
		$('.calendar--details *[data-daynum="' + daynum + '"]').show(0, function() {
			$scrollTarget = $('.calendar--details');
			scrollToElement($scrollTarget, 500, 300);
		});
	}
	$(".cnc-events-calendar .calendar--day").click(function() {
		if($(this).hasClass('active') == false) {
			$('.cnc-events-calendar .calendar--day').removeClass('active');
			$(this).addClass('active');
			hide_day_events();
			var daynum = $(this).data('daynum');
			show_day_event(daynum);
		}
	});
})(jQuery);
