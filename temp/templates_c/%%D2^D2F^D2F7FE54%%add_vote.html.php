<?php /* Smarty version 2.6.18, created on 2013-05-28 15:59:36
         compiled from add_vote.html */ ?>
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
				  <form method="post" action="/vote/adding">
					  <fieldset>
						<legend>添加投票</legend>
						<label>内容</label>
						<textarea name="content"></textarea>
						<button type="submit" id="submit" class="btn btn-success" >添加</button>
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
  </body>
</html>