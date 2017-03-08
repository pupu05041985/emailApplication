<html xmlns="http://www.w3.org/1999/xhtml"><head>
   <title>Compose</title>
   
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
  </script>
  <style>
	.column-left{ float: left; width: 33%; }
	.column-right{ float: right; width: 33%; }
	.column-center{ display: inline-block; width: 33%; }
   </style>
</head>
 
 <body>
	<div class="container" style="margin-left: 20%;">
	<div class="column-left"><a href="../home">Home</a></div>
	<div class="column-center"><a href="logout">Logout</a></div>
	<div class="column-left"><a href="inbox">Inbox</a></div>
	<div class="column-left"><a href="compose">Compose</a></div>
	<div class="column-left"><a href="sent">Sent</a></div>
	<div class="column-left">Welcome <?php echo $username; ?>!</div>
	<div class="column-left"></div>
	<div class="column-center"><?php echo $msg;?><br>
		<form action="sendemail" method="post" enctype="multipart/form-data" style="margin-left: 74%;">
			To: <br><input type="text" id="email" name="email" ><br><br>
			Subject: <br><input type="text" id="subject" name="subject"><br><br>
			Body:<br><textarea rows="15" name="message" id="message" cols="30"></textarea><br><br>
			<input type="file" name="userfile" size="20"><br>
			<br><input type="submit" name="submit" value="Submit">
		</form>
	</div>
	<div class="column-right"></div>
   </div>
 </html>
  