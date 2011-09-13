<link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/slide.css" media="screen" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<!-- PNG FIX for IE6 -->
<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
<!--[if lte IE 6]>
    <script type="text/javascript" src="js/pngfix/supersleight-min.js"></script>
<![endif]-->

<script type="text/javascript" src="js/slide.js"></script>

<?php echo $script; ?>

<!-- Panel -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Pencl</h1>
				<h2>A web-base note taking application.</h2>		
				<p class="grey">Collaborate, share, and create!</p>
				<h2>Open Beta Soon!</h2>
				<p class="grey">Enter your email address below to hear when we open it up for test phases - you could be one of them! Sign up now before it's too late!</p>
			</div>            
            
            <?php
			
			if(!$_SESSION['id']):
			
			?>
            
			<div class="left">
				<h1>Features</h1>
				<p class="grey">HTML5/Canvas Note Editor (Web friendly)</p>
				<p class="grey">Include images (Editor, or import)</p>
				<p class="grey">Export to PDF!</p>
				<p class="grey">Share notepads with friends</p>
				<p class="grey">Minimalistic interface</p>
				<p class="grey">Save to our server, access your notepad anywhere!</p>
				<p class="grey">Create <b>unlimited</b> notepads!</p>
			</div>
			<div class="left right">	
				<!-- Login Form -->		
				<form class="clearfix" action="" method="post" id="toggleLogin">
					<h1>Member Login (<a id="showRegister" href="#">Register</a>)</h1>
                    
                    <?php
						
						if($_SESSION['msg']['login-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							//unset($_SESSION['msg']['login-err']); //Need variable below also, don't unset yet
						}
					?>
					
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
						
				</form>
				<form class="clearfix"  style="display: none;" action="" method="post" id="toggleRegister">
					<h1>Register for Beta (<a id="showLogin" href="#">Login</a>)</h1>
                    
                    <?php
						
						if($_SESSION['msg']['login-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
					<label class="grey" for="username">Beta Key:</label>
					<input class="field" type="text" name="betaKey" id="betaKey" value="" size="40" />
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Register" class="bt_register" />
					
				</form>
			</div>
            
            <?php
			
			else:
			
			?>
            
            <div class="left">
            
            <h1>Members panel</h1>
            
            <p>You can put member-only data here</p>
            <a href="registered.php">View a special member page</a>
            <p>- or -</p>
            <a href="?logoff">Log off</a>
            
            </div>
            
            <div class="left right">
            </div>
            
            <?php
			endif;
			?>
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hello <?php echo $_SESSION['usr'] ? $_SESSION['usr'] : 'Guest';?>!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#"><?php echo $_SESSION['id']?'Open Panel':'Log In | Register';?></a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->