define(
	['jquery'],
	function($) {
		var signOut = function(page) {
			$('a.sign-out').on('click', function() {
				$.confirm("确认退出登录？", function() {
					//点击确认后的回调函数
					location = '/api/auth/logout?subdomain=guard'
				}, function() {
					//点击取消后的回调函数
				});
			})
		}
		return {
			init: function(pageName, page) {
				signOut(page)
			}
		}
	})