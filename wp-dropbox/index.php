<?php
 
error_reporting(E_ALL);
include ('../wp-config.php');
require_once("DropboxClient.php");

$author = $_GET['author'];
setcookie("peerusername", $_GET['author'], time()+ 3600);

// you have to create an app at https://www.dropbox.com/developers/apps and enter details below:

$dropbox = new DropboxClient(array(
	'app_key' => DROPBOX_KEY, 
	'app_secret' => DROPBOX_SECRET,
	'app_full_access' => true,
),'en');

handle_dropbox_auth($dropbox); // see below

require "dbconn.php";
connection();

// if there is no upload, show the form
if(empty($_FILES['the_upload'])) {
?>


<!doctype html>
<!--
   Flowplayer JavaScript, website, forums & jQuery Tools by Tero Piirainen
   Prefer web standards over Flash. Video is the only exception (for now).
-->
<html lang="en">
   <head>
   

<style>

body {

background-color:#76a0b6;
} 
 
/* the overlayed element */
.apple_overlay {

    /* initially overlay is hidden */
    display:none;

    /* growing background image */
    background-image:url(white.png);

    /*
      width after the growing animation finishes
      height is automatically calculated
      */
    width:640px;

    /* some padding to layout nested elements nicely  */
    padding:35px;

    /* a little styling */
    font-size:18px;
    font-family: Arial;
}

/* default close button positioned on upper right corner */
.apple_overlay .close {
    background-image:url(close.png);
    position:absolute; right:5px; top:5px;
    cursor:pointer;
    height:35px;
    width:35px;
}
    /* use a semi-transparent image for the overlay */
  #overlay {
    background-image:url(background.png);
    color:#efefef;
    height:450px;
  }
  /* container for external content. uses vertical scrollbar, if needed */
  div.contentWrap {
    height:441px;
    overflow-y:auto;
  }
</style>

<script src="jquery.tools.min.js"></script>

<script>
$(function() {

    // if the function argument is given to overlay,
    // it is assumed to be the onBeforeLoad event listener
    $("a[rel]").overlay({

        mask: 'gray',
        effect: 'apple',

        onBeforeLoad: function() {

            // grab wrapper element inside content
            var wrap = this.getOverlay().find(".contentWrap");

            // load the page specified in the trigger
			if (wrap.is(":empty")){
            wrap.load(this.getTrigger().attr("href"));
			}
        }

    });
});
</script>

<script type="text/javascript">
function autoload() {
document.getElementById('MyLink').click();
         } 
</script>

</head>

<?php
if ($_COOKIE['peeroverlaydisplay']=="No"){
echo '<body>';} else {
echo '
<body onLoad="autoload();">'; }

?>


<!-- external page is given in the href attribute (as it should be) -->
<a href="./sample-form.php" rel="#overlay" style="text-decoration:none" id="MyLink">
  <!-- remember that you can use any element inside the trigger -->
  <button type="button"><?php echo REPUPLOAD; ?></button>
</a>

<!-- overlayed element -->
<div class="apple_overlay" id="overlay">
  <!-- the external content is loaded inside this tag -->
  <div class="contentWrap"></div>
</div>

<!-- make all links with the 'rel' attribute open overlays -->


<?php } else { 
	
	$upload_name = $_FILES["the_upload"]["name"];
	echo $upload_name;	echo "<pre>";
	echo "\r\n\r\n<b>Uploading $upload_name:</b>\r\n";
	$meta = $dropbox->UploadFile($_FILES["the_upload"]["tmp_name"], $upload_name);
	print_r($meta);
	echo "\r\n done!";
	echo "</pre>";
	$user_name = $_COOKIE["peerusername"];
	
	
	
	$data = json_decode($meta); 
	
	echo $user_name;
	echo $data->icon;

	$displayname = $_POST['displayname'];	
	$dateinunix = strtotime($data->modified);

	mysql_query("INSERT INTO wp_peer_dropbox (filename, displayname, author, type, spam, visible, date) VALUES ('$upload_name', '$displayname', '$user_name', '$data->icon', 0, 1, '$dateinunix')")
             or die('B³¹d zapytania'); 

	
	$repomainpage = dirname(dirname($_COOKIE["peerrepomainpage"]));
	header('Location: http://'.$repomainpage.'/repository/?filename=' . $upload_name);
}


// ================================================================================
// store_token, load_token, delete_token are SAMPLE functions! please replace with your own!
function store_token($token, $name)
{
	file_put_contents("tokens/$name.token", serialize($token));
}

function load_token($name)
{
	if(!file_exists("tokens/$name.token")) return null;
	return @unserialize(@file_get_contents("tokens/$name.token"));
}

function delete_token($name)
{
	@unlink("tokens/$name.token");
}
// ================================================================================

function handle_dropbox_auth($dropbox)
{
	// first try to load existing access token
	$access_token = load_token("access");
	if(!empty($access_token)) {
		$dropbox->SetAccessToken($access_token);
	}
	elseif(!empty($_GET['auth_callback'])) // are we coming from dropbox's auth page?
	{
		// then load our previosly created request token
		$request_token = load_token($_GET['oauth_token']);
		if(empty($request_token)) die('Request token not found!');
		
		// get & store access token, the request token is not needed anymore
		$access_token = $dropbox->GetAccessToken($request_token);	
		store_token($access_token, "access");
		delete_token($_GET['oauth_token']);
	}

	// checks if access token is required
	if(!$dropbox->IsAuthorized())
	{
		// redirect user to dropbox auth page
		$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?auth_callback=1";
		$auth_url = $dropbox->BuildAuthorizeUrl($return_url);
		$request_token = $dropbox->GetRequestToken();
		store_token($request_token, $request_token['t']);
		die("Authentication required. <a href='$auth_url'>Click here.</a>");
	}
} ?>



  </body>
</html>

