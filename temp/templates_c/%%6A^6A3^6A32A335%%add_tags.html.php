<?php /* Smarty version 2.6.18, created on 2013-05-28 15:47:54
         compiled from add_tags.html */ ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
    <title>Bootstrap 101 Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
	<link href="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/css/bootstrap.css" rel="stylesheet" media="screen">
  </head>
  <body>
	  <!--container-->
	  <div class="container-fluid">
		  <div class="row-fluid">
			  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/sidebar.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			  <div class="span9">
				  <form method="post" action="/tags/adding">
					  <fieldset>
						<legend>添加标签</legend>
						<label>名称</label>
						<input type="text" name="name">
						<label>说明</label>
						<textarea name="des"></textarea>
						<label>图片</label>
						<input type="file" name="fileToUpload" id="fileToUpload">
							<img name="upimg" src="" style="width:80px;height:80px;" class="hide">
							<input name="upimg1" type="hidden">
						<button class="btn" onclick="return ajaxFileUpload();">上传</button>
						<br>
						<button type="button" id="submit" class="btn btn-success" >添加</button>
					  </fieldset>
				  </form>
			  </div>
		  </div>
		<hr>
	  </div>
	  <!--container-->

    <script src="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/js/jquery.js"></script>
	<script src="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/js/bootstrap.min.js"></script>
    <script src="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/js/ajaxfileupload.js"></script>
	<script>
		$("#submit").click(function(){
			$.ajax({
				url: "<?php echo $this->_tpl_vars['siteurl']; ?>
/tags/adding",
				type: 'POST',
				dataType: 'json',
				data: ({
					name: $("[name=name]").val(),
					des: $("[name=des]").val(),
					image: $("[name=upimg1]").attr('value')
				}),
				success:function(result) {
					if (result.flg == true) {
						window.location.href="<?php echo $this->_tpl_vars['siteurl']; ?>
/tags";
					}
				}
			});
		});
	
		function ajaxFileUpload()
		{

			$.ajaxFileUpload
			(
				{
					url:'<?php echo $this->_tpl_vars['siteurl']; ?>
/tags/img',
					secureuri:false,
					fileElementId:'fileToUpload',
					dataType: 'json',
					data:{name:'logan', id:'id'},
					success: function (result, status)
					{
						if(result.flg == true)  {
							$("[name=upimg]").attr('src', "<?php echo $this->_tpl_vars['imgurl']; ?>
"+result.data);					
							$("[name=upimg]").show();					
							$("[name=upimg1]").attr('value', result.data);					
						}
//						if(typeof(data.error) != 'undefined')
//						{
//							if(data.error != '')
//							{
//								alert(data.error);
//							}else
//							{
//								alert(data.msg);
//							}
//						}
					},
					error: function (data, status, e)
					{
						alert(e);
					}
				}
			)
			
			return false;

		}
	</script>
  </body>
</html>