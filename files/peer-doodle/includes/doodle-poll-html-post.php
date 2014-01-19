<?php // Extract shortcode attributes, set defaults
extract(shortcode_atts(array(
            'poll_id' => ''
                ), $atts)
);

if (!class_exists('DPC_Doodle_Poll'))
    require_once( dirname(__FILE__) . '/class-dpc-poll.php' );

$poll_id = $atts['poll_id'];
global $wpdb;
$query = "SELECT * FROM " . $wpdb->prefix . 'peer_doodle' . " WHERE poll_id = ". $poll_id ;
$model = $wpdb->get_results($query);
$doodle= new DPC_Doodle_Poll($model[0]);
if(isset($model[0])) {
    
} else {
    $doodle->build_xml();
}

$options = array();
$options = $doodle->getOptionsHeaders();

?>
<script type="text/javascript">
function showPool(){
document.getElementById("hiddenpool").style.display = 'block';
}
</script>
<script type="text/javascript">
function hidePool(){
document.getElementById("hiddenpool").style.display = 'none';
}
</script> 
<div style="" class="contentPart clearfix" id="pollArea">
	<hr>
        <p><b>Titel:</b> <?php echo $doodle->getTitle();?></p>
        <p><b>Beschrijving:</b> <?php echo $doodle->getDescription();?></p>
        <p><b>Waar:</b> <?php echo $doodle->getDescription();?></p>
        <p><h6><b><a onclick="showPool(); return false" href="#">Laat zien <?php echo $doodle->getTitle();?> </a></b></h6></p>
	
	
	
	 <div id="hiddenpool">
	 <p><b><a onclick="hidePool(); return false" href="#">Hide</a></b></p>	
        <form class="clearfix">
            <div id="ptContainer">
                <table class="poll" cellspacing="0" cellpadding="0" summary="<?php echo $doodle->getTitle() ?>">
                    <tbody style="text-align: center;">
                        <?php
                       
                        
                            $colspans_month = array();
                            $colspans_days = array();
                            $c = 0;
                            //print_r($options['structure']);
                            foreach ($options['structure'] as $year_key => $year) {
                                foreach ($year as $month_key => $month) {                        
                                    foreach ($month as $day) {                                        
                                        $colspans_days[$c]['counter'] = $day['counter'];
                                        if(array_key_exists('startdatetime', $day)) {
                                            //loop through options
                                             $colspans_days[$c]['startdatetime'] = $day['startdatetime'];
                                             $colspans_days[$c]['enddatetime'] = $day['enddatetime'];
                                        } else if(array_key_exists('date', $day)) {                                            
                                            $colspans_days[$c]['date'] = $day['date'][0];
                                        } else if(array_key_exists('datetime', $day)) {
                                            foreach($day['datetime'] as $d) {
                                               $colspans_days[$c]['datetime'] = $day['datetime']; 
                                            }
                                           
                                        }
 
                                        $colspans_month[$month_key]['counter'] +=$day['counter'];
                                        $c++;    
                                    }
                                }                                
                            }
                            
                         
                        ?>


                            <tr class="header date month">
                                <th class="nonHeader"></th>
                                <?php
                                    foreach ($options['structure'] as $year_key => $year) {
                                        foreach ($year as $month_key => $month) { 
                                            
                                            ?>
                                            <th class="msep"  colspan=<?php echo $colspans_month[$month_key]['counter']; ?>>
                                                <p>
                                                <?php
                                                
                                                echo date("F", mktime(0,0,0,$month_key+1,0,0));
                                                echo " " . $year_key;
                                                ?></p>
                                            </th><?php
                                        } ?>   
                               <?php } ?>       
                                                                     
                            </tr>
                             <tr class="header date month">
                                <th class="nonHeader partCount boldText" style="width: 140px;"></th>
                                <?php
                                    foreach ($colspans_days as $date) { ?>
                                        
                                        <th class="msep" width="120px" colspan=<?php echo $date['counter']; ?>>
                                            <p>
                                                <?php
                                                //print_r($date['datetime']);
                                                    if(array_key_exists('startdatetime', $date))
                                                        echo date("D d", strtotime($date['startdatetime']));
                                                    else if(array_key_exists('date', $date)) 
                                                        echo date("D d", strtotime($date['date']));
                                                    else if(array_key_exists('datetime', $date))
                                                        echo date("D d", strtotime($date['datetime'][0]));
                                                ?>
                                            </p>
                                        </th>
                                           
                               <?php } ?>                                                                            
                            </tr>
                            
                            <tr>
                                <th class="header time">
                                <?php echo $doodle->getNumberOfParticipants(); ?>  participants
                                </th>
                                 <?php foreach ($options['dates'] as $date) {  ?>
                                    <?php
                                            if(array_key_exists('startDateTime', $date)) { ?>
                                                <th class="dsep"><p>
                                            <?php echo date("H:i", strtotime($date['startDateTime'])) ?> -<br>
                                            <?php echo date("H:i", strtotime($date['endDateTime'])) ?><br>
                                                </p></th>
                                      <?php } else if(array_key_exists('date', $date)) { ?>
                                                <th class="dsep"><p>
                                            <?php echo " " ?><br>       
                                                </p></th>  
                                      <?php } else if(array_key_exists('dateTime', $date)) {?>    
                                                <th class="dsep"><p>
                                            <?php  echo date("H:i", strtotime($date['dateTime'])) ?><br>        
                                                </p></th>
                                      <?php } ?>           
                                 <?php } ?>   
                                               
                            </tr>
                            <?php 
                                //participants
                                $p_votes = $doodle->getParticipantsVotes();
                                //print_r($p_votes);
                                foreach($p_votes as $votes) { ?>
                                    <tr>
                                        <th class="nonheader" >
                                            <div class="pname" title="<?php echo $votes['name']?>"><?php echo $votes['name']?></div>
                                         </th>
                                     <?php 
                                     foreach($votes['option'] as $opts) { ?>
                                        <th>
                                            <?php if($opts == 1) {?>
                                                Yes
                                            <?php }else {?>
                                                No
                                            <?php }?>
                                        </th>
                              <?php } ?>
                                     </tr>
                          <?php }?>
                            
 
                         
                             <tr>
                                <th class="nonheader msep" >
                                    <input required="required" class="required" type="text" name="participant_name" id="participant_name"></input>
                                    <input type="hidden" name="action_name" id="action_name" value="participant_votes"/>
                                    <input type="hidden" name="poll" value="<?php echo $doodle->getPollId();?>" />
                                    <input type="hidden" name="options_counter" value="<?php echo count($options['dates']);?>" />
                                </th>
                                <?php $i=0;
                                    
                                  foreach ($options['dates'] as $option) { ?>
                                    <td class="msep" ><input class="msep" type="checkbox" name="option-<?php echo $i++?>"/></td>
                                    
                                <?php
                                }
                                ?>
                            </tr> 
                            
                            <tr>
                                <td class="msep" ></td>
                                <?php for($i=0;$i<count($options['dates']);$i++ ) {
                                    if($i == (count($options['dates'])-1)){ ?>
                                        <td class="msep" >
                                            <input class="dpc_submit_participant button button-primary" type="submit" value="Save" name="dpc_submit_participant" />
                                        </td>
                                <?php
                                    } else {?>
                                        <td class="msep" ></td>  
                                <?php 
                                    }
                                }
                                ?>
                            </tr> 
                          
                    </tbody>
                </table>
            </div> 
	 	 <p><b><a onclick="hidePool(); return false" href="#">Hide</a></b></p>	
       
        </form>
		</div>
    </div>