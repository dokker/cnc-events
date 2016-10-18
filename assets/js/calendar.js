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

	function get_month_data(year, month) {
        $.post(cnc_cal_ajax_obj.ajax_url, {         //POST request
           _ajax_nonce: cnc_cal_ajax_obj.nonce,     //nonce
            action: "get_calendar_month",            //action
            year: year,
            month: month
        }, function(data) {                    //callback
        	console.log(data);
        });
	}

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
