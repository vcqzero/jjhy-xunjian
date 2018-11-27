define(
	['jquery', 'myScanQrcode'],
	function($) {

		var myAlertPointInfo = function(page) {
			var a_points = page.find('a.alert-point-info')
			a_points.on('click', function() {
				var _this = $(this)
				var point_name = _this.text()
				var address = _this.attr('data-point-address')
				var time = _this.attr('data-point-time')
				var note = _this.attr('data-point-note')
				var content = '<p>地址：' + address + '</p>'
				content = time ? content + '<p>巡逻时间：' + time + '</p>' : content;
				content = note ? content + '<p>备注：' + note + '</p>' : content;
				$.alert(content, point_name);
			})
		}

		return {
			init: function(pageName, page) {
				myAlertPointInfo(page)
			}
		}
	})