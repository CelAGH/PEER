<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class DPC_Doodle_Poll {

    protected $id;
    protected $title;
    protected $description;
    protected $username;
    protected $email;
    protected $options;
    protected $xml;
    protected $latestchange;
    protected $status;
    protected $doc;

    public function __construct($model) {
        $this->doc = new DOMDocument('1.0', 'UTF-8');
        //print_r($model->xml);
        if (isset($model)) {

            $this->id = $model->poll_id;
            $this->title = $model->poll_title;
            if (isset($model->poll_description) && strlen($model->poll_description) > 0)
                $this->description = $model->poll_description;
            if (isset($model->poll_username) && strlen($model->poll_username) > 0)
                $this->username = $model->poll_username;

            $this->email = $model->poll_username;
            if (isset($model->poll_options) && strlen($model->poll_options) > 0)
                $this->options = $model->poll_options;

            if (isset($model->poll_xml) && strlen($model->poll_xml) > 0)
                $this->xml = $model->poll_xml;
            $this->latestchange = $model->poll_latestchange;
            $this->status = $model->poll_status;
        } else {
            $this->id = null;
        }
    }

    public function getPurePollHTML() {
        $html = "";

        return $html;
    }

    //with voting possibility
    public function getPollHTML() {
        $html = "";

        return $html;
    }

    public function getXml() {
        return $this->xml;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getOptionsArray() {

        $options = $this->doc->getElementsByTagName("option");

        $opts = array();
        foreach ($options as $option) {
            if ($option->parentNode->parentNode->tagName == "poll")
                $opts[] = $option->nodeValue;
        }

        return $opts;
    }
    
    
    public function addParticipantVote($opts) {        
        $this->doc->loadXML($this->xml);
        $participants = $this->doc->getElementsByTagName("participants");
        $ps = $participants->item(0)->getAttribute("nrOf");
        $participants->item(0)->setAttribute("nrOf",$ps+1);
        
        //print_r($ps);
        $participant = $this->doc->createElement("participant");
        $username = $this->doc->createElement("name");
        $username->appendChild($this->doc->createTextNode($opts["participant_name"]));
        
        $options = $opts['options'];
        $xmlopts = $this->doc->createElement("preferences");
        
        foreach ($options as $option) {
           
            if($option == 1) {
                $opt = $this->doc->createElement("option", "1"); 
                $xmlopts->appendChild($opt);    
                //unset($opt);
                //print_r($opt);print_r("<br>");
                //$opt->appendChild($this->doc->createTextNode("1"));
            }
            else { 
                $opt = $this->doc->createElement("option","0");
                $xmlopts->appendChild($opt);
                //unset($opt);
               // print_r($opt);print_r("<br>");
               // $opt->appendChild($this->doc->createTextNode("0"));
            }
            
            //print_r($xmlopts);print_r("<br>");
           
        }  
        // print_r($opts);print_r("<br>");
        $participant->appendChild($username);
        $participant->appendChild($xmlopts);
        
        //print_r($participant);
        $participants->item(0)->appendChild($participant);
        $this->xml = $this->doc->saveXML();
        //print_r($this->doc->saveXML());
//        
//        $username = $opts['participant_name'];
     
//        $participants = $this->doc->getElementsByTagName("participants");
//        $participants_els = $this->doc->getElementsByTagName("participant");
//        
//        foreach($participants_els as $part) {
//            print_r($part);
//            print_r("<br>");
//        }
        
        //print_r(count($participants_els));
        
        
//        $participant = $this->doc->createElement("participant");
//        $options = $this->doc->createElement("options");
        
        
        
        
    }

    public function getParticipantsVotes() {
        
        $this->doc->loadXML($this->xml);
        //print_r($this->xml);
        //print_r($this->getPollId());
        $participants = $this->doc->getElementsByTagName("participants");
        $attributes = $participants->item(0)->attributes;
        
        
        //number of particiapants
        $nrofparticipants = '';
        foreach ($attributes as $attribute) {
            if (strtolower($attribute->name) == "nrof")
                $nrofparticipants = $attribute->value;
        }

        //print_r($nrofparticipants);
        $participants = $this->doc->getElementsByTagName("participant");
        $p_votes = array();
        
        foreach ($participants as $participant) {
            $votes = array();
            foreach ($participant->childNodes as $node) {
                if ($node->nodeName == 'name') {
                    $votes['name'] = $node->nodeValue;
                }
                if ($node->nodeName == 'preferences') {
                    foreach ($node->childNodes as $option) {
                        $votes['option'][] = $option->nodeValue;
                    }
                }
            }
            $p_votes[] = $votes;
        }


        return $p_votes;
    }

    public function build_xml() {
        //print_r($domtree);
        /* create the root element of the xml tree */
        $xmlRoot = $this->doc->createElement("poll");
        $xmlRoot->appendChild($this->doc->createAttribute("xmlns"))->appendChild($this->doc->createTextNode("http://doodle.com/xsd1"));
        $xmlRoot->appendChild($this->doc->createElement("latestchange", $this->latestchange));
        $xmlRoot->appendChild($this->doc->createElement("type", "DATE"));
        $xmlRoot->appendChild($this->doc->createElement("state", "OPEN"));
        $xmlRoot->appendChild($this->doc->createElement("title", $this->title));
        $xmlRoot->appendChild($this->doc->createElement("description", $this->description));
        //initiator    
        $initiator = $this->doc->createElement("initiator");
        $initiator->appendChild($this->doc->createElement("name", $this->username));
        $xmlRoot->appendChild($initiator);

        //options
        $opts = $this->doc->createElement("options");

        $db_opts = (array) json_decode($this->options, true);
       
        foreach ($db_opts as $db_opt) {
            //$date = date("Y-m-d",$db_opt['date']);
            if (is_array($db_opt) && array_key_exists("times", $db_opt)) {
                foreach ($db_opt['times'] as $time) {
                    if (isset($time) && (is_null($time) === FALSE) && strlen($time) > 0) {
                        $option = $this->doc->createElement("option");
                        $date = date("c", strtotime(date("Y-m-d", strtotime($db_opt["date"][0])) . "T" . substr($time, 0, 5)));
                        $option->appendChild($this->doc->createAttribute("dateTime"))->appendChild($this->doc->createTextNode($date));
                        $opts->appendChild($option);
                    } 
                }
            } else if(is_array($db_opt)){
                $option = $this->doc->createElement("option");
                
                $date = date("c", strtotime($db_opt["date"][0]));
                $option->appendChild($this->doc->createAttribute("date"))->appendChild($this->doc->createTextNode($date));
                $opts->appendChild($option);
            } else {
                $option = $this->doc->createElement("option");
                $date = date("c", strtotime(date("Y-m-d", $db_opt)));                  
                $option->appendChild($this->doc->createAttribute("date"))->appendChild($this->doc->createTextNode($date));
                $opts->appendChild($option);
            }
            
        }
        $xmlRoot->appendChild($opts);
        $participants = $this->doc->createElement("participants");
        $participants->appendChild($this->doc->createAttribute("nrOf"))->appendChild($this->doc->createTextNode("0"));
        $xmlRoot->appendChild($participants);
        $this->doc->appendChild($xmlRoot);
        $this->xml = ($this->doc->saveXML());
        return $this->xml;
    }

    public function getNumberOfParticipants() {
        $this->doc->loadXML($this->xml);
        $participants = $this->doc->getElementsByTagName("participants");
        $attributes = $participants->item(0)->attributes;

        //number of particiapants
        $nrofparticipants = '';
        foreach ($attributes as $attribute) {
            if ($attribute->name == "nrOf")
                $nrofparticipants = $attribute->value;
        }

        return $nrofparticipants;
    }

    public function getLatestChange() {
        return $this->latestchange;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPollId() {
        return $this->id;
    }

    public function getOptionsHeaders() {
        $this->doc->loadXML($this->xml);
        $results = array();
        $options = $this->doc->getElementsByTagName("option");

        $dates = array();
        $dates_table = array();
        $j = 0;
        foreach ($options as $option) {
            if ($option->parentNode->parentNode->tagName == "poll") {              
                $attributes = $option->attributes;
                

                foreach ($attributes as $attribute) {
                    $time = strtotime($attribute->value);
                    $year = date("Y", $time);
                    $month = date("m", $time);
                    $day = date("d", $time);
                    $h = date("H", $time);
                    $i = date("i", $time);
                    if (array_search($attribute->value, $dates) === FALSE) {
                        $dates[$j][$attribute->name] = $attribute->value;
                        if (strtolower($attribute->name) == "startdatetime") {
                            $dates_table[$year][$month][$day]['counter'] +=1;
                            $dates_table[$year][$month][$day]['startdatetime'] = $attribute->value;
                        } else if (strtolower($attribute->name) == "enddatetime") {
                            $dates_table[$year][$month][$day]['enddatetime'] = $attribute->value;
                        } else if (strtolower($attribute->name) == "date") {
                            $dates_table[$year][$month][$day]['counter'] +=1;
                            $dates_table[$year][$month][$day]['date'][] = $attribute->value;
                        } else if (strtolower($attribute->name) == "datetime") {
                            $dates_table[$year][$month][$day]['counter'] +=1;
                            $dates_table[$year][$month][$day]['datetime'][] = $attribute->value;
                        }
                    }
                }
                $j++;
            }
        }
 
        //sortowanie dat - zakładamy że są w odpowiedniej kolejnpości w xmlu
        //$sorted_dates = usort($dates,array($this,'date_compare'));
//        foreach($dates as $date) {
//                $time = strtotime($date);    
//                $year = date("Y", $time);
//                $month = date("m", $time);
//                $day = date("d", $time);
//                $h = date("H", $time);
//                $i = date("i", $time);
//                
//                $dates_table[$year][$month][$day][$h] = 
//        }
        //print_r($dates_table);

        $results['structure'] = $dates_table;
        $results['dates'] = $dates;

        return $results;
    }

    public function getDescription() {
        return $this->description;
    }
}

?>
