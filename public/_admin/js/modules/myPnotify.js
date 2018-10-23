define(['jquery', 'pnotify'], function($, PNotify) {
	var stack_bar_top = {
		"dir1": "down",
		"dir2": "right",
		"push": "top",
		"spacing1": 0,
		"spacing2": 0
	}
	var init = function(type, title, text) {
		if(title === undefined || title.length < 1) {
			return
		}

		var pnotify = new PNotify({
			styling: 'fontawesome',
			title: title,
			text: text ? text : '',
			addclass: "text-center",
			hide: true,
			icon: false,
			delay: 800,
			type: type,
			cornerclass: "",
			width: "100%",
			stack: stack_bar_top
		})
		
		var elem = pnotify.elem
		elem.css('left', '35%')
		elem.css('right', '35%')
		elem.css('top', '0px')
		elem.css('width', 'auto')
	}
	return {
		success: function(title, text) {
			init('success', title, text)
		},

		error: function(title, text) {
			init('error', title, text)
		},

		info: function(title, text) {
			init('info', title, text)
		},

		notice: function(title, text) {
			init('notice', title, text)
		}
	}
})