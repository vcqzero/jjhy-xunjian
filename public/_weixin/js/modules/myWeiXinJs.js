define(['jquery', 'jweixin'], function($, wx) {
	var _debug = false
	var current_page_url = location.href
	var data = {
		'url': current_page_url
	}
	var jsApiList = [
	'checkJsApi', 
	'scanQRCode', 
	'getLocation',
	]
	
	$.ajax({
		type: "post",
		url: '/api/weixin/getWxConfig',
		data: data,
		async: true
	}).done(function(_config) {
		var config = JSON.parse(_config)
		config['debug'] = _debug 
		config['jsApiList'] = jsApiList
		wx.config(config)
		wx.ready(function() {
			// config信息验证后会执行ready方法，
			//所有接口调用都必须在config接口获得结果之后，
			//config是一个客户端的异步操作，
			//所以如果需要在页面加载时就调用相关接口，
			//则须把相关接口放在ready函数中调用来确保正确执行。
			//对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
//			console.log('微信接口配置成功，可进行接口调用')
		});
		wx.error(function(res) {
			// config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
			console.log('微信接口配置失败')
			
		});
	})

	return wx
})