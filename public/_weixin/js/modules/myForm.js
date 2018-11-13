define(['jquery'], function($) {
	var EVENT_BEFORE = 'form-ajax-submit:before'
	var EVENT_COMPLETE = 'form-ajax-submit:complete'
	var CLASS_AJAX_FORM = 'form-ajax-submit'

	var getActionUrl = function(form) {
		return form.attr('action')
	}

	var hasData = function(form) {
		var datas = form.serializeArray()
		var hasData = false
		for(var key in datas) {
			var data = datas[key]
			if(data['value'].length > 0) {
				hasData = true
				break
			}
		}

		return hasData
	}

	var doSubmit = function(form) {
		var url = getActionUrl(form)
		var data = form.serialize()
		console.log(data)
		$.ajax({
			type: "post",
			data: data,
			url: url,
			async: true,
			beforeSend: function() {
				if(hasData(form) !== true) {
					console.log('form is empty do not need submit')
					return false;
				} else {
					form.trigger(EVENT_BEFORE)
					disabledSubmitButton(form, true)
					console.log('The form is submitting on ajax')
				}
			},

			error: function() {
				$.toast("操作错误！", "cancel", function() {
					location.reload()
				});
				
			},

		}).done(function(res) {
			var resObj = JSON.parse(res)
			form.trigger(EVENT_COMPLETE, {
				'resObj': resObj
			})
		});
		
	}

	var disabledSubmitButton = function(form, disabled) {
		var submitButton = form.find('button[type="submit"]')
		submitButton.attr('disabled', disabled === true)
		submitButton.text('处理中...')
		submitButton.addClass('weui-btn_disabled')
	}
	
	$('body').on('submit', 'form', function(e) {
		var form = $(this)
		e.preventDefault()
		if(form.hasClass(CLASS_AJAX_FORM)) {
			doSubmit(form)
		}
	})
	return {
		doSubmit: function(form) {
			doSubmit(form)
		}
	}
})