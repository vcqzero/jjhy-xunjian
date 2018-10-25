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

//		var changePassword = function(page) {
//			$('a.change-password').on('click', function() {
//				$.login({
//					title: '修改密码',
////					text: '内容文案',
//					password: '请输入新密码', // 默认用户名
//					password: '请再次输入新密码', // 默认密码
//					onOK: function(username, password) {
//						//点击确认
//					},
//					onCancel: function() {
//						//点击取消
//					}
//				});
//			})
//
//		}
		return {
			init: function(pageName, page) {
				signOut(page)
//				changePassword(page)
			}
		}
	})