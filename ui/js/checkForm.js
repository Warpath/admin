/**表单验证2.0
 * author: warpath
 * 2012年 09月 16日 星期日 20:14:36 PDT
 */

/**
 * 使用方法：
 *		带有input标签的元素验证:
 *****************************
 *		非空:
 *		class添加 require 
 *		title设置为字段名，如title="用户名"
 *****************************
 *		长度验证：
 *		class添加length_limit
 *		minlength:最小值
 *		maxlength:最大值
 *		title:字段名
 *****************************
 *		手机验证
 *		class添加phone
 *****************************
 *		二选一必填验证
 *		class添加bind1、bind2
 *****************************
 *		固定电话
 *      class添加telephone
 *****************************
 *		URL验证：
 *		class添加url
 *****************************
 *      邮箱验证:
 *      class添加email
 *****************************
 *		邮编验证
 *		class添加zip
 *****************************
 *      数字验证:
 *      class添加num
 *****************************
 *		密码重复验证
 ****************************
 *TODO::用户名存在ajax验证
 *      失去焦点验证开关
 *****************************
 *		错误提示位置:<div class=error_msg_username></div>
 *		错误样式：error_msg_class
 *		正确样式：right_msg_class
 *
 *		页面:
 *	   <script type="text/javascript">
 *	   $(form).submit(function(){
 *		checkForm.check();
 *		if (checkForm.is_passed == false) {
 *			return false;
 *		}
 *	   });
 */
var checkForm = {
	is_passed:true,
	_error_class:'text-error',
	_right_class:'right_msg_class',
	//去空
	_trim:function(msg) {
			return msg.replace(/(^\s*)|(\s*$)/g, "");
	},
	
	//去]、[
	_expect:function(ep) {
		ep = ep.replace(/\[/g, '');		
		ep = ep.replace(/\]/g, '');
		return ep;
	},
	//样式设置
	setClassError:function(cls, msg, is_true) {
		if (is_true == true) {
			$(cls).last().removeClass(checkForm._error_class);
			$(cls).last().removeClass(checkForm._right_class);
			$(cls).last().addClass(checkForm._right_class);
		} else {
			$(cls).last().removeClass(checkForm._right_class);
			$(cls).last().removeClass(checkForm._error_class);
			$(cls).last().addClass(checkForm._error_class);
		}			  
		$(cls).last().html(msg);
	},
	start:function() {
		//普通text/textarea/select表单
		$('.require').bind('blur',function(){
			var _name = this.name;
			var _title = this.title;
			var _value = checkForm._trim(this.value);
			var error_txt = '.error_msg_'+_name;
			if (_value == '' || _value == '0') {
				checkForm.setClassError(error_txt, _title + '不能为空', false);
			} else {
				checkForm.setClassError(error_txt, '', true);
			}
		});	  
		//密码重复验证
		if ($(':password').length > 1) {
			$(':password').eq(1).blur(function(){
				var _password = checkForm._trim($(':password').eq(0).attr('value'));	
				var _cpassword = checkForm._trim($(':password').eq(1).attr('value'));
				var _name = $(':password').eq(1).attr('name');
				var error_txt = '.error_msg_'+_name;
				if (_password !== _cpassword) {
					checkForm.setClassError(error_txt, '密码输入必须一致', false);
				} else {
					checkForm.setClassError(error_txt, '', true);
				}
			});	
		}
		//长度验证
		$('.length_limit').blur(function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			var _minlength = $(this).attr('minlength');
			var _maxlength = $(this).attr('maxlength');
			var _title = this.title;
			var error_txt = '.error_msg_'+_name;
			if(_value !== '') {
				if (_value.length > _maxlength || _value.length < _minlength) {
					checkForm.setClassError(error_txt, _title+'必须由'+_minlength+'到'+_maxlength+'位字符构成', false);
				} else {
					checkForm.setClassError(error_txt, '', true);
				}
			}
		});
		//url验证
		$('.url').blur(function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			var error_txt = '.error_msg_'+_name;
			var reg = /^http|https:\/\/\S+$/;
			if (reg.exec(_value)) {
				checkForm.setClassError(error_txt, '', true);
			} else {
				checkForm.setClassError(error_txt, '网址格式不正确', false);
			}
		});
		//email验证
		$('.email').blur(function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			var error_txt = '.error_msg_'+_name;
			var reg = /^[a-zA-Z0-9_\-]+@([a-zA-Z0-9_\-])+(\.[a-zA-Z0-9_\-]{2,3}){1,2}$/;
			if (reg.exec(_value)) {
				checkForm.setClassError(error_txt, '', true);
			} else {
				checkForm.setClassError(error_txt, '邮箱格式不正确', false);
			}
		});
		//固定电话号码验证
		$('.telephone').blur(function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			var reg = /-/;
			if(reg.exec(_value)) {
				var _title = this.title;
				var error_txt = '.error_msg_'+_name;
				var reg = /^\d{4}-\d{8}(-\d{1,4})?$/;
				if (reg.exec(_value)) {
					checkForm.setClassError(error_txt, '', true);
				} else {
					checkForm.setClassError(error_txt, _title+'格式不正确', false);
				}
			}
		});
		//电话号码验证
		$('.phone').blur(function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			var reg1 = /^\d+$/;
			if(_value != '') {
				if(reg1.exec(_value)) {
					var _title = this.title;
					var error_txt = '.error_msg_'+_name;
					var reg = /^1\d{10}$/;
					if (reg.exec(_value)) {
						checkForm.setClassError(error_txt, '', true);
					} else {
						checkForm.setClassError(error_txt, _title+'格式不正确', false);
					}
				}
			}
		});
		//数字范围验证
		$('.num').bind('blur', function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			if (_value != '') {
				var _min = $(this).attr('min');
				var _max = $(this).attr('max');
				var _title = this.title;
				var error_txt = '.error_msg_'+_name;
				//_value = parseFloat(_value);
				var reg = /^\d+(\.\d{1,2})?$/;
				if (_value>=parseFloat(_min) && _value<=parseFloat(_max) && reg.exec(_value)) {
					checkForm.setClassError(error_txt, '', true);
				} else {
					checkForm.setClassError(error_txt, _title+'应输入'+_min+'至'+_max+'的数字', false);
				}
			}
		});
		//整型验证
		$('.number').bind('blur', function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			if(_value != '') {
				var _title = this.title;
				var error_txt = '.error_msg_'+_name;
				var reg = /^\d+$/;
				if (reg.exec(_value)) {
					checkForm.setClassError(error_txt, '', true);
				} else {
					checkForm.setClassError(error_txt, _title+'应该是整形数字', false);
				}
			}
		});
		//邮编验证
		$('.zip').bind('blur', function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			var _title = this.title;
			var error_txt = '.error_msg_'+_name;
			var reg = /^\d{6}$/;
			if (reg.exec(_value)) {
				checkForm.setClassError(error_txt, '', true);
			} else {
				checkForm.setClassError(error_txt, _title+'必须由六位数字构成', false);
			}
		});
		//input 二选一必填验证
		$('.bind2').bind('blur', function(){
			var _name = this.name;
			var _value = checkForm._trim(this.value);
			var _title = this.title;
			var _bind1Title = $(".bind1").eq(0).attr('title');
			var _bind1Value = $(".bind1").eq(0).val();
			var error_txt = '.error_msg_'+_name;
			if(_value == '' && _bind1Value == '') {
				checkForm.setClassError(error_txt, _title+'和'+_bind1Title+'至少填一项', false);
			} else {
				checkForm.setClassError(error_txt, '', true);
			}
		});
	},
	check:function(){
		this.is_passed = true;
		//密码重复验证
		var len = $(':password').length;
		if ($(':password').length > 1) {
			var _password = this._trim($(':password').eq(0).attr('value'));
			var _cpassword = this._trim($(':password').eq(1).attr('value'));
			var _name = $(':password').eq(1).attr('name');
			var error_txt = '.error_msg_'+ _name;	
			if (_password !== _cpassword) {
				checkForm.setClassError(error_txt, '密码输入必须一致', false);
			} else {
				checkForm.setClassError(error_txt, '', true);
			}
		}
		//长度验证
		var len = $('.length_limit').length;
		for(i=0;i<len;i++) {
			var _name = $('.length_limit').eq(i).attr('name');
			var _value = $('.length_limit').eq(i).attr('value');
			var _title = $('.length_limit').eq(i).attr('title');
			var _minlength = $('.length_limit').eq(i).attr('minlength');
			var _maxlength = $('.length_limit').eq(i).attr('maxlength');
			_value = checkForm._trim(_value);
			var error_txt = '.error_msg_'+_name;
			if(_value !== '') {
				if (_value.length > _maxlength || _value.length < _minlength) {
					checkForm.setClassError(error_txt, _title + '必须由'+_minlength+'到'+_maxlength+'位字符构成', false);
					this.is_passed = false;
				} else {
					checkForm.setClassError(error_txt, '', true);
				}
			}
		}
		//URL验证
		var len = $('.url').length;
		for(i=0;i<len;i++) {
			var _name = $('.url').eq(i).attr('name');
			var _value = this._trim($('.url').eq(i).attr('value'));
			var error_txt = '.error_msg_' + _name;
			var reg = /^http|https:\/\/\S+$/;
			if (reg.exec(_value)) {
				checkForm.setClassError(error_txt, '', true);
			} else {
				checkForm.setClassError(error_txt, 'URL格式不正确', false);
				this.is_passed = false;
			}
		}
		//email验证
		var len = $('.email').length;
		for(i=0;i<len;i++) {
			var _name = $('.email').eq(i).attr('name');
			var _value = this._trim($('.email').eq(i).attr('value'));
			var error_txt = '.error_msg_'+_name;
			var reg = /^[a-zA-Z0-9_\-]+@([a-zA-Z0-9_\-])+(\.[a-zA-Z0-9_\-]{2,3}){1,2}$/;
			if (reg.exec(_value)) {
				checkForm.setClassError(error_txt, '', true);
			} else {
				checkForm.setClassError(error_txt, '邮箱格式不正确', false);
				this.is_passed = false;
			}
		}
		//radio
		var len = $(":radio").length;
		for(i=0;i<len;i++) {
			var _name = $(":radio").eq(i).attr('name');
			var _value = $("[name='"+_name+"']:checked").val();
			var error_txt = '.error_msg_'+_name;
			if(typeof(_value) == "undefined") {
				checkForm.setClassError(error_txt, '单选必须选择一个', false);			
				this.is_passed = false;
			} else {
				checkForm.setClassError(error_txt, '', true);
			}
		}
		//数字范围验证
		var len = $(".num").length;
		for(i=0;i<len;i++) {
			var _name = $(".num").eq(i).attr('name');
			var _value = this._trim($('.num').eq(i).attr('value'));
			if(_value != '') {
				var _min = $('.num').eq(i).attr('min');
				var _max = $('.num').eq(i).attr('max');
				var _title = $('.num').eq(i).attr('title');
				var error_txt = '.error_msg_'+_name;
				var reg = /^\d+(\.\d{1,2})?$/;
				if (_value>=parseFloat(_min) && _value<=parseFloat(_max) && reg.exec(_value)) {
					checkForm.setClassError(error_txt, '', true);
				} else {
					checkForm.setClassError(error_txt, _title+'应输入'+_min+'至'+_max+'的数字', false);
					this.is_passed = false;
				}
			}
		}
		//整型验证
		var len = $(".number").length;
		for(i=0;i<len;i++) {
			var _name = $(".number").eq(i).attr('name');
			var _value = this._trim($('.number').eq(i).attr('value'));
			if(_value != '') {
				var _title = $('.number').eq(i).attr('title');
				var error_txt = '.error_msg_'+_name;
				var reg = /^\d+$/;
				if (reg.exec(_value)) {
					checkForm.setClassError(error_txt, '', true);
				} else {
					checkForm.setClassError(error_txt, _title+'应该是整形数字', false);
					this.is_passed = false;
				}
			}
		}
		//电话号码验证
		var len = $(".phone").length;
		for(i=0;i<len;i++){
			var _name = $(".phone").eq(i).attr('name');
			var _value = this._trim($('.phone').eq(i).attr('value'));
			var reg1 = /^\d+$/;
			if(_value != '') {
				if(reg1.exec(_value)) {
					var _title = $('.phone').eq(i).attr('title');
					var error_txt = '.error_msg_'+_name;
					var reg = /^1\d{10}$/;
					if (reg.exec(_value)) {
						checkForm.setClassError(error_txt, '', true);
					} else {
						checkForm.setClassError(error_txt, _title+'格式不正确', false);
						this.is_passed = false;
					}
				}
			}
		}
		//固定电话号码验证
		var len = $(".telephone").length;
		for(i=0;i<len;i++) {
			var _name = $(".telephone").eq(i).attr('name');
			var _value = this._trim($('.telephone').eq(i).attr('value'));
			var reg = /-/;
			if(reg.exec(_value)) {
				var _title = $('.telephone').eq(i).attr('title');
				var error_txt = '.error_msg_'+_name;
				var reg = /^\d{4}-\d{8}(-\d{1,4})?$/;
				if (reg.exec(_value)) {
					checkForm.setClassError(error_txt, '', true);
				} else {
					checkForm.setClassError(error_txt, _title+'格式不正确', false);
					this.is_passed = false;
				}
			}
		}
		//邮编验证
		var len = $(".zip").length;
		for(i=0;i<len;i++) {
			var _name = $(".zip").eq(i).attr('name');
			var _value = this._trim($('.zip').eq(i).attr('value'));
			var _title = $('.zip').eq(i).attr('title');
			var error_txt = '.error_msg_'+_name;
			var reg = /^\d{6}$/;
			if (reg.exec(_value)) {
				checkForm.setClassError(error_txt, '', true);
			} else {
				checkForm.setClassError(error_txt, _title+'必须由六位数字构成', false);
				this.is_passed = false;
			}
		}
		//input 二选一必填验证
		var len = $(".bind2").length;
		for(i=0;i<len;i++) {
			var _name = $(".bind2").eq(i).attr('name');
			var _value = this._trim($('.bind2').eq(i).attr('value'));
			var _title = $('.bind2').eq(i).attr('title');
			var error_txt = '.error_msg_'+_name;
			var _bind1Title = $(".bind1").eq(i).attr('title');
			var _bind1Value = $(".bind1").eq(i).attr('value');
			if(_value == '' && _bind1Value == '') {
				checkForm.setClassError(error_txt, _title+'和'+_bind1Title+'至少填一项', false);
				this.is_passed = false;
			} else {
				checkForm.setClassError(error_txt, '', true);
			}
		}
		//普通text/textarea/select表单
		var len = $('.require').length;
		for(i=0;i<len;i++) {
			var _name = $('.require').eq(i).attr('name');
			var _value = $('.require').eq(i).attr('value');
			var _title = $('.require').eq(i).attr('title');
			var error_txt = '.error_msg_'+_name;
			if (checkForm._trim(_value) == '' || checkForm._trim(_value) == '0') {
				checkForm.setClassError(error_txt, _title + '不能为空', false);
				this.is_passed = false;
			} else {
				checkForm.setClassError(error_txt, '', true);
			}
		}
	}
}
$().ready(function(){
	checkForm.start();		
});
