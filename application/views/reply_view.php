<html xmlns="http://www.w3.org/1999/xhtml"><head>
   <title>Reply</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>

   $( function() {
    var availableTags = [
      <?php echo $emails?>   ];
     function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#email" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  } );
  } );
  } );
  } );
 

  </script>
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
<?php // echo "<pre>";print_r($populate);echo "</pre>";?>
	   <div class="column-left"></div>
	<div class="column-center"><?php //echo $msg;?><br>
	   <form action="../../replyemail" method="post" enctype="multipart/form-data" style="margin-left: 74%;">
			Reply To: <input type="text" id="email" name="email"  value="<?php echo $populate['from']?>"><br>
			Subject: <input type="text" id="subject" name="subject"  value="<?php echo $populate['subject']?>"><br>
			Body:<br><textarea rows="25" name="message" id="message" cols="50"  onkeyup="sendCode()"><?php echo $populate['message'] ."\r\n"?></textarea><br>
			
			<input type="file" name="userfile" size="20"><br>
			<p>File : <a href="uploades/<?php echo $populate['attachment']?>" download><?php echo $populate['attachment']?> </a></p>
			<br><input type="submit" name="reply" value="Reply">
			<input type="hidden" name="file" value="<?php echo $populate['attachment']?>">
			<input type="hidden" name="message_intact" id="message_intact" value="<?php echo $populate['message']?>">
			<input type="hidden" name="message_reply" id="message_reply" value="<?php echo "\r\n". $populate['reply_email']." replied at :". $populate['date_created'];?>">
			<input type="hidden" name="message_add" id="message_add" value="">
		</form>
	</div>
	   <div class="column-right"></div>
   </div>

<script>
	var input = document.getElementById("message");
	var output = document.getElementById("message_add");

	function sendCode(){
	output.value = input.value;
	}
</script>
 
   
 

 </html>
  