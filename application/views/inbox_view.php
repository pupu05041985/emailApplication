<html xmlns="http://www.w3.org/1999/xhtml"><head>
   <title>Sent</title>
   
   <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>


  </script>
  <style>
	.column-left{ float: left; width: 33%; }
	.column-right{ float: right; width: 33%; }
	.column-center{ display: inline-block; width: 33%; }
   </style>
</head>
 
 <body>
	<div class="container">
	   <div class="column-center"><?php //echo "<pre>";$countArray	=	count($populate);print_r($populate);echo "</pre>";?></div>
	   
	   
	</div>
	<div class="container" style="margin-left: 20%;">
	<div class="column-left"><a href="../home">Home</a></div>
	<div class="column-center"><a href="logout">Logout</a></div>
	<div class="column-left"><a href="inbox">Inbox</a></div>
	<div class="column-left"><a href="compose">Compose</a></div>
	<div class="column-left"><a href="sent">Sent</a></div>
	<div class="column-left">Welcome <?php //echo $username; ?>!</div>
	<table style="width:73%;margin-top: 5%;">
	  <tr style="font-weight: bold;">
		<td>From</td>
		<td>Subject</td> 
		<td>Email</td> 
		<td>Attachment</td> 
		<td>Date</td>
		<td>Action</td>
	  </tr>
	<?php foreach($populate as $row){if($row->status!=3){echo $row->status;?>  
	<td><?php if($row->status==1){$background_color="#fff";}else{$background_color="#dddddd";}?></td>
	<tr    style=" background: <?php echo $background_color?>;">
		<td><?php echo $row->email?></td>
		
		<td><?php echo $row->email_subject?></td>
		<td><?php echo $row->email_content?></td>
		<td><?php if($row->attachment!=""){echo "have attachment!";}else{echo "no attachment";}?></td>
		<td><?php echo date("D, d M y H:i:s O",strtotime($row->date_created));?></td>
		<td><a href="view_email_inbox/id/<?php echo $row->email_id?>">view</a></td>
		
		
		
	  </tr><br>
	<?php

	}
	}?>
	</table>
	

</div>
	   
	
	
 
   
 

 </html>
  