<?php /* Smarty version 2.6.18, created on 2013-05-29 13:19:28
         compiled from signin.html */ ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
    <title>登录</title>
	<meta name="robots" content="index,follow" />
	<meta name="Keywords" content="习惯，感恩，个人成长"/>
	<meta name="description" content="一个关注个人成长的平台，帮助你建立习惯，培养感恩，关注成长中的点点滴滴。">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/css/bootstrap.css" rel="stylesheet" media="screen">
  </head>
  <body>

	  <!-- header -->
	  <div class="navbar navbar-inverse navbar-static-top">
		  <div class="navbar-inner">
			  <div class="container">
				  <a class="pull-left" href="http://www.breakfate.com"><img src="http://www.breakfate.com/ui/img/logo.png" style="height:40px;width:60px;"></a>
				  <span class="nav-collapse collapse">
					  <ul class="nav">
						  <li class><a href="http://www.breakfate.com/habit">习惯</a></li>
						  <li class><a href="http://www.breakfate.com/thanksgiving">感恩</a></li>
						  <li class><a href="http://www.breakfate.com/mature">成长</a></li>
					  </ul>
					  <form class="navbar-search">
						<input type="text" class="search-query" placeholder="Search">
						<div></div>
					  </form>
					  <div class="pull-right">
						  <?php if ($this->_tpl_vars['userinfo']): ?>
						  <a href="http://www.breakfate.com/home"><img src="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/img/avatar.jpg" class="img-polaroid">Warpath</a>
						  <a href="http://www.breakfate.com/set"><i class="icon-edit"></i></a>
						  <a href="http://www.breakfate.com/logout"><i class="icon-share"></i></a>
						  <?php else: ?>
						  <a href="http://www.breakfate.com/signup"><button class="btn btn-success ">注册</button></a>
						  <?php endif; ?>
					  </div>
				  </span>
			  </div>
		  </div>
	  </div>
	  <!-- header -->
	  <!--container-->
	  <div class="container">
		  <br>
		  <br>
		  <br>
		  <div class="thanksgiving_height">
		  <form class="form-signin" method="post" action="logining">
			  <h2 class="form-signin-heading">登录</h2>
			  <?php if ($this->_tpl_vars['error']): ?><p class="text-error"><?php echo $this->_tpl_vars['error']; ?>
</p><?php endif; ?>
			  <label>
			  用户名
			  </label>
		      <input type="text" class="input-block-level" placeholder="用户名" name="name">
			  <label>
				  密码
			  </label>
			  <input type="password" class="input-block-level" placeholder="密码" name="password">
			  <button class="btn btn-success btn-large btn-primary" type="submit">登录</button>
			  <a class="pull-right" style="margin-top:15px;"  href="<?php echo $this->_tpl_vars['siteurl']; ?>
/forgetpassword">忘记密码</a>
		  </form>
		  </div>
		<hr>
	  </div>
	  <!--container-->
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <script src="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/js/jquery.js"></script>
    <script src="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/js/bootstrap.min.js"></script>
	<script src="<?php echo $this->_tpl_vars['siteurl']; ?>
/ui/js/main.js"></script>
  </body>
</html>