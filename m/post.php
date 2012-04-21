<?
session_start();
if(isset($_SESSION['name'])){
	$text = $_POST['text'];
	
	$fp = fopen("../log/log.html", 'a');
        /*
         * Until I can get this working smoothly, the smiley function will not be present :(
         * To enable it anyways, uncomment the section below and change 
         * "stripslashes(strip_tags($text, '<a><img>'))" to
         * "stripslashes(strip_tags($pushtext, '<a><img>'))"
         * SUPPLY WITH YOUR OWN SMILEYS!
         */
        /*
        $pushtext = str_replace(";)",'<img src="emotes/"></img>',$text);
        $pushtext = str_replace(":@",'<img src="emotes/"></img>',$text);
        $pushtext = str_replace("<.<",'<img src="emotes/"></img>',$text);        
        $pushtext = str_replace(":(",'<img src="emotes/"></img>',$text);
        $pushtext = str_replace(">.<",'<img src="emotes/"></img>',$text);
        $pushtext = str_replace(";(",'<img src="emotes/"></img>',$text);*/
	fwrite($fp, "<div class='msgln'>[".date("g:i A")."] <b>".$_SESSION['name']."</b>: ".stripslashes(strip_tags($text, '<a><img>'))."<br></div>");
	fclose($fp);
}
?>