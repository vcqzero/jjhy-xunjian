define(['jquery'], function($) {
	var init = function(page, config) {
		$('body').on('form-ajax-submit:complete', 'form', function(e) {
			var resObj = arguments[1]['resObj']
			var form = $(e.currentTarget)
			var form_id = form.attr('id')
			var _config = config['forms'][form_id]
			if(_config) {
				doResult(resObj, _config)
			} else {
				console.log('ERROR 未找到表单提交之后执行的配置文件信息，请确保已配置')
			}
			return
		})
	}

	var isModal = function(page) {
		return page.hasClass('modal')
	}

	var doResult = function(resObj, _config) {
		var isSuccess = resObj['success']
		var resultName = isSuccess ? 'success' : 'error'
		var resultConfig = _config[resultName]
		var callback = resultConfig['callback']
		var toast = resultConfig['toast']
		var route = resultConfig['route']

		//do callback 
		if($.isFunction(callback)) {
			callback(resObj)
			return
		}

		//show toast
		if(isSuccess) {
			$.toptip(toast, 'success');
		} else {
			$.toptip(toast, 'error');
		}
		setTimeout(
			function() {
				if(route == 'reload') {
					location.reload()
					return
				}
				if(route == 'back') {
					history.back()
					return
				}
				location = route
			},
			1500
		)
	}

	return {
		/**
		 * 
		 * @param {Object} pageName
		 */
		init: function(page, config) {
			if(config['enabled']) {
				init(page, config)
				console.log('PAGE myResult->init success')
			}
		},
	}
})