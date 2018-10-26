define(['jquery', 'myWeiXinJs'], function($, wx) {
	var status_scan_qrcode;
	var status_get_address;
	var status_prompt;
	var data;
	var workyard_id
	var _url = '/api/shiftTime/add'

	$('body').on('click', '.scan-qrcode', function() {
		var _this = $(this)
		var shift = $('#my-shift-on-working')
		var shift_time_id = shift.attr('data-shift-time-id')
		workyard_id = shift.attr('data-workyard-id')
		data = {
			'shift_time_id': shift_time_id,
		}

		//获取地理位置
		getAddressPath()
		//扫码
		scanQRCode()
		//输入巡检点状况
		prompt()

		//test
//		data['point_id'] = 8
//		data['point_id'] = 11
//		data['point_id'] = 12
//		data['point_id'] = 13
//		data['point_id'] = 14
//		data['point_id'] = 1 //巡检点无效
//		status_scan_qrcode = true

	})

	var trigger = function() {
		if(status_scan_qrcode !== true) {
			return false;
		}
		if(status_get_address !== true) {
			return false;
		}
		if(status_prompt !== true) {
			return false;
		}
		send()
	}

	var prompt = function() {
		$.prompt({
			title: '巡检状况',
			text: '选填：可输入巡检状况',
			empty: true, // 是否允许为空

			onOK: function(_input) {
				//点击确认
				data['note'] = _input
				status_prompt = true
				trigger()
			},

			onCancel: function() {
				//点击取消
				status_prompt = true
				trigger()
			}

		});
	}

	var scanQRCode = function() {
		wx.scanQRCode({
			needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
			scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有

			fail: function(errMsg) {
				//				console.log(errMsg)
				$.alert('扫码失败,请确保微信有调用摄像头权限', function() {
					status_scan_qrcode = false
					location.reload()
				});
			},

			success: function(res) {
				try {
					var str = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
					//设置二维码未json字符串{point_id : 'point_id'}
					var strObj = JSON.parse(res)
					var point_id = strObj['point_id']
					if(point_id.length < 1) {
						throw '未查询到巡检点'
					}
					//添加巡检点id
					data['point_id'] = point_id
					status_scan_qrcode = true
					trigger()

				} catch(err) {
					$.alert('扫码失败', function() {
						status_scan_qrcode = false
						location.reload()
					})
				}
			},

		})
	}

	var getAddressPath = function() {
		wx.getLocation({
			type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
			fail: function(errMsg) {
				$.alert('未获取到位置，请确保微信有获取位置权限', function() {
					status_get_address = false
					location.reload()
				});
			},

			success: function(res) {
				var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
				var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
				var speed = res.speed; // 速度，以米/每秒计
				var accuracy = res.accuracy; // 位置精度
				var address_path = [longitude, latitude];
				address_path = JSON.stringify(address_path)
				data['address_path'] = address_path
				status_get_address = true
				trigger()
			}
		})
	}
	/**
	 * 发送巡检信息到服务器
	 * 
	 */
	var send = function() {
		_url = _url + '?workyard_id=' + workyard_id
		$.ajax({
			type: "post",
			url: _url,
			data: data,
			async: true,
			error: function() {
				$.alert('服务器异常，上传失败', function() {
					location.reload()
				})
			},

			beforeSend: function() {
				console.log('send...')
			},

		}).done(function(res) {
			var resObj = JSON.parse(res)
			var success = resObj['success']
			var err = resObj['err']
			if(success) {
				$.toast("数据上传成功", function() {
					location.reload()
				});
			} else {
				err = err ? err : '数据上传失败'
				$.alert(err, function() {
					location.reload()
				})
			}
		})
	}

	return {}
})