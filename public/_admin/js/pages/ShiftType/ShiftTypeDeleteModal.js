define(
	['jquery', 'myResult'],
	function($, myResult) {
		
		var myResultConfig = {
			enabled: true,
			forms: {
				'form-delete-shift-type': {
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