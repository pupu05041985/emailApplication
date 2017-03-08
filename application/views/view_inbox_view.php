<html xmlns="http://www.w3.org/1999/xhtml"><head>
   <title>Sent</title>
   
   <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
	.column-left{ float: left; width: 33%; }
	.column-right{ float: right; width: 33%; }
	.column-center{ display: inline-block; width: 33%; }
   </style>
</head> 
<body>
<div class="container" style="margin-left: 20%;">
	<div class="column-left"><a href="../../">Home</a></div>
	<div class="column-center"><a href="../../logout">Logout</a></div>
	<div class="column-left"><a href="../../inbox">Inbox</a></div>
	<div class="column-left"><a href="../../compose">Compose</a></div>
	<div class="column-left"><a href="../../sent">Sent</a></div>
	<div class="column-left">Welcome <?php //echo $username; ?>!</div>
		<?php //$populate=$row;?>  
	
			<P style="width:73%;margin-top: 5%;">
				<P>from : <?php echo $populate['email']?> <br>To : <?php echo $populate['reciver_email']?> on <?php echo $populate['date_created'];?></p>
				<P>Subject :</br><?php echo $populate['email_subject']?></p>
				<P>Message :</br><?php echo $populate['email_content']?></p>
				<p><?php if($populate['attachment']!=""){echo "have attachment!";}else{echo "no attachment";}?></p>			
				<p>File : <a href="uploades/<?php echo $populate['attachment']?>" download><?php echo $populate['attachment']?> </a>
				<p><a href="../../reply_email/<?php echo $populate['transaction_id']?>/<?php echo $populate['sender_id']?>">Reply</a></p>
				<p><a href="../../forward_email/<?php echo $populate['transaction_id']?>/<?php echo $populate['sender_id']?>">Forward</a></p>
				<p><a href="../../email_trash/<?php echo $populate['transaction_id']?>/<?php echo $populate['email_id']?>">Move Trash</a></p>
		  </p><br>
		<?php
		//}
		?>		
</div>
 </html>
  