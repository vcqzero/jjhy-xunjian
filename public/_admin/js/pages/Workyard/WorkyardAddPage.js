define(
	['jquery', 'myResult', 'myValidator', 'myGaodemap'],
	function($, myResult, myValidator, myGaodemap) {

		var myGaodemapConfig = {
			container_id: 'map_container',
			callback: function() {
				var mapObj = arguments[0]
				mapObj.plugin(['AMap.MouseTool', 'AMap.PolyEditor'], function() {
					var mouseTool = new AMap.MouseTool(mapObj)
					$('#button-mouse-draw-start').on('click', function() {
						mouseTool.polygon({
							strokeColor: "#FF33FF",
							strokeOpacity: 1,
							strokeWeight: 6,
							strokeOpacity: 0.2,
							fillColor: '#1791fc',
							fillOpacity: 0.4,
							// 线样式还支持 'dashed'
							strokeStyle: "solid",
							// strokeStyle是dashed时有效
							// strokeDasharray: [30,10],
						})
					})
					$('#button-mouse-draw-delete').on('click', function() {
						mapObj.clearMap()
					})

					mouseTool.on('draw', function() {
						var type = arguments[0]['type']
						var obj = arguments[0]['obj']
						var paths = myGaodemap.getPath(obj)
						var _path = JSON.stringify(paths)
						$('input[name="address_path"]').val(_path)
						mouseTool.close()
					})
				})
			}
		}

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-add-workyard': {
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
				'form-add-workyard': {
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
								
								remote: {
									url: '/api/workyard/validName',
									type: 'POST', //以post的方式发生信息
									message: '名称已存在',
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
					
					callback : function() {
						var path = $('input[name="address_path"]').val()
						if (path.length < 1) {
							alert('请在地图上标出工地')
							return false;
						}
						return true;
					}
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