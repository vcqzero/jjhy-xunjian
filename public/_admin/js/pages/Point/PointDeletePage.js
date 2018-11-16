define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-delete-point': {
					//成功
					success: {
						toast: '删除成功',
						route: 'reload',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				}
			}
		}

		return {
			init: function(pageName, page) {
				myResult.init(page, myResultConfig)
			}
		}
	})