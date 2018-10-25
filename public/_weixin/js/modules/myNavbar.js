define(['jquery'], function($) {
	var init_tabbar = function() {
//		var href = location.href
		var path = location.pathname
		var tarbars = $('div.weui-tabbar').find('a')
		var on_class = 'weui-bar__item--on'
		$.each(tarbars, function(k, tarbar) {
			var _this = $(this)
			var a_href = _this.attr('href')
			if(path == a_href) {
				_this.addClass(on_class)
			}
			//		console.log(a_href)
		})
	}
	
	var init_top_navbar = function() {
		var search = location.search
		var path = location.pathname
		var path_search = path + search
		var tarbars = $('div.weui-navbar').find('a')
		var on_class = 'weui-bar__item--on'
		if (search.length < 1) {
			tarbars.first().addClass(on_class)
			return
		}
		
		$.each(tarbars, function(k, tarbar) {
			var _this = $(this)
			var a_href = _this.attr('href')
			if(path_search == a_href) {
				_this.addClass(on_class)
			}
			//		console.log(a_href)
		})
	}
	
	init_tabbar()
	init_top_navbar()
	return {}
})