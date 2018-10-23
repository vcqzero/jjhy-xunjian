define(
	['jquery', 'myResult', 'myValidator', 'myGaodemap'],
	function($, myResult, myValidator, myGaodemap) {
		var myGaodemapConfig = {
			container_id: 'map_container',
			callback: function() {
				var mapObj = arguments[0]
				var inputAddressPath = $('input[name="address_path"]')
				var path = inputAddressPath.val()
				path = JSON.parse(path)
				mapObj.plugin(['AMap.PolyEditor'], function() {
					var polygon = new AMap.Polygon({
						map: mapObj,
						path: path,
						isOutline: true,
						borderWeight: 3,
						strokeColor: "#FF33FF",
						strokeWeight: 6,
						strokeOpacity: 0.2,
						fillOpacity: 0.4,
						// 线样式还支持 'dashed'
						fillColor: '#1791fc',
						zIndex: 50,
					})
					
					// 缩放地图到合适的视野级别
					mapObj.setFitView([polygon])

					var polyEditor = new AMap.PolyEditor(mapObj, polygon)
					
					$('#button-edit-open').on('click', function() {
						polyEditor.open()
					})

					$('#button-edit-close').on('click', function() {
						polyEditor.close()
					})
					
					var setInputValue = function(obj) {
						var paths = myGaodemap.getPath(obj)
						var _path = JSON.stringify(paths)
						inputAddressPath.val(_path)
					}
					polyEditor.on('addnode', function(event) {
						var obj = event.target
						setInputValue(obj)
					})

					polyEditor.on('adjust', function(event) {
						var obj = event.target
						setInputValue(obj)
					})

					polyEditor.on('removenode', function(event) {
						var obj = event.target
						setInputValue(obj)
					})

					polyEditor.on('end', function(event) {
						var obj = event.target
						setInputValue(obj)
					})
				})
			}
		}

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-edit-workyard': {
					//成功
					success: {
						toast: '操作成功',
						route: '/workyard',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				},
			},
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-edit-workyard': {
					fields: {
						name: {
							validators: {
								notEmpty: {
									message: '请输入名称',
								},

								stringLength: {
									message: '最长36个字符',
									max: 124,
								},
							}
						},

						address: {
							validators: {
								notEmpty: {
									message: '请输入具体地址',
								},

								stringLength: {
									message: '最长256个字符',
									max: 80,
								},
							}
						},

						note: {
							validators: {
								stringLength: {
									message: '最长160个字符',
									max: 512,
								},
							}
						},
					},
				},
			},
		}

		return {
			init: function(pageName, page) {
				myGaodemap.init(page, myGaodemapConfig)
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})