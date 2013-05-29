function set_regional()
{
  var url  = '/regional';
  var boxs = ['r1', 'r2'];
  var curv = 'regional';

  $.ajax({
    type : "GET",
    url  : url,
    dataType : "json",
    success: function(data) {
    var opts = {
      'item' : data['item'],
      'boxs'  : boxs,
	  'curv' : $('#'+curv).val()
    };
    new DropDownListBox(opts);
    }
  });
}

