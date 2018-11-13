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
//				//获取鼠标画点功能
//				mapObj.plugin(['AMap.MouseTool'], function() {
//					var mouseTool = new AMap.MouseTool(mapObj)
//					//					$('#button-mouse-draw-start').on('click', function() {
//					//						mouseTool.marker({
//					//							draggable: true
//					//						})
//					//					})
//					mouseTool.marker({
//						draggable: true
//					})
//					var setPositon = function(obj) {
//						var _position = obj.getPosition();
//						_position = {
//							lng: _position['lng'],
//							lat: _position['lat']
//						}
//						_position = JSON.stringify(_position)
//						$('input[name="address_position"]').val(_position)
//						console.log(_position)
//					}
//
//					mouseTool.on('draw', function() {
//						var type = arguments[0]['type']
//						var obj = arguments[0]['obj']
//						setPositon(obj)
//						mouseTool.close()
//
//						obj.on('dragend', function() {
//							setPositon(obj)
//						})
//					})
//				})
//			}
//		}

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-add-point': {
					//成功
					success: {
						toast: '添加成功',
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
				'form-add-point': {
					fields: {
						name: {
							validators: {

								notEmpty: {
									message: '请输入巡检点名称',
								},

								stringLength: {
									message: '最长36个字符',
									max: 121,
								},

								remote: {
									url: '/api/point/validName',
									type: 'POST', //以post的方式发生信息
									data: function(validator) {
										return {
											workyard_id: validator.getFieldElements('workyard_id').val()
										};
									},
									message: '名称已存在',
								},
							}
						},

						address: {
							validators: {

								notEmpty: {
									message: '请输入巡检点地址',
								},

								stringLength: {
									message: '最长36个字符',
									max: 121,
								},
							}
						},

//						note: {
//							validators: {
//
//								stringLength: {
//									message: '最长200个字符',
//									max: 512,
//								},
//							}
//						},
					},

//					callback: function(form) {
//						var address_position = $('input[name="address_position"]').val()
//						if(address_position.length < 1) {
//							alert('请标记巡检点')
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