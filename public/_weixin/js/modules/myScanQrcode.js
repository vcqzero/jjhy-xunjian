define(['jquery', 'myWeiXinJs'], function($, wx) {
	var status_scan_qrcode;
	var status_get_address;
	var status_prompt;
	var data;
	var workyard_id
	var token
	var _url = '/api/shiftTime/add'
	var scaning

	$('body').on('click', '.scan-qrcode', function() {
		if (scaning === true) {
			return false;
		}else {
			scaning = true
		}
		
		var _this = $(this)
		var shift = $('#my-shift-on-working')
		var shift_time_id = shift.attr('data-shift-time-id')
		var shift_id = shift.attr('data-shift-id')
		workyard_id = shift.attr('data-workyard-id')
		token = shift.attr('data-token')
		data = {
			'shift_time_id': shift_time_id,
			'shift_id': shift_id,
		}

		//获取地理位置
		getAddressPath()
		//扫码
		scanQRCode()

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
					var strObj = JSON.parse(str)
					//test
//					var strObj = {
//						'point_id' : '29'
//					}
					//设置二维码未json字符串{point_id : 'point_id'}
					
					var point_id = strObj['point_id']
					if(point_id.length < 1) {
						throw '未查询到巡检点'
					}
					//添加巡检点id
					data['point_id'] = point_id
					status_scan_qrcode = true
					$.toptip('扫码成功', 'success');
					//输入巡检点状况
					prompt()

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
		_url = _url + '&token=' + token
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
			var shift_time_point_id
			if(success) {
				data['shift_time_point_id'] = resObj['shift_time_point_id']
				toSuccessPage(data)
			} else {
				err = err ? err : '数据上传失败'
				$.alert(err, function() {
					location.reload()
				})
			}
		})
	}
	
	var toSuccessPage = function(data) {
		var params = []
		var _url   = '/shiftTime/successPage'
		for(var key in data) {
			var value = data[key]
			var param = key + '=' + value
			params.push(param)
		}
		
		params = params.join('&')
		
		_url = _url + '?' + params
		location = _url
	}

	return {}
})