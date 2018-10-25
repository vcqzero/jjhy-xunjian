define(['jquery'], function($) {
	var init = function(type) {
		$('body').on('click', '.scan-qrcode', function() {
			var _this = $(this)
			console.log('点击了扫码')
			var shift    = $('#my-shift-on-working')
			var has_done
			var shift_id
			var shift_time_id
			var guard_id
			
			if (shift.length < 1){
				$.alert("无值班安排，不需扫码");
				return
			}
			has_done = shift.attr('data-has-done')
			if (has_done == 'done'){
				$.alert("已完成巡检，不需扫码");
				return
			}
			
			shift_id = shift.attr('data-shift-id')
			shift_time_id = shift.attr('data-shift-time-id')
			guard_id = shift.attr('data-guard-id')
			workyard_id = shift.attr('data-workyard-id')
			
			console.log(shift_id)
			console.log(workyard_id)
			console.log(shift_time_id)
			console.log(guard_id)
			console.log('需要调用微信扫描功能进行扫描，需要保证扫描的巡检点二维码是工地的')
		})
	}
	init()
	return {
	}
})