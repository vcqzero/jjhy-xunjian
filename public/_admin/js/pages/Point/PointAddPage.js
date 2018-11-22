define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-add-point': {
					//成功
					success: {
						toast: '添加成功',
						route: '/point',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				}
			}
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-add-point': {
					fields: {
						name: {
							validators: {

								notEmpty: {
									message: '请输入巡检点名称',
								},

								stringLength: {
									message: '最长6个字符',
									max: 6,
								},

								remote: {
									url: '/api/point/validName',
									type: 'POST', //以post的方式发生信息
									data: function(validator) {
										return {
											workyard_id: validator.getFieldElements('workyard_id').val()
										};
									},
									message: '名称已存在',
								},
							}
						},

						address: {
							validators: {

								notEmpty: {
									message: '请输入巡检点位置',
								},

								stringLength: {
									message: '最长17个字符',
									max: 17,
								},
							}
						},

//						note: {
//							validators: {
//
//								stringLength: {
//									message: '最长200个字符',
//									max: 512,
//								},
//							}
//						},
					},

//					callback: function(form) {
//						var address_position = $('input[name="address_position"]').val()
//						if(address_position.length < 1) {
//							alert('请标记巡检点')
//							return false;
//						}
//						return true;
//					}
				},
			},

		}

		return {
			init: function(pageName, page) {
//				myGaodemap.init(page, myGaodemapConfig)
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
			}
		}
	})