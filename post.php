<?
session_start();
if(isset($_SESSION['name'])){
	$text = $_POST['text'];
	
	$fp = fopen("log/log.html", 'a');
        //Thanks to Squaredwhale for the fix :D
        error_reporting(E_ALL);
        ini_set('display_errors','1');
        
        $patterns = array();
        $patterns[0] = "/:\)/";
        $patterns[1] = "/:\(/";
        $patterns[2] = "/:\D/";
        $patterns[3] = "/:\O/";
        $patterns[4] = "/;\)/";
        
        $replacement = array();
        $replacement[0] = "<img src='emotes/Smile.png' />";
        $replacement[1] = "<img src='emotes/Sad.png' />";
        $replacement[2] = "<img src='emotes/Grin.png' />";
        $replacement[3] = "<img src='emotes/Surprise.png' />";
        $replacement[4] = "<img src='emotes/Wink.png' />";
        
        $text = preg_replace($patterns,$replacement,$text);
        
	fwrite($fp, "<div class='msgln'>[".date("g:i A")."] <b>".$_SESSION['name']."</b>: ".stripslashes(strip_tags($text, '<a><img>'))."<br></div>");
	fclose($fp);
}
?>