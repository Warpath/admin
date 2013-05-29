<?php /* Smarty version 2.6.18, created on 2013-05-28 10:17:54
         compiled from tags.html */ ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
    <title>Bootstrap 101 Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="ui/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="ui/css/bootstrap.css" rel="stylesheet" media="screen">
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
				  <div>
					  <a href="<?php echo $this->_tpl_vars['siteurl']; ?>
/tags/add"><button class="btn btn-success">添加标签</button></a>
				  </div>
				  <table class="table">
					  <thead>
						  <tr>
							<th>名称</th>
							<th>说明</th>
							<th>查看</th>
							<th>删除</th>
						  </tr>
					  </thead>
					  <tbody>
						  <?php $_from = $this->_tpl_vars['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
						  <tr>
						  <td><?php echo $this->_tpl_vars['item']['Name']; ?>
</td>
						  <td><?php echo $this->_tpl_vars['item']['Des']; ?>
</td>
						  <td><a href="<?php echo $this->_tpl_vars['siteurl']; ?>
/tags/detail/<?php echo $this->_tpl_vars['item']['Id']; ?>
">查看</a></td>
						  <td><a href="<?php echo $this->_tpl_vars['siteurl']; ?>
/tags/del/<?php echo $this->_tpl_vars['item']['Id']; ?>
">删除</a></td>
						  </tr>
						  <?php endforeach; endif; unset($_from); ?>
					  </tbody>
				  </table>
			  </div>
		  </div>
		<hr>
	  </div>
	  <!--container-->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="ui/js/bootstrap.min.js"></script>
  </body>
</html>