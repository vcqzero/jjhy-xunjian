define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {
//		var myGaodemapConfig = {
//			container_id: 'map_container',
//			callback: function() {
//				var mapObj = arguments[0]
//
//				//获取工地地图标记 start
//				var workyard_address_path = $('#workyard_address_path').text()
//				if(workyard_address_path == '') {
//					return
//				}
//				workyard_address_path = JSON.parse(workyard_address_path)
//				mapObj.plugin(['AMap.PolyEditor'], function() {
//					var polygon = new AMap.Polygon({
//						map: mapObj,
//						path: workyard_address_path,
//						isOutline: true,
//						borderWeight: 3,
//						strokeColor: "#FF33FF",
//						strokeWeight: 6,
//						strokeOpacity: 0.2,
//						fillOpacity: 0.4,
//						// 线样式还支持 'dashed'
//						fillColor: '#1791fc',
//						zIndex: 50,
//					})
//
//					// 缩放地图到合适的视野级别
//					mapObj.setFitView([polygon])
//				})
//				//获取工地地图标记 end
//
//				//显示坐标点
//				var point_position_input = $('input[name="address_position"]')
//				var point_positon = point_position_input.val()
//				console.log(point_positon)
//				point_positon = JSON.parse(point_positon)
//				var marker = new AMap.Marker({
//					map: mapObj,
//					position: [point_positon['lng'], point_positon['lat']], // 经纬度对象，也可以是经纬度构成的一维数组[116.39, 39.9]
//					title: '北京',
//					draggable: true,
//				});
//
//				marker.on('dragend', function() {
//					var _position = marker.getPosition();
//					_position = {
//						lng: _position['lng'],
//						lat: _position['lat']
//					}
//					_position = JSON.stringify(_position)
//					point_position_input.val(_position)
//				})
//				
//			}
//		}

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-edit-point': {
					//成功
					success: {
						toast: '修改成功',
						route: '/point',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				}
			}
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-edit-point': {
					fields: {
						name: {
							validators: {

								notEmpty: {
									message: '请输入巡逻点名称',
								},

								stringLength: {
									message: '最长6个字符',
									max: 6,
								},

								remote: {
									url: '/api/point/validName',
									type: 'POST', //以post的方式发生信息
									data: function(validator) {
										return {
											point_id: validator.getFieldElements('point_id').val(),
											workyard_id: validator.getFieldElements('workyard_id').val(),
										};
									},
									message: '名称已存在',
								},
							}
						},

						address: {
							validators: {

								notEmpty: {
									message: '请输入巡逻点地址',
								},

								stringLength: {
									message: '最长17个字符',
									max: 17,
								},
							}
						},
					},

//					callback: function(form) {
//						var address_position = $('input[name="address_position"]').val()
//						if(address_position.length < 1) {
//							alert('请标记巡逻点')
//							return false;
//						}
//						return true;
//					}
				},
			},

		}

		return {
			init: function(pageName, page) {
//				myGaodemap.init(page, myGaodemapConfig)
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})