<?php
function Openmeetingswidget( $instance )
{
include ('../wp-config.php');
//	Loading wordpress logged in user
global $current_user;
get_currentuserinfo();

//	setting variables
$Url = apply_filters('widget_hostname', $instance['hostname'] );
$omadmin = apply_filters('widget_admin', $instance['admin'] );
$ompassword = apply_filters('widget_password', $instance['password'] );
$roomid = apply_filters('widget_roomid', $instance['roomid'] );
$widget_text = apply_filters('widget_widget_text', $instance['widget_text'] );
$imgurl = apply_filters('widget_widget_imgurl', $instance['widget_imgurl'] );

$firstname = apply_filters('widget_widget_text', $current_user->user_firstname );
$lastname = apply_filters('widget_widget_text', $current_user->user_lastname );
$email = apply_filters('widget_widget_text', $current_user->user_email );



//	SOAP
$SoapUsers = new SoapClient('http://'.$Url.'/services/UserService?wsdl');

$Res = array();
$Res = $SoapUsers->getSession();

$idSession = $Res->return->session_id;

$Res = $SoapUsers->loginUser(array('SID' => $idSession, 'username' => $omadmin, 'userpass' => $ompassword));
        $SoapRomm = new SoapClient('http://'.$Url.'/services/RoomService?wsdl');

        $Res = $SoapRomm->getRoomsWithCurrentUsersByList(array('SID' => $idSession, 'start' => 1, 'max' => 10, 'orderby' => 'rooms_id', 'asc' => 0));

        $Res = $SoapUsers->setUserObjectAndGenerateRoomHash(array(
				'SID' => $idSession,
				'username' => 'shuki',
				'firstname' => $firstname,
				'lastname' => $lastname,
				'profilePictureUrl' => '',
                'email' => $email,
                'externalUserId' => '',
                'externalUserType' => '',
                'room_id' => $roomid,
                'becomeModeratorAsInt' => '0',
                'showAudioVideoTestAsInt' => '0'));
				
				//	URL display
	?>

<b><?php echo OMDESC; ?></b>
<br><br><b><a href="http://<?php print $Url;?>/?secureHash=<?php print $Res->return; ?>" target="_blank">
	
<?php
if(empty($widget_text))
{
    ?><img style="text-align:right" src="<?php print $imgurl; ?>"/><b><?php
}
else
{
	print $widget_text;
}
?>
</b>
</a>
<br>
<br>
<a href="http://get.adobe.com/flashplayer/"><i><?php echo OMLATESTFLASH; ?></i></a>
<?php } ?>
