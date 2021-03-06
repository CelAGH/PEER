<?php setcookie ("peeroverlaydisplay", "No"); ?>

<?php
error_reporting(E_ALL);
require_once("DropboxClient.php");
include ('../wp-config.php');

// you have to create an app at https://www.dropbox.com/developers/apps and enter details below:

$dropbox = new DropboxClient(array(
	'app_key' => DROPBOX_KEY, 
	'app_secret' => DROPBOX_SECRET,
	'app_full_access' => true,
),'en');

handle_dropbox_auth($dropbox); // see below
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-2">

<style type="text/css">

#href {
     margin:0 auto;
     width:400px;
     height:400px;
     border:1px solid black;
}

#infop {
    float:center;
    margin:0px; width:640px;
    visibility:hidden;      
    color:white;
    text-align:center;
}
</style>

<script type="text/javascript">
function infop(x){
if(!x){
  document.getElementById('infop').style.visibility='hidden'
  return
}
with(document.getElementById('infop')){
  innerHTML=x+
  '<p><a href="javascript:infop()"></a></p>'
  style.visibility='visible'
}
}
info='<img src="loading.gif" />'
</script>

</head>

<body>

<form enctype="multipart/form-data" method="POST" action="">
<p>	<br>
	<?php echo REPUPLOAD; ?><br><br><font size="2"><?php echo REPLOGGED; ?> <i><?php echo $_COOKIE["peerusername"]; ?></i></font><br><hr>
	
	<font size="2"><?php echo REPPATH; ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="file" name="the_upload" /><br><br></font>
	<font size="2"><?php echo REPOWNNAME; ?>:&nbsp&nbsp<input type="text" name="displayname" /></font>
	<hr>	
</p>

<div id="infop"></div>

<p>
<input onclick="location.href='javascript:infop(info)';" type="submit" name="submit-btn"  value="<?php echo REPUP; ?>">
</p>

</form>

</body>
</html>



<?php 

// ================================================================================


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

