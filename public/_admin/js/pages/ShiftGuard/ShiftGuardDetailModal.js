define(
	['jquery', 'myGaodemap'],
	function($, myGaodemap) {
		var myTrack = function(page) {
			page.on('click', '.show-map-track', function() {
				var _this = $(this)
				var container = _this.parent().find('.map-container').first()
				var map_id
				var map_callback
				var myGaodemapConfig
				var addrss_path
				var close_button = _this.parent().find('.hide-map-track')
				var map_obj
				var panel = _this.closest('.panel')
				var table = panel.find('tbody')
				if(container.length < 1) {
					return
				}
				map_id 		= container.attr('id')
				addrss_path = container.attr('data-address-path')
				addrss_path = JSON.parse(addrss_path)
				if (addrss_path.length < 1) {
					alert('没有巡检数据，无法生成巡检轨迹')
					return
				}
				container.removeClass('hide')
				map_callback = function() {
					var mapObj = arguments[0]
					AMapUI.load(['ui/misc/PathSimplifier'], function(PathSimplifier) {
						if(!PathSimplifier.supportCanvas) {
							alert('当前浏览器不支持查看轨迹，请更换');
							return;
						}
						var pathSimplifierIns = new PathSimplifier({
							zIndex: 100,
							map: mapObj, //所属的地图实例
							getPath: function(pathData, pathIndex) {
								//返回轨迹数据中的节点坐标信息，[AMap.LngLat, AMap.LngLat...] 或者 [[lng|number,lat|number],...]
								return pathData.path;
							},
							getHoverTitle: function(pathData, pathIndex, pointIndex) {
								//返回鼠标悬停时显示的信息
								if(pointIndex >= 0) {
									var path = pathData['path']
									var point_path = path[pointIndex]
									var address_path = JSON.stringify(point_path)
									var trs = table.find('tr')
									var tr
									$.each(trs, function(k, _tr) {
										var _this = $(this)
										if (_this.attr('data-address-path') == address_path) {
											tr = _this
											return false;
										}
									})
									if (tr) {
										var name = tr.find('td.point-name').text()
										var time = tr.find('td.point-time').text()
										var note = tr.find('td.point-note').text()
										return name + '，巡检时间:' + time + '，巡检备注' + note 
									}
									//鼠标悬停在某个轨迹节点上
									return pathData.name + '，点:' + pointIndex + '/' + pathData.path.length;
								}
							},
							renderOptions: {
								//轨迹线的样式
								pathLineStyle: {
									strokeStyle: 'red',
									lineWidth: 6,
									dirArrowStyle: true
								}
							}
						})

						//set data
						pathSimplifierIns.setData([{
							name: '巡检轨迹',
							path: addrss_path
						}]);
						//自动巡航
						var navg0 = pathSimplifierIns.createPathNavigator(0, //关联第1条轨迹
							{
								loop: true, //循环播放
								speed: 800
							});

						navg0.start();
					});
				}

				myGaodemapConfig = {
					container_id: map_id,
					callback: map_callback
				}
				map_obj = myGaodemap.init(page, myGaodemapConfig)
				
				close_button.on('click', function() {
					map_obj.destroy()
					container.addClass('hide')
				})
			})
		}

		return {
			init: function(pageName, page) {
				myTrack(page)
				//				myGaodemap.init(page, myGaodemapConfig)
			}
		}
	})