define(['jquery', 'bootstrap'], function($) {
	var myModal = function() {
		var EVENT_MODAL_INIT = 'myModal:init'
		var current_modal
		var onClose
		var getModal = function(title, settings) {
			var modal = $('<div class="modal"  tabindex="-1" role="dialog">' +
				'<div class="modal-dialog" role="document">' +
				'<div class="modal-content">' +
				'<div class="modal-header">' +
				'<h4 class="modal-title"></h4>' +
				'</div>' +
				'<div class="modal-body"></div>' +
				'<div class="modal-footer">' +
				'</div>' +
				'</div>' +
				'</div>' +
				'</div>')

			//set title
			title = title.length < 1 ? 'title' : title
			modal.find('.modal-title').text(title)

			//set zhe modal size
			var class_modal_size = settings.size === 'sm' ? 'modal-sm' : 'modal-lg'
			modal.find('div.modal-dialog').addClass(class_modal_size)

			//set zhe modal content
			var content = settings.content
			if(typeof content !== 'undefined') {
				modal.find('div.modal-body').append(content)
			}

			//set close
			var close = settings.close
			var closeIcon = '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
				'<span aria-hidden="true">&times;</span>' +
				'</button>'
			var closeButton = '<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'
			if(close === true) {
				modal.find('div.modal-header').prepend(closeIcon)
				modal.find('div.modal-footer').append(closeButton)
			}

			//set confirm
			var confirm = settings.confirm
			var confirmButton = '<button type="button" class="btn btn-primary" data-dismiss="modal">确认</button>'
			if(confirm === true) {
				modal.find('div.modal-footer').append(confirmButton)
			}

			//set callback confirm
			onClose = settings.onClose

			//set callback confirm
			var onConfirm = settings.onConfirm
			modal.on('click', 'button.btn-primary', function() {
				if($.isFunction(onConfirm)) {
					onConfirm()
				}
			})

			return modal
		}

		var show = function(modal, option) {
			modal.modal(option)
			modal.off('hidden.bs.modal')
			modal.on('hidden.bs.modal', function(e) {
				var modal = $(e.target)
				if($.isFunction(onClose)) {
					onClose()
					onClose = undefined
				}
				modal.remove()
			})

			current_modal = modal
		}
		var showLoading = function() {
			var progress_bar = '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>'
			var settings = {
				size: 'sm', //lg
				content: progress_bar,
				close: false,
				confirm: false,
				//			onClose : '',
				//			onConfirm : '',
			}
			var progress_modal_id = 'progress_modal'
			var modal = getModal('加载中...', settings)
			modal.removeClass('fade')
			modal.css('padding-top', '15%')

			show(modal, {
				backdrop: 'static',
				keyboard: false,
			})
		}

		var hide = function(callback) {
			onClose = callback
			current_modal.modal('hide')
		}

		/**
		 * 远程加载modal并将其显示出来
		 * behaivor 
		 * 如果当前页面已存在modal则不显示
		 * 
		 */
		var load = function(url) {
			$.ajax({
				type: "get",
				url: url,
				async: true,
				beforeSend: function() {
					showLoading()
				},

				error: function() {
					alert('操作失败')
					location.reload()
				},

			}).done(function(modal) {
				var callback = function() {
					var _modal = $(modal)
					if(_modal.hasClass('modal')) {
						$('body').append(_modal)
						show(_modal)
					}
					$(document).trigger(EVENT_MODAL_INIT, [_modal])
				}
				hide(callback)
			})
		}

		$(function() {
			$('body').off('click', '[data-modal-open="true"]')
			$('body').on('click', '[data-modal-open="true"]', function(e) {
				console.log('open modal')
				var _button = $(this)
				var modalUrl = _button.attr('data-modal-url')
				if(modalUrl) {
					load(modalUrl)
				}
			})
		})
	}

	var myPage = function() {
		/**
		 * 页面基本框架加载好之后，就要加载实质页面内容
		 * 页面主要数据和交互功能都在这里定义
		 */
		var init_page = function(page) {
			var pageName = page.attr('data-name')
			if(typeof pageName == 'undefined') {
				console.log('page-> 未加载页面 ，页面名称未定义')
				return false;
			}
			requirejs([pageName], function(pageModule) {
				if(typeof pageModule == 'undefined') {
					return
				}
				if($.isFunction(pageModule['init'])) {
					pageModule.init(pageName, page)
				}
				console.log('PAGE-> 加载页面完成：' + pageName)
			})
		}

		$(function() {
			var page = $('body').find('div.page').first()
			init_page(page)
		})

		$(document).on('myModal:init', function(e, page) {
			init_page(page)
		})
	}

	var mySearch = function() {
		var CLASS_SEARCH_FORM = '.form-search-submit'

		var setFormAction = function(form, path) {
			form.attr('action', path)
			form.attr('method', 'get')
			form.find('a[type="button"]').attr('href', path)
		}

		/**
		 * 如果url带有query
		 * 则将query值填充到搜索框中
		 * 同时显示清空筛选按钮
		 * 
		 * @param {Object} form
		 * @param {Object} search
		 */
		var setFormData = function(form, search) {
			search = search.replace('?', '')
			search = search.split('&')
			$.each(search, function(k, v) {
				if(v.length < 1) {
					return false;
				}
				var query = v.split('=')
				var name = query[0]
				var value = getUrlParam(name)
				if(name != 'page' && value.length > 0) {
					form.find('[name=' + name + ']').first().val(value)
					setResetButton(form)
					disabledSubmitButton(form, false)
				}
			});
		}

		var disabledSubmitButton = function(form, disabled) {
			var submitButton = form.find('button[type="submit"]')
			submitButton.prop('disabled', disabled === true)
		}

		var setFormButton = function(form) {
			var submitButton = form.find('button[type="submit"]')
			submitButton.prop('disabled', true)
			form.on('change', 'input, select, textarea', function() {
				form.trigger('mySearch.search')
			})
		}

		var onSearch = function(form) {
			form.on('mySearch.search', function() {
				var submitButton = form.find('button[type="submit"]')
				submitButton.prop('disabled', false)
			})
		}

		var enabledSubmitButton = function(form, enabled) {
			var submitButton = form.find('button[type="submit"]')
			submitButton.prop('disabled', enabled != true)
		}

		var setResetButton = function(form) {
			form.find('a[type="button"]').removeClass('hidden')
		}

		var getUrlParam = function(key) {
			// 获取参数
			var search = window.location.search;
			// 正则筛选地址栏
			var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)");
			// 匹配目标参数
			var result = search.substr(1).match(reg);
			//返回参数值
			return result ? decodeURIComponent(result[2]) : null;
		}

		var init = function() {
			var form = $('body').find('form' + CLASS_SEARCH_FORM).first(),
				path = location.pathname,
				search = window.location.search;
			if(form.length < 1) {
				return false;
			}

			//set form action
			setFormAction(form, path)
			setFormButton(form)
			setFormData(form, search)
			onSearch(form)
			console.log('mySearch is ready')
		}

		init()
	}

	var myForm = function() {
		var EVENT_BEFORE = 'form-ajax-submit:before'
		var EVENT_COMPLETE = 'form-ajax-submit:complete'
		var CLASS_AJAX_FORM = 'form-ajax-submit'

		var getActionUrl = function(form) {
			return form.attr('action')
		}

		var hasData = function(form) {
			var datas = form.serializeArray()
			var hasData = false
			for(var key in datas) {
				var data = datas[key]
				if(data['value'].length > 0) {
					hasData = true
					break
				}
			}

			return hasData
		}

		var doSubmit = function(form) {
			var url = getActionUrl(form)
			var data = form.serialize()
			$.ajax({
				type: "post",
				data: data,
				url: url,
				async: true,
				beforeSend: function() {
					if(hasData(form) !== true) {
						console.log('form is empty do not need submit')
						return false;
					} else {
						console.log('The form is submitting on ajax')
						form.trigger(EVENT_BEFORE)
						disabledSubmitButton(form, true)
					}
				},

				error: function() {
					alert('请求失败')
					location.reload()
				},

			}).done(function(res) {
				var resObj = JSON.parse(res)
				form.trigger(EVENT_COMPLETE, {
					'resObj': resObj
				})
			});
		}

		var disabledSubmitButton = function(form, disabled) {
			var submitButton = form.find('button[type="submit"]')
			submitButton.text('处理中...')
			form.find('button').attr('disabled', disabled === true)
		}

		$('body').on('submit', 'form.' + CLASS_AJAX_FORM, function(e) {
			var form = $(this)
			if(form.hasClass(CLASS_AJAX_FORM) == false) {
				return false;
			}
			e.preventDefault()

			//判断是否是需要验证的表单
			//表单有bv-form的class代表是需要验证的表单
			if(form.hasClass('bv-form')) {
				return
			}
			doSubmit(form)
		})

		$('body').on('myValidator:valid.success', 'form.' + CLASS_AJAX_FORM, function(e) {
			var form = $(this)
			doSubmit(form)
		})
	}

	var myNoprogress = function() {
		requirejs(['nprogress'], function(NProgress) {
			// NProgress
			$(document).ready(function() {
				NProgress.start();
				NProgress.done();

			});

			$(window).load(function() {
				NProgress.start();
				NProgress.done();
			});
		})
	}

	var myPanel = function() {

		$('.collapse-link').on('click', function() {
			var $BOX_PANEL = $(this).closest('.x_panel'),
				$ICON = $(this).find('i'),
				$BOX_CONTENT = $BOX_PANEL.find('.x_content');

			// fix for some div with hardcoded fix class
			if($BOX_PANEL.attr('style')) {
				$BOX_CONTENT.slideToggle(200, function() {
					$BOX_PANEL.removeAttr('style');
				});
			} else {
				$BOX_CONTENT.slideToggle(200);
				$BOX_PANEL.css('height', 'auto');
			}

			$ICON.toggleClass('fa-chevron-up fa-chevron-down');
		});
	}

	var myNavbar = function() {
		var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
			$BODY = $('body'),
			$MENU_TOGGLE = $('#menu_toggle'),
			$SIDEBAR_MENU = $('#sidebar-menu'),
			$SIDEBAR_FOOTER = $('.sidebar-footer'),
			$LEFT_COL = $('.left_col'),
			$RIGHT_COL = $('.right_col'),
			$NAV_MENU = $('.nav_menu'),
			$FOOTER = $('footer'),
			$PAGE_NAVBAR = $('body').find('div.page').first().attr('data-narbar')
		var setContentHeight = function() {
			// reset height
			$RIGHT_COL.css('min-height', $(window).height());
			var bodyHeight = $BODY.outerHeight(),
				footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
				leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
				contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;
			// normalize content
			//			contentHeight -= $NAV_MENU.height() + footerHeight;
			$RIGHT_COL.css('min-height', contentHeight);
		};

		//点击菜单时
		$SIDEBAR_MENU.find('a').on('click', function(ev) {
			var $li = $(this).parent();

			if($li.is('.active')) {
				$li.removeClass('active active-sm');
				$('ul:first', $li).slideUp(function() {
					//					setContentHeight();
				});
			} else {
				// prevent closing menu if we are on child menu
				if(!$li.parent().is('.child_menu')) {
					$SIDEBAR_MENU.find('li').removeClass('active active-sm');
					$SIDEBAR_MENU.find('li ul').slideUp();
				} else {
					if($BODY.is(".nav-sm")) {
						$SIDEBAR_MENU.find("li").removeClass("active active-sm");
						$SIDEBAR_MENU.find("li ul").slideUp();
					}
				}
				$li.addClass('active');

				$('ul:first', $li).slideDown(function() {
					//					setContentHeight();
				});
			}
		});

		// 点击菜单切换按钮
		$MENU_TOGGLE.on('click', function() {
			console.log('clicked - menu toggle');

			if($BODY.hasClass('nav-md')) {
				$SIDEBAR_MENU.find('li.active ul').hide();
				$SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
			} else {
				$SIDEBAR_MENU.find('li.active-sm ul').show();
				$SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
			}
			$BODY.toggleClass('nav-md nav-sm');
			setContentHeight();
		});

		//自动将当前所需菜单设置为active
		$SIDEBAR_MENU.find('a').filter(function() {
			var href = this.href
			if($PAGE_NAVBAR) {
				return(origin + $PAGE_NAVBAR) == href
			} else {
				return CURRENT_URL === href
			}
		}).parent('li').addClass('current-page').parents('ul').slideDown(function() {
			//			setContentHeight();
		}).parent().addClass('active');
	}

	var pnotify = function(type, title, message) {
		requirejs(['bootstrap_notify'], function() {
			title = '<strong>' + title + '</strong>'
			message = typeof message == 'undefined' ? '' : message;
			$.notify({
				// options
				title: title,
				message: message
			}, {
				// settings
				type: type,
				allow_dismiss: false,
				newest_on_top: true,
				offset: {
					y: 1
				},

				placement: {
					from: 'top', //top bottom
					align: 'center',
				},
				delay: 500
			});
		})
	}

	var route = {
		getSearchParam: function(key) {
			// 获取参数
			var search = window.location.search;
			// 正则筛选地址栏
			var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)");
			// 匹配目标参数
			var result = search.substr(1).match(reg);
			//返回参数值
			return result ? decodeURIComponent(result[2]) : null;
		},
	}

	myNavbar()
	myNoprogress()
	myPage()
	myModal()
	mySearch()
	myForm()
	myPanel()
	console.log('init')
	return {
		pnotify: pnotify,
		route: route,
	}
})