define(
	['jquery', 'jquery-weui'],
	function($) {
		var clientHeight = $('div.page')[0].clientHeight
		var navbarHeight = $('div.weui-navbar')[0].clientHeight
		var tarbarHeight = $('div.weui-tabbar')[0].clientHeight
		var div_infinite = $('.my-infinite');
		var div_infinite_height = clientHeight - navbarHeight - tarbarHeight
		div_infinite.css('height', div_infinite_height)
		var loading = false
		$('.my-infinite').infinite(50).on("infinite", function() {
			if (loading) {
				return
			}
			loading = true
			var _this = $(this)
			var list = _this.find('div.my-infinite-list')
			var last_list_cell = list.find('div.my-infinite-list-cell').last()
			var workyard_id    = list.attr('data-workyard-id')
			var user_id        = list.attr('data-user-id')
			var type           = list.attr('data-type')
			var page_current   = last_list_cell.attr('data-page-current')
			var page_count     = last_list_cell.attr('data-page-count')
			var page_next
			var data
			var div_loading = $('.weui-loadmore-loading')
			var div_loading_done = $('.weui-loadmore-done')
			page_current = parseInt(page_current)
			page_count   = parseInt(page_count)
			if(page_current >= page_count) {
				div_loading_done.removeClass('hidden')
				_this.destroyInfinite()
				return false;
			}
			page_next = page_current + 1
			data = {
				'page' : page_next,
				'workyard_id' : workyard_id,
				'user_id' : user_id,
			}
			$.ajax({
				type:"post",
				url :"/shift/paginator?type=" + type,
				async:true,
				data : data,
				beforeSend : function() {
					div_loading.removeClass('hidden')
				}
			}).done(function(paginator) {
				list.append(paginator)
				loading = false
				div_loading.addClass('hidden')
			})
		})
		return {
			init: function(pageName, page) {}
		}
	})