(function($) {
	/**
	 * Scroll window to given position
	 * @param  {object} $target_element DOM object
	 * @param  {int} duration        Animation duration in milliseconds
	 * @param  {int} offset          Offset of the end position
	 */
    function scrollToElement ($target_element, duration, offset) {
      var destination = $target_element.offset().top - offset;
      var itemHeight = $target_element.height();
      $("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination}, duration, "easeInOutCubic");
    }

    /**
     * Hide event days
     */
	function hide_day_events() {
		$('.calendar--event-day').hide();
	}

	/**
	 * Show event details for the given day
	 * @param  {int} daynum Number of the day
	 */
	function show_day_event(daynum) {
		$('.calendar--details *[data-daynum="' + daynum + '"]').show(0, function() {
			$scrollTarget = $('.calendar--details');
			scrollToElement($scrollTarget, 500, 300);
		});
	}
	$(".cnc-events-calendar").on('click', '.calendar--day', function() {
		if($(this).hasClass('active') == false) {
			$('.cnc-events-calendar .calendar--day').removeClass('active');
			$(this).addClass('active');
			hide_day_events();
			var daynum = $(this).data('daynum');
			show_day_event(daynum);
		}
	});

	/**
	 * Start the AJAX loader
	 */
	function loader_start() {
		$('.calendar--overlay').fadeTo("default", 1);
		$('.cnc-events-calendar').fadeTo("default", 0.2);
	}

	/**
	 * Stop the AJAX loader
	 */
	function loader_stop() {
		$('.calendar--overlay').fadeOut("default");
		$('.cnc-events-calendar').fadeTo("default", 1);
	}

	/**
	 * Render the HTML markup of the calendar
	 * @param  {array} days Raw data of days
	 */
	function render_calendar(days) {
		var $calendar = $('.calendar--days');
		$calendar.empty();
		var $calendar_details = $('.calendar--details');
		$calendar_details.empty();
		$.each(days, function(num, day) {

			// Work with calendar
			$day= $('<li class="calendar--day"></li>');
			$day.attr('data-daynum', num);
			if (day.today == true) {
				$day.addClass('today');
			}
			if (day.weekend == true) {
				$day.addClass('weekend');
			}
			if (day.events.length > 0) {
				$day.addClass('event');
			}
			$day.append('<span class="dayname">' + day.dow + '</span>');
			$day.append('<span class="daynum">' + num + '</span>');
			$calendar.append($day);

			// Work with calendar details
			$event_day = $('<ul class="calendar--event-day"></ul>');
			$event_day.attr('data-daynum', num);
			$.each(day.events, function(num, event) {
				$event = $('<li></li>');
				$event.append(
					'<p class="date">' + event.c_date_start + ' - '
					+ event.c_date_end  + '</p>'
					);
				$event.append(
					'<p class="title"><a href="' + event.c_permalink + '">'
					+ event.post_title + '</a></p>'
					);
				$event.append('<p>' + event.c_excerpt + '</p>');
				$event_day.append($event);
			})
			$calendar_details.append($event_day);
		});
	}

	/**
	 * Handle the month change
	 * @param  {array} data AJAX result of 'get month' call
	 */
	function handle_month_change(data) {
		$('.cnc-events-calendar .calendar-date-label').text(data.date_label);
		cnc_cal_ajax_obj.year = data.year_new;
		cnc_cal_ajax_obj.month = data.month_new;
		render_calendar(data.days);
	}

	/**
	 * Get month data with AJAX call
	 * @param  {int} year  Year
	 * @param  {int} month Month
	 */
	function get_month_data(year, month) {
		loader_start();
        $.post(cnc_cal_ajax_obj.ajax_url, {         //POST request
           _ajax_nonce: cnc_cal_ajax_obj.nonce,     //nonce
            action: "get_calendar_month",            //action
            year: year,
            month: month
        }, function(data) {                    //callback
        	if (data.success == true) {
        		loader_stop();
	        	handle_month_change(data);
        	}
        }, 'json');
	}

	/**
	 * Handle month cahnge to next
	 */
	$('.next-month.calendar--pager').click(function() {
		var year = parseInt(cnc_cal_ajax_obj.year);
		var month = parseInt(cnc_cal_ajax_obj.month, 10);
		if (month < 12) {
			year_new = year;
			month_new = month + 1;
		} else {
			year_new = year + 1;
			month_new = 1;
		}
		get_month_data(year_new, month_new);
	});

	/**
	 * Handle month cahnge to previous
	 */
	$('.prev-month.calendar--pager').click(function() {
		var year = parseInt(cnc_cal_ajax_obj.year);
		var month = parseInt(cnc_cal_ajax_obj.month, 10);
		if (month == 1) {
			year_new = year - 1;
			month_new = 12;
		} else {
			year_new = year;
			month_new = month - 1;
		}
		get_month_data(year_new, month_new);
	});

})(jQuery);
