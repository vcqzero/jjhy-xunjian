define(
	['jquery',
	'jquery_validate',
	],
	function($, myForm) {
		var getConfig = function(form_id, _config) {
			var formsConfig = _config['forms']
			if(formsConfig === undefined) {
				return;
			}
			var _formConfig = formsConfig[form_id]
			
			return _formConfig
		}

		var getCallback = function(form_id, config) {
			var formsConfig = config['forms']
			if(formsConfig === undefined) {
				return;
			}
			var _formConfig = formsConfig[form_id]

			if(_formConfig === undefined) {
				return
			}

			return _formConfig['callback']
		}
		
		var addMethod = function(config) {
			var addMethod = config['addMethod']
			if ($.isFunction(addMethod)) {
				addMethod()
			}
		}
		
		var init = function(page, config) {
			var forms = page.find('form')
			if(forms.length < 1) {
				return false;
			}
			addMethod(config)
			$.each(forms, function(k, form) {
				var form = $(this)
				var form_id = form.attr('id')
				if(form_id === undefined) {
					return false
				}
				var form_config = getConfig(form_id, config)
//				var callback = getCallback(form_id, config)
				if(form_config === undefined) {
					return false
				}
				form.validate(form_config)
			})
		}

		return {
			/**
			 * 
			 * @param {Object} pageName
			 */
			init: function(page, config) {
				if(config['enabled']) {
					init(page, config)
				}
			},
		}
	})