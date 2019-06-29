jQuery(document).ready(function() {
	// list page size
	jQuery('#task_page_size ul').hide().after('<select id="task_page_size_js" />');
	jQuery('#task_page_size a').each(function() {
		var selected = jQuery(this).parent().attr('class') && jQuery(this).parent().attr('class').match('task_selected_page_size');
		jQuery('#task_page_size_js').append('<option value="' + jQuery(this).attr('href') + '"' 
				+ ( selected ? ' selected="selected"' : '' ) + '>'
				+ jQuery(this).text()
				+ '</option>');
	});
	jQuery('#task_page_size_js').bind('change', function () {
		var target = jQuery(this).val();
		jQuery.ajax({
			url: tznfrontjs_vars.plugins_url + '/tasks/ajax-npage.php?target=' + encodeURIComponent(target),
		}).done(function(data) {
			window.location = target;
		});
	});
	// list sort criteria
	jQuery('#task_sort_criteria ul').hide().after('<select id="task_sort_criteria_js" />');
	jQuery('#task_sort_criteria a').each(function() {
		var selected = jQuery(this).parent().attr('class') && jQuery(this).parent().attr('class').match('task_selected_order');
		jQuery('#task_sort_criteria_js').append('<option value="' + jQuery(this).attr('href') + '"' 
				+ ( selected ? ' selected="selected"' : '' ) + '>'
				+ jQuery(this).text()
				+ '</option>');
	});
	jQuery('#task_sort_criteria_js').bind('change', function () {
		window.location = jQuery(this).val();
		return false;
	});
	// view task history toggle
    jQuery('#task_task_history_toggle').addClass('task_task_history_hidden').attr('title', tznfrontjs_vars.task_hist_show);
	jQuery('#task_task_history_toggle').click(function() {
		if (jQuery('#task_task_history_toggle').hasClass('task_task_history_hidden')) {
			var $toggle = jQuery(this);
			$toggle.css('cursor', 'wait');
			jQuery.ajax({
				url: this + '&t=' + (new Date().getTime()),
				}).done(function(data) {
					jQuery('#task_task_history').replaceWith(jQuery('#task_task_history', data));
                    jQuery('#task_task_history_toggle').removeClass('task_task_history_hidden').attr('title', tznfrontjs_vars.task_hist_hide);
			}).always(function() {
				$toggle.css('cursor', 'pointer');
				jQuery('#task_task_history').show();
			});
		} else {
			jQuery('#task_task_history').hide();
            jQuery('#task_task_history_toggle').addClass('task_task_history_hidden').attr('title', tznfrontjs_vars.task_hist_show);
		}
		return false;
	});
	// view comment file upload
	jQuery('.task_file_more').click(function() {
		jQuery(this).hide();
		jQuery(this).parent().next().show();
		return false;
	});
	jQuery('input[type=file]').change(task_file_input_reset);
	jQuery('a[rel*=external]').click(function(){
		window.open(jQuery(this).attr('href'));
		return false; 
	});
	// list status changer
	jQuery('#task_tasksheet .task_sts a').attr('href', function(i,h) { return h + '&js=1'; });
	jQuery('#task_tasksheet .task_sts a').click(function() {
		var $clicked_a = jQuery(this);
		var clicked_a = { 'id': $clicked_a.attr('id').substr(9), 'level': $clicked_a.attr('id').substr(7,1) };
		jQuery('#task_col4-' + clicked_a['id']+ ' a').css('cursor', 'wait');
		jQuery.ajax({
			url: this + '&t=' + (new Date().getTime()),
			}).done(function(data) {
                                var m = data.match("<!-- TF!WP_status_change_result : (.*?) -->");
                                if (m) {
					var extracted_message = m[1].substr(4);
					if (m[1].substr(0,2) == 'OK') {
						for (var i = 1; i <= clicked_a['level']; i++) {
							jQuery('#task_sts' + i + '-' + clicked_a['id']).addClass('task_sts1').removeClass('task_sts0');
						}
						for (var i = parseInt(clicked_a['level']) + 1; i <= 3; i++) {
							jQuery('#task_sts' + i + '-' + clicked_a['id']).addClass('task_sts0').removeClass('task_sts1');
						}
						jQuery('#task_col4-' + clicked_a['id']+ ' .task_sts_lbl').text(extracted_message);
					} else {
						alert(extracted_message);
					}
				} else {
					alert(tznfrontjs_vars.error_message); // unknown error
				}
		}).complete(function() {
			jQuery('#task_tasksheet .task_sts a').css('cursor', 'pointer');
		});
		return false; 
	});
	// view status changer
	jQuery('#task_task_details .task_sts a').attr('href', function(i,h) { return h + '&js=1'; });
	jQuery('#task_task_details .task_sts a').click(function() {
		var $clicked_a = jQuery(this);
		var clicked_a = { 'id': $clicked_a.attr('id').substr(9), 'level': $clicked_a.attr('id').substr(7,1) };
		jQuery('#task_task_details .task_sts a').css('cursor', 'wait');
		jQuery.ajax({
			url: this + '&t=' + (new Date().getTime()),
			}).done(function(data) {
                                var m = data.match("<!-- TF!WP_status_change_result : (.*?) -->");
                                if (m) {
					var extracted_message = m[1].substr(4);
					if (m[1].substr(0,2) == 'OK') {
						for (var i = 1; i <= clicked_a['level']; i++) {
							jQuery('#task_sts' + i + '-' + clicked_a['id']).addClass('task_sts1').removeClass('task_sts0');
						}
						for (var i = parseInt(clicked_a['level']) + 1; i <= 3; i++) {
							jQuery('#task_sts' + i + '-' + clicked_a['id']).addClass('task_sts0').removeClass('task_sts1');
						}
						jQuery('#task_task_details .task_sts_lbl').text(extracted_message);
						if (!jQuery('#task_task_history_toggle').hasClass('task_task_history_hidden')) {
                                                        jQuery('#task_task_history_toggle').addClass('task_task_history_hidden').attr('title', tznfrontjs_vars.task_hist_show);
							jQuery('#task_task_history').hide();
						}
					} else {
						alert(extracted_message);
					}
				} else {
					alert(tznfrontjs_vars.error_message); // unknown error
				}
		}).complete(function () {
			jQuery('#task_task_details .task_sts a').css('cursor', 'pointer');
		});
		return false; 
	});
	// task edit
	jQuery('#task_deadline_date').datepicker({ dateFormat: tznfrontjs_vars.datepicker_format });
	jQuery('#task_cal_btn').click(function() { 
		jQuery('#task_deadline_date').datepicker('show'); 
	});
	task_update_select_prio_color();
	jQuery('#task_select_prio').change(task_update_select_prio_color);
	jQuery('#task_project').change(task_project_change);
	if (jQuery('#task_project').length > 0) {
		task_project_change.apply(jQuery('#task_project'));
	}
});

function task_project_change() {
	selected_user = jQuery('#task_user_id option[selected]').val();
	jQuery.ajax({
		url: jQuery(this).data('ajax') + '&user=' + selected_user + '&proj=' + jQuery(this).val() + '&t=' + (new Date().getTime()),
	}).done(function(data) {
		// neither jQuery('#task_corresponding_users option', data) nor jQuery('option', data) work on Android 2
		if (jQuery('option', data).length > 0) {
			jQuery('#task_user_id').html(jQuery('#task_corresponding_users option', data));
		}
	});
}

function task_file_input_reset() {
	jQuery(this).after('<a href="#" class="task_file_reset">×</a>');
	jQuery('.task_file_reset').click(function() {
		jQuery(this).hide();
		$file_input = jQuery(this).prev();
		$file_input_2 = jQuery('<input type="file" name="' + $file_input.attr('name') + '">');
		$file_input_2.change(task_file_input_reset);
		$file_input.after($file_input_2).remove();
		return false;
	});
}

function task_update_select_prio_color() {
	jQuery('#task_select_prio_color').removeClass().addClass('task_pr' + jQuery('#task_select_prio option:selected').val());
}
