<?
session_start();

if(isset($_GET['logout'])){	
	
	//Simple exit message
	$fp = fopen("log.html", 'a');
	fwrite($fp, "<div class='msgln'><i>Användare ". $_SESSION['name'] ." har lämnat konversationen.</i><br></div>"); //User [username] has left the conversation
	fclose($fp);
	
	session_destroy();
	header("Location: index.php"); //Redirect the user
}

function loginForm(){
	echo'
	<div id="loginform">
	<form action="index.php" method="post">
		<p>Ange ett användarnamn om du vill fortsätta:</p> <!--Enter a username if you want to continue-->
		<label for="name">Användarnamn:</label> <!--Username-->
		<input type="text" name="name" id="name" />
		<input type="submit" name="enter" id="enter" value="Kom in i värmen" /> <!--Enter-->
	</form>
	</div>
	';
}

if(isset($_POST['enter'])){
	if($_POST['name'] != ""){
		$_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
	}
	else{
		echo '<span class="error">Du måste ha ett användarnamn</span>'; //"You need a valid username!"
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SimpleTalk 1.0 - Swedish</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<?php
if(!isset($_SESSION['name'])){
	loginForm();
}
else{
?>
<div id="wrapper">
	<div id="menu">
		<p class="welcome">Välkommen, <b><?php echo $_SESSION['name']; ?></b></p> <!--Welcome [user]-->
		<p class="logout"><a id="exit" href="#">Logga ut från chatten</a></p> <!--Log out from chat-->
		<div style="clear:both"></div>
	</div>	
	<div id="chatbox"><?php
	if(file_exists("log.html") && filesize("log.html") > 0){
		$handle = fopen("log.html", "r");
		$contents = fread($handle, filesize("log.html"));
		fclose($handle);
		
		echo $contents;
	}
	?></div>
	
	<form name="message" action="">
		<input name="usermsg" type="text" id="usermsg" size="63" />
		<br />
		<div id="license"><i>License info</i></div>
		<div id="licenseinfo"><a rel="license" href="http://creativecommons.org/licenses/by-sa/2.5/se/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/2.5/se/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">SimpleTalk</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/Rickythemaniac/SimpleTalk" property="cc:attributionName" rel="cc:attributionURL">Rickard Ahlstedt</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/2.5/se/">Creative Commons Attribution-ShareAlike 2.5 Sweden License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="https://github.com/Rickythemaniac/SimpleTalk" rel="dct:source">github.com</a>.<br />Permissions beyond the scope of this license may be available at <a xmlns:cc="http://creativecommons.org/ns#" href="https://github.com/Rickythemaniac/SimpleTalk" rel="cc:morePermissions">https://github.com/Rickythemaniac/SimpleTalk</a>.</div>
		<!--<input name="submitmsg" type="submit"  id="submitmsg" value="Send" />-->
	</form>
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.js"></script>
<script type="text/javascript" src="https://raw.github.com/cowboy/javascript-linkify/master/ba-linkify.js"></script>
<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
    $("#licenseinfo").slideToggle("slow");
    $("#chatbox").animate({scrollTop: $("#chatbox").height()*($("#chatbox").height()/2)}, 'slow'); //Continous scroll when ever a user joins
    $("#usermsg").focus();
        $("#usermsg").keypress(function(e){
           var clientmsg = $("#usermsg").val(); 
           if(e.keyCode == '13'){
               var msg = linkify(clientmsg)
               $.post("post.php", {text: msg});
               $("#usermsg").attr("value", "");
               return false
           }
        });
    
	$("#license").click(function(){
		$("#licenseinfo").slideToggle("slow");
	});
	
	//If user submits the form using the buttin
	$("#submitmsg").click(function(){	
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		return false;
	});
	
	//Load the file containing the chat log
	function loadLog(){		
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html); //Insert chat log into the #chatbox div				
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				}
		  	}
		});
	}
	setInterval (loadLog, 1500);	//Reload file every 2.5 seconds
	
	//If user wants to end session
	$("#exit").click(function(){
		var exit = confirm("Vill du verkligen logga ut?"); //"Do you really want to log out from the current session?"
		if(exit==true){window.location = 'index.php?logout=true';}		
	});
});
</script>
<?php
}
?>
</body>
</html>