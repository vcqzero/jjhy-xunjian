define(
	['jquery', 'myGaodemap'],
	function($, myGaodemap) {
		var myGaodemapConfig = {
			container_id: 'map_container',
			callback: function() {
//				var mapObj = arguments[0]
//				mapObj.plugin(['AMap.MouseTool', 'AMap.PolyEditor'], function() {
//					var mouseTool = new AMap.MouseTool(mapObj)
//					$('#button-mouse-draw-start').on('click', function() {
//						mouseTool.polygon({
//							strokeColor: "#FF33FF",
//							strokeOpacity: 1,
//							strokeWeight: 6,
//							strokeOpacity: 0.2,
//							fillColor: '#1791fc',
//							fillOpacity: 0.4,
//							// 线样式还支持 'dashed'
//							strokeStyle: "solid",
//							// strokeStyle是dashed时有效
//							// strokeDasharray: [30,10],
//						})
//					})
//					$('#button-mouse-draw-delete').on('click', function() {
//						mapObj.clearMap()
//					})
//
//					mouseTool.on('draw', function() {
//						var type = arguments[0]['type']
//						var obj = arguments[0]['obj']
//						var paths = myGaodemap.getPath(obj)
//						var _path = JSON.stringify(paths)
//						$('input[name="address_path"]').val(_path)
//						mouseTool.close()
//					})
//				})
			}
		}

		return {
			init: function(pageName, page) {
				myGaodemap.init(page, myGaodemapConfig)
			}
		}
	})