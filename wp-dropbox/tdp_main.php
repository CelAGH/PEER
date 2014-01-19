<?php
include ("./wp-config.php");
/*
Plugin Name: The dropbox plugin
Plugin URI: http://software.o-o.ro/dropbox-plugin-for-wordpress/
Description: Dropbox in wordpress. This version is held together with duct tape and chewing gum, but it works.
Version: 0.105
Author: Andrew M
Author URI: http://software.o-o.ro


READ THIS:
Abandon all hope ye who enter here.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.*/

function tdp_options()
{
    add_options_page("The Dropbox Plugin", "TDP Options", 'activate_plugins', 'dropbox-plugin/tdp_options.php');
}
add_action('admin_menu', 'tdp_options');
add_action( 'admin_init', 'tdp_init' );
add_action( 'wp_footer' , 'tdp_link' );
add_shortcode('dropbox', 'show_dropbox');
add_action('init', 'tdp_elephant_sandwitch');



function tdp_init(){
	register_setting( 'tdp-opt', 'tdp_mail' );
	register_setting( 'tdp-opt', 'tdp_pass' );
	register_setting( 'tdp-opt', 'tdp_dir' );
	register_setting( 'tdp-opt', 'tdp_cred' );
	register_setting( 'tdp-opt', 'tdp_date' );
	register_setting( 'tdp-opt', 'tdp_size' );

}

function tdp_link()
{if(get_option('tdp_cred')!=1){echo'<a href="http://software.o-o.ro" alt="Software, projects and code"> <img src="';bloginfo('wpurl');echo '/wp-content/plugins/dropbox-plugin/cred.jpg"> </a>'; }
}

function gettill($string,$separator)
{return substr(strrchr($string, $separator), 1);}


function fixspaces($string)
{return str_replace("%2F", "/", rawurlencode($string));}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

function placenames($fh,$itm,$alnav,$aldw,$pnme_sepa){

$ou="";
$temp=str_ireplace($fh,"",$itm["path"]);
$aaa = $temp;
//echo $aaa;
$bbb = substr($aaa, 1);
$temp = $bbb;
  //$ou.="<td>";
$filename = trim(strrchr($temp, "/"),"/");
  if(($itm["is_dir"] && $alnav=="true")||(!$itm["is_dir"] && $aldw=="true"))
	$ou.="<a href='".$pnme_sepa."g=".$temp."'>".$filename."</a>";
  else $ou.=trim(strrchr($temp, "/"),"/");
//$ou.="</td>";

return $ou;}


function displayname($fh,$itm,$alnav,$aldw,$pnme_sepa){

db_conn();
$ou="";

$temp=str_ireplace($fh,"",$itm["path"]);
$filenamed = trim(strrchr($temp, "/"),"/");

$displaynamed = mysql_query("SELECT displayname FROM `wp_peer_dropbox`  WHERE `filename` = '$filenamed'");
$row = mysql_fetch_array($displaynamed, MYSQL_ASSOC);

	$ou.="<a href='".$pnme_sepa."g=".$temp."'>".$row['displayname']."</a>";

mysql_close();
return $ou;

}

function displaynamewithoutlink($fh,$itm,$alnav,$aldw,$pnme_sepa){

db_conn();
$ou="";

$temp=str_ireplace($fh,"",$itm["path"]);
$filenamed = trim(strrchr($temp, "/"),"/");

$displaynamed = mysql_query("SELECT displayname FROM `wp_peer_dropbox`  WHERE `filename` = '$filenamed'");
$row = mysql_fetch_array($displaynamed, MYSQL_ASSOC);

	$ou.=$row['displayname'];

mysql_close();
return $ou;

}


function dplacenames($fh,$itm,$alnav,$aldw,$pnme_sepa){
$ou="";
$temp=str_ireplace($fh,"",$itm["path"]);

	$ou.=trim(strrchr($temp, "/"),"/");
 
return $ou;}

function showicon($thing)
{$temp=""; $pth=dirname(__FILE__) . "/images";$pth2=WP_PLUGIN_URL ."/dropbox-plugin/images";
if ($thing['is_dir']){
if(file_exists($pth."/folder.png")) 
$temp.= "<img src='$pth2/folder.png' />";
}
  else{$ext=gettill($thing["path"], ".");
if(file_exists($pth."/".$ext.".png")) 
$temp.= "<img src='$pth2/" . $ext . ".png' />";
 else if(file_exists($pth."/default.png")) $temp.= "<img src='$pth2/default.png' />";
}

return $temp;
}


function dshowicon($thing)
{$temp=""; $pth=dirname(__FILE__) . "/images";$pth2=WP_PLUGIN_URL ."/dropbox-plugin/images";

if ($thing==".pdf"){
$temp.= "<img src='$pth2/pdf.png' />";
}
else if ($thing==".doc"){
$temp.= "<img src='$pth2/doc.png' />";
}
else if ($thing=="docx"){
$temp.= "<img src='$pth2/doc.png' />";
}
else if ($thing==".ppt"){
$temp.= "<img src='$pth2/ppt.png' />";
}
else if ($thing=="pptx"){
$temp.= "<img src='$pth2/ppt.png' />";
}
else if ($thing==".xls"){
$temp.= "<img src='$pth2/xls.png' />";
}
else if ($thing=="xlsx"){
$temp.= "<img src='$pth2/xls.png' />";
}
else if ($thing==".jpg"){
$temp.= "<img src='$pth2/jpg.png' />";
}
else if ($thing==".txt"){
$temp.= "<img src='$pth2/txt.png' />";
}
else if ($thing==".avi"){
$temp.= "<img src='$pth2/avi.png' />";
}
else if ($thing==".png"){
$temp.= "<img src='$pth2/png.png' />";
}
else if ($thing=="html"){
$temp.= "<img src='$pth2/html.png' />";
}
else if ($thing==".htm"){
$temp.= "<img src='$pth2/html.png' />";
}
else if ($thing==".mp3"){
$temp.= "<img src='$pth2/mp3.png' />";
}
else if ($thing==".zip"){
$temp.= "<img src='$pth2/zip.png' />";
}
else if ($thing==".rar"){
$temp.= "<img src='$pth2/rar.png' />";
}
else {
$temp.= "<img src='$pth2/_page.png' />";
}


return $temp;
}

function db_conn(){

include ('./wp-config.php');

    // serwer
    $mysql_server = "localhost";
    // admin
    $mysql_admin = DB_USER;
    // has³o
    $mysql_pass = DB_PASSWORD;
    // nazwa baza
    $mysql_db = DB_NAME;

    // nawi¹zujemy po³¹czenie z serwerem MySQL
    mysql_connect($mysql_server, $mysql_admin, $mysql_pass)
    or die('No database connection - error in dropbox plugin');
    // ³¹czymy siê z baz¹ danych
    @mysql_select_db($mysql_db)
    or die('Wrong database');
}

function report($thing)
{
db_conn();
$state = "1";
$dodaj = mysql_query("UPDATE `wp_peer_dropbox` SET `spam`='$state' WHERE `filename` = '$thing'");

echo '<b><font color="black">Note: </font><font color="black">'.$thing.' as <u>spam</u> reported!</font></b>';
mysql_close();
}

function delete($thing)
{
db_conn();
$state = "0";
$dodaj = mysql_query("UPDATE `wp_peer_dropbox` SET `visible`='$state' WHERE `filename` = '$thing'");

echo '<b><font color="black">Note: </font><font color="black">'.$thing.' deleted!</font></b>';
mysql_close();
}

function peerrenamedisplay($thing){

if (isset($_GET['rename'])){
if ($_GET['rename']==$thing){
echo '<hr><form action="http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] .'" method="GET">';
echo "<font color='black'><b>Change name of: ";
echo '<input type=text name=oldname size=40 value="'.$thing.'"><br>';
echo "<br>New name:";
echo '<input type=text name=newname><br><br>';
echo '<input type=submit value="Change name">';
echo '</form><hr>';

echo "</b></font>";
}
}

};

function peerrename($thing, $newthing)
{
db_conn();
$sql = mysql_query("UPDATE `wp_peer_dropbox` SET displayname='$newthing' WHERE filename='$thing'");
echo '<b><font color="black"><b>Note: </b></font><font color="black">'.$thing.' renamed.<br> <b>New name:</b> '.$newthing.'</font></b>';
mysql_close();
}


function owner($thing)
{
db_conn();

$dodaj = mysql_query("SELECT author FROM `wp_peer_dropbox`  WHERE `filename` = '$thing'");
$row = mysql_fetch_array($dodaj, MYSQL_ASSOC);
mysql_close();


return $row['author'];
}



function tdp_elephant_sandwitch()
{session_start();
$allowdownload=$_SESSION['tdp_allowdownload'];
$allownavigate=$_SESSION['tdp_allownavigate'];
if(isset($_GET['g'])&&($allowdownload=="true")&&($allownavigate=="true"||($allownavigate="false" && strrpos($_GET['g'],"/")==0)))
{$sometemp=$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$firsttemp='g=';
$secondtemp=strpos($sometemp, $firsttemp);
if($secondtemp!==false)$anothertemp=substr($sometemp, 0, $secondtemp-1);
else $anothertemp=$sometemp;
$lasttemp=strpos($_SESSION['tdp_check'], $firsttemp);
if($lasttemp!==false)$lasttemp=substr($_SESSION['tdp_check'], 0, $lasttemp-1);
else $lasttemp=$_SESSION['tdp_check'];

if($lasttemp!=$anothertemp)die("if you see this you were probably not playing nice, if you were, this error comes from the dropbox plugin");
$consumerKey=get_option('tdp_mail');
$consumerSecret=get_option('tdp_pass');
$forcedhome=$_SESSION['tdp_forcedhome'];
require_once'dropbox.php';
$dropbox = new Dropbox($consumerKey, $consumerSecret);
if(get_option('tdp_tokens')){
$temptok=get_option('tdp_tokens');
$dropbox->setOAuthToken($temptok['oauth_token']);
$dropbox->setOAuthTokenSecret($temptok['oauth_token_secret']);

 $gimmeit=$forcedhome.$_GET['g'];

  
 $info = $dropbox->metadata("$gimmeit",1000,false,true,false);
   $filename=gettill($gimmeit,"/");
   
if (!$info['is_dir']){
  header("Content-Type: ".$info["mime_type"]);
  header("Content-Disposition: attachment; filename=\"".$filename."\"");
 
 $temp=$dropbox->filesGet("$gimmeit",false);
echo base64_decode($temp["data"]);
exit();}


}

}}




function show_dropbox($atts)
 {
include ('./wp-config.php');

$homepath = 'Apps/'.DROPBOX_HOME;
echo "aaaa";

extract(shortcode_atts(array(
'home'=> DROPBOX_HOME,
'separator'=>'~',
'hometext'=>'home',
'dirsfirst'=>'true',
'orderby'=>'path',
'orderdir'=>-1,
'showdirs' => "true",
'dateformat'=>'j F Y h:i:s A',
'allowdownload'=>"true", 
'allownavigate'=>"true",
'happyerror'=>'Something exploded! Refresh or go back and try again',
'columns'=>'INDSD',
'asctxt'=>'&#x25b2;',
'desctxt'=>'&#x25bc;',
'ordering'=>"false",
'allowupload'=>"true")
, $atts));

$forcedhome=$home;

if(isset($_GET['oldname']) && isset($_GET['newname'])){
$peernewname = $_GET['newname'];
$peeroldname = $_GET['oldname'];
peerrename($peeroldname,$peernewname);
}

db_conn();

$happyerror=$happyerror;
$letuserplaywithdetails=$ordering;

$_SESSION['tdp_forcedhome']=$forcedhome;
$_SESSION['tdp_allowdownload']=$allowdownload;
$_SESSION['tdp_allownavigate']=$allownavigate;
$_SESSION['tdp_check']=$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];



$output="";
$consumerKey=get_option('tdp_mail');
$consumerSecret=get_option('tdp_pass');
$pagename="";
$pagename.="http"; if ($_SERVER["HTTPS"] == "on")$pagename.='s';$pagename.="://";

$pagename.=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
if($tdp_sps=strrpos($pagename,"?g="))$pagename=substr($pagename,0,$tdp_sps);
else if($tdp_sps=strrpos($pagename,"&g="))$pagename=substr($pagename,0,$tdp_sps);
if(!strpos($pagename,"?"))$tdp_fancysep="?";
else $tdp_fancysep="&";


require_once'dropbox.php';
$dropbox = new Dropbox($consumerKey, $consumerSecret);
if(get_option('tdp_tokens')){
$temptok=get_option('tdp_tokens');
$dropbox->setOAuthToken($temptok['oauth_token']);
$dropbox->setOAuthTokenSecret($temptok['oauth_token_secret']);


if(isset($_GET['report'])){
report($_GET['report']);
}

if(isset($_GET['delete'])){
delete($_GET['delete']);
}

if(isset($_GET['g'])&&($allowdownload=="true" || $allownavigate=="true"))
{ $gimmeit=$forcedhome.$_GET['g'];
 $info = $dropbox->metadata("$gimmeit",1000,false,true,false);

 //$filename=gettill($gimmeit,"/");




 if($allownavigate=="true")
$where=$_GET["g"];
else $output.=$happyerror;
}
else $where="/";


$author = $_GET['filename'];
if (isset($_GET['filename'])){
$output.= '<b><font color="black">Note: </font><font color="green">Filename ' . $author . ' successfully added</font></b><br> '; 
};

$current_user = wp_get_current_user();

if (current_user_can( 'manage_options' )){
$output.='<font color="red"><b>Administrator warning!</b><br> Files:<br>';

db_conn();
$wynikasd = mysql_query("SELECT * FROM wp_peer_dropbox WHERE spam='1'") or die ("Blad!");
while($r = mysql_fetch_assoc($wynikasd)) { 

$output.=$r['filename'];
$output.="<br>";
		}
mysql_close();



$output.='are reported as spam! Maybe you should delete this files?</font><br><br>';
};



$output.=' 
<a href="http://'. dirname($_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']) . '/wp-dropbox/?author='. $current_user->user_login . '"><button>'.REPADD.'</button></a>
<iframe src=" http://'. dirname($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']) . '/wp-dropbox/try.php" width="1" height="1"></iframe><br>
';

if($allownavigate=="true"){
$allthedirs=explode("/",$where);
//$output.="<a href='$pagename'>$hometext</a>$separator";

$thesemany=sizeof($allthedirs);   
if($where!='/')for ($i=1;$i<$thesemany;$i++)
{

$output.="<a href='$pagename";
 
$output.=($tdp_fancysep."g=");
$temp=$allthedirs[$i];
 $allthedirs[$i]=$allthedirs[$i-1]."/".$allthedirs[$i];

$output.=$allthedirs[$i]; 
$output.="'>$temp</a>$separator";

}
}
$output.="<table border='1'>";
$stuff=$dropbox->metadata($forcedhome.$where,1000,false,true,false);

$stuff=$stuff["contents"];

//echo $stuff["contents"];

foreach($stuff as &$thing )
{
 
$thing['modified']=strtotime($thing['modified']);
}

///////////////////////////SORTOWANIE/////////////////////////////////
$orderby = "filename";

if(isset($_GET['o'])&& isset($_GET['d'])&& $ordering =="true")
{
switch($_GET['o'])
{case "filename":$orderby="filename";break;
 case "displayname": $orderby="displayname"; break;
 case "date":$orderby="date";break;
 default: $orderby="displayname"; break;
}

switch($_GET['d'])
{case "asc":$orderdir=-1;break;
 default: $orderdir=1; break;
}



//if($orderdir==-1)$stuff=array_orderby($stuff,$orderby,SORT_ASC); 
//else $stuff=array_orderby($stuff,$orderby,SORT_DESC); 

//rosnaco ASC
if($orderdir==-1){
echo $orderby;
db_conn();
$wynik = mysql_query("SELECT * FROM wp_peer_dropbox ORDER BY `$orderby` ASC") or die ("Bslad!");
$ilosc_wierszy = mysql_num_rows($wynik);
$licznik = 0;
$sortitem = array();
    while($r = mysql_fetch_assoc($wynik)) { 
	
	$sortitem[$licznik] = $r[$orderby];
	// $sortitem[$licznik];
	$licznik = $licznik + 1;
	//echo "<br>";
	//echo $licznik;
	}
mysql_close();


}

else
{

db_conn();
$wynik = mysql_query("SELECT * FROM wp_peer_dropbox  ORDER BY `$orderby` DESC") or die ("Blad!");
$ilosc_wierszy = mysql_num_rows($wynik);
$licznik = 0;
$sortitem = array();

    while($r = mysql_fetch_assoc($wynik)) { 
	$sortitem[$licznik] = $r[$orderby];
	//echo $sortitem[$licznik];
	$licznik = $licznik + 1;
	//echo "<br>";
	//echo $licznik;
	}
mysql_close();

}


}	
//condition without get variable
else
{

db_conn();
$wynik = mysql_query("SELECT * FROM wp_peer_dropbox  ORDER BY `$orderby` DESC") or die ("Blad!");
$ilosc_wierszy = mysql_num_rows($wynik);
$licznik = 0;
$sortitem = array();

    while($r = mysql_fetch_assoc($wynik)) { 
	$sortitem[$licznik] = $r[$orderby];
	//echo $sortitem[$licznik];
	$licznik = $licznik + 1;
	//echo "<br>";
	//echo $licznik;
	}
mysql_close();

}
///////////////////////////KONIEC SORTOWANIE/////////////////////////////////




$columnssplit=str_split($columns);

if($letuserplaywithdetails=="true"){$output.="<tr>";
foreach ($columnssplit as $column){
switch ($column){
case "F":$output.="<td>".REPFILENAME.": <a href='"."?o=filename&d=asc'>$asctxt</a><a href='"."?o=filename&d=dsc'>$desctxt</a></td>";break;
case "N":$output.="<td>".REPDISPLAYNAME.": <a href='"."?o=displayname&d=asc'>$asctxt</a><a href='"."?o=displayname&d=dsc'>$desctxt</a></td>";break;
case "S":$output.="<td>".REPSIZE."</td>";break;
case "D":$output.="<td>".REPDATE.": <a href='".$pagename.$tdp_fancysep."g=$where&o=date&d=asc'>$asctxt</a><a href='".$pagename.$tdp_fancysep."g=$where&o=date&d=dsc'>$desctxt</a> </td>";break;
case "I":$output.="<td></td>";break;
case "R":$output.="<td>".REPREPORT."</td>";break;
case "O":$output.="<td>".REPOWNER."</td>";break;
case "U":$output.="<td>".REPDELETE."</td>"; break;


}
}
$output.="</tr>";
}


foreach ($sortitem as $sorted){

foreach ($stuff as $item)
{ 
$dfilenames = dplacenames($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$ddisplayname = displaynamewithoutlink($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$ddate = strtotime(date($dateformat,$item["modified"]));

if ($dfilenames == $sorted || $ddisplayname == $sorted || $ddate == $sorted){


db_conn();
$ifdeleted = mysql_query("SELECT visible FROM `wp_peer_dropbox`  WHERE `filename` = '$dfilenames'");
$row = mysql_fetch_array($ifdeleted, MYSQL_ASSOC);
mysql_close();

if($row['visible']!="0"){

if(($item["is_dir"] && $showdirs=="true")||!$item["is_dir"]){


$output.="<tr>";
foreach ($columnssplit as $column){
switch ($column){

//FILENAME
case "F":
$output.='<td>';
$output.= placenames($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);break;
$output.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
break;

//DISPLAYNAME WITH RENAME
case "N":
$output.='<td>';
$dfilenames = dplacenames($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$pth2=WP_PLUGIN_URL ."/dropbox-plugin/images";
$cond = owner($dfilenames);
$user = wp_get_current_user();

if ($user->user_login == $cond){
$output.="<a href='?rename=$dfilenames' rel='#overlay'><img src='$pth2/rename.png' width='20' alt='Rename file' /></a>&nbsp;&nbsp;";
peerrenamedisplay($dfilenames);
} 
else if (current_user_can( 'manage_options' )){
$output.="<a href='?rename=$dfilenames' rel='#overlay'><img src='$pth2/rename.png' width='20' alt='Rename file' /></a>&nbsp;&nbsp;";
peerrenamedisplay($dfilenames);
}
else {
$output.="<img src='$pth2/rename_empty.png' width='24' alt='Rename file' />&nbsp;&nbsp;";
}
$output.=displayname($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$output.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
break;

//SIZE
case "S":
$output.="<td>".$item["size"]."</td>";
break;

//REPORT FUNCTION
case "R": $pth2=WP_PLUGIN_URL ."/dropbox-plugin/images";
$dfilenames = dplacenames($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$output.="<td><a href='?report=$dfilenames'><img src='$pth2/spam.png' alt='Report spam file' /></a>
"."</td>";
break;

//DELETE FUNCTION (polish: Usuwanie)
case "U": $pth2=WP_PLUGIN_URL ."/dropbox-plugin/images";
$dfilenames = dplacenames($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$cond = owner($dfilenames);
$user = wp_get_current_user();

if ($user->user_login == $cond){
$output.="<td><a href='?delete=$dfilenames'><img src='$pth2/del.png' width='24' alt='Delete file' /></a>"."</td>";
}
else if (current_user_can( 'manage_options' )){
$output.="<td><a href='?delete=$dfilenames'><img src='$pth2/del.png' width='24' alt='Delete file' /></a>"."</td>";
}
else {
$output.="<td><img src='$pth2/del_gray.png' width='24' alt='You are not owner' /></td>";
}
break;

//DATE

case "D":

db_conn();
$costam = mysql_query("SELECT date FROM `wp_peer_dropbox`  WHERE `filename` = 'list of EN peer users.pdf'");
$row = mysql_fetch_array($costam, MYSQL_ASSOC);
mysql_close();

$output.="<td>".date($dateformat,$item["modified"])."</td>";
break;

//OWNER OF FILE
case "O":
$dfilenames = dplacenames($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$output.="<td>".owner($dfilenames)."</td>";break;

//FANCY ICON
case "I":
$dfilenames = dplacenames($forcedhome,$item,$allownavigate,$allowdownload,$pagename.$tdp_fancysep);
$dfilenamesformat = substr($dfilenames,-4);
$output.="<td>".dshowicon($dfilenamesformat)."</td>";

break;
}
;
  
}

$output.="</tr>";
}
}
}
}
//if sorted
}
//koniec foreach
$output.="</table>";
/*
if($allowupload=="true"){
$output.=' <br/><form method="post" action="" enctype="multipart/form-data">
        				
            <input type="file" name="file" /><input type="submit" value="Upload" />
       </form>';
tdp_upload($forcedhome.$where);

}
*/
if($allowupload=="true")
{
//print_r($_SERVER);
//$output.= $_SERVER['PHP_SELF'];
$output.=' 
<a href="http://'. dirname($_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']) . '/wp-dropbox/?author='. $current_user->user_login . '"><button>'.REPADD.'</button></a>
<iframe src=" http://'. dirname($_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']) . '/wp-dropbox/try.php" width="1" height="1"></iframe>
';}
}

else $output="Don't forget to connect the plugin to your dropbox";



return $output;
}


function tdp_upload($targetdir)
{ini_set('memory_limit', '32M');
if(isset($_FILES['file']['name']))
 {
   $consumerKey=get_option('tdp_mail');
$consumerSecret=get_option('tdp_pass');

require_once'dropbox.php';
$dropbox = new Dropbox($consumerKey, $consumerSecret);
$temptok=get_option('tdp_tokens');
$dropbox->setOAuthToken($temptok['oauth_token']);
$dropbox->setOAuthTokenSecret($temptok['oauth_token_secret']);

	
    try {
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK)
            throw new Exception('File was not successfully uploaded from your computer.');
       
        $tmpDir = uniqid('/tmp/theDdropboxPlugin-');
        if (!mkdir($tmpDir))
            throw new Exception('Can not create temporary directory!');
       
        if ($_FILES['file']['name'] === "")
            throw new Exception('File name error.');
           $tmpFileName=str_replace("/\0", '_', $_FILES['file']['name']);
        $tmpFile = $tmpDir.'/'.$tmpFileName;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $tmpFile))
            throw new Exception('Can not rename uploaded file');
       
        
        $supertemp=($dropbox->filesPost($targetdir, $tmpFile));
        
        echo '<span style="color: green">File successfully uploaded to your Dropbox!</span><br/>';
    } catch(Exception $e) {
        echo '<span style="color: red">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
    }
   
    if (isset($tmpFile) && file_exists($tmpFile))
        unlink($tmpFile);
       
    if (isset($tmpDir) && file_exists($tmpDir))
        rmdir($tmpDir);
}


}

?>
