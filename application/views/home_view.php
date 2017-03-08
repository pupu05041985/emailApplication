<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Private Area</title>
   <style>
	.column-left{ float: left; width: 33%; }
	.column-right{ float: right; width: 33%; }
	.column-center{ display: inline-block; width: 33%; }
   </style>
 </head>
 <body>
   <div class="container" style="margin-left: 20%;">
	<div class="column-left"><a href="#">Home</a></div>
	<div class="column-center"><a href="home/logout">Logout</a></div>
	<div class="column-left"><a href="home/inbox">Inbox</a></div>
	
	<div class="column-left"><a href="home/compose">Compose</a></div>
	<div class="column-left"><a href="home/sent">Sent</a></div>
	

	<div class="column-left">Welcome <?php echo $username; ?>!</div>
   </div>
  
 
   
 </body>
</html>