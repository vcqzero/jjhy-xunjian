define(
	['jquery'],
	function($) {
		//巡检记录
		var myLogShift = {
			bindButton : function(page) {
				$('a.log-shift').on('click', function() {
					var _this = $(this)
					var shift_id = _this.attr('data-shift-id')
					var done_count = _this.attr('data-done-count')
					done_count = parseInt(done_count)
					if (done_count < 1) {
						$.alert("无巡检记录")
						return
					}
					
					myLogShift.getPopupByAjax(shift_id, done_count)
				})
			},
			
			getPopupByAjax : function(shift_id, done_count) {
				var _url = '/shift/logShiftPopup'
				var data = {
					shift_id : shift_id,
					done_count : done_count
				}
				$.ajax({
					type:"post",
					url : _url,
					data : data,
					async:true,
				}).done(function(popup_content) {
					var _pupup  = $('#log-shift-popup')
					var content = _pupup.find('div.weui-popup__modal_content')
					content.replaceWith(popup_content)
					_pupup.popup()
				})
			},
			
		}
		return {
			init: function(pageName, page) {
				myLogShift.bindButton(page)
			}
		}
	})