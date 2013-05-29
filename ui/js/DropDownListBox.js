/** 地区三级联动
 * 版本：1.0
 * author: warpath
 * 2012年 10月 28日 星期日 00:40:48 PDT
 */

/**
 * 使用方法:
 * <input type="hidden" id="regional" value="<{$province}>-<{$city}>-<{$area}>">
 * <select name="province" id="r1">
 * <select name="city" id="r2">
 * <select name="area" id="r3">
 *
 * <script type="text/javascript" src="/ui/default/js/DropDownListBox.js"></script>
 * <script type="text/javascript" src="/ui/default/js/cate_s.js"></script>
 * <script language="javascript">
 * set_regional();
 * </script>
 *
 * TIPS:需要显示地区的时候，添加一个input元素，ID为regional
 */
var DropDownListBox = function(opts) {
	this._item = opts.item;
	this._boxs = opts.boxs || ['box1', 'box2', 'box3'];
	this._curv = opts.curv;

	if (typeof(this._item) == 'object') {
		this._init();
	} else {
		alert('DropDownListBox Initialization Failed!')
	}
	return this
}
DropDownListBox.prototype = {
	_init: function() {
		var self = this;

		this._setValue();
		for (var i in this._boxs) {
			var $box = $('#' + this._boxs[i]);
			$box.bind('change', function(){
				var box = $(this)[0];
					if (box.options.length > 0) {
						self._cgValue(box, $(this).val());
					}
			});
		}

    },

	_cgValue: function(box, curv) {
		if(box.id == this._boxs[0]) {
			if (curv == '') {
				$box2.options.length = 0;
				$box3.options.length = 0;
			}else {
				$box2.options.length = 0;
				var _city = this._item[curv].city;
				for (var i in _city) {
					$box2.options.add(new Option(_city[i].name, _city[i].id));
				}
			}
		} else if(box.id == this._boxs[1]) {
		} else {
			return ;
		}
	},

	_setValue: function() {
		$box1 = $('#' + this._boxs[0])[0];	
		$box2 = $('#' + this._boxs[1])[0];


		for (var key in this._item) {
			$box1.options.add(new Option(this._item[key].name, this._item[key].id));
		}		   

		if(this._curv) {
			curvs = this._curv.split('-');
			var province = Number(curvs[0]);
			var city = Number(curvs[1]);
			var area = Number(curvs[2]);

			if(this._item[province] !== undefined) {
				$("#" + this._boxs[0]).val(province);

				$box2.options.length = 0;
				if(this._item[province] !== undefined) {
					var _city = this._item[province].city;
					for (var i in _city) {
						$box2.options.add(new Option(_city[i].name, _city[i].id));
					}
					$("#" + this._boxs[1]).val(city);
				}

			}

		}

    }
}
