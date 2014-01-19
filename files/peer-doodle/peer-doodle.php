<?php
/*
  Plugin Name: Peer Doodle
  Description: Build polls inside WordPress
  Author: Jacek Bubak
  Author URI: http://mystats.pl
  Version: 0.0.1
 */

/*
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; version 2 of the License.
 */


$peer_doodle = new Peer_Doodle();

// Doodle_Poll_Creator class
class Peer_Doodle {

    protected $doodle_poll_creator_db_version = '0.0.1';
    protected $polls_table_name = "";

    public function __construct() {

        global $wpdb;
        $this->last_inserted_poll = 0;
        $this->polls_table_name = $wpdb->prefix . 'peer_doodle';
        // Make sure we are in the admin before proceeding.
        add_shortcode('peer_doodle', array(&$this, 'peer_doodle'));
        add_action('init', array(&$this, 'confirmation'), 12);
        add_action('wp_enqueue_scripts', array(&$this, 'css'));
        add_action('wp_enqueue_scripts', array(&$this, 'scripts'));
    }

    /**
     * Install database tables
     * 
     * @since 0.0.1 
     */
    static function install() {
        // Create post object
        $current_user = wp_get_current_user();
        $post_content = "";
        ob_start();
        include( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/admin-new-poll.php' );
        $post_content = ob_get_contents();
        ob_end_clean();

        $peer_post = array(
            'post_title' => 'Maak een poll',
            'post_content' => $post_content,
            'post_status' => 'publish',
            'post_author' => $current_user->id,
            'post_type' => 'page'
        );

// Insert the post into the database
        wp_insert_post($peer_post);
        $peer_post = array(
            'post_title' => 'Doe mee',
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => $current_user->id,
            'post_type' => 'page'


            );

// Insert the post into the database
        $id = wp_insert_post($peer_post);
        register_setting('peer_doodle', 'page_id', 'intval');
        if (!get_option('peer_doodle_page_id')) {
            add_option('peer_doodle_page_id', $id);
        } else {
            update_option('peer_doodle_page_id', $id);
        }


        global $wpdb;

        $polls_table_name = $wpdb->prefix . 'peer_doodle';

        // Explicitly set the character set and collation when creating the tables
        $charset = ( defined('DB_CHARSET' && '' !== DB_CHARSET) ) ? DB_CHARSET : 'utf8';
        $collate = ( defined('DB_COLLATE' && '' !== DB_COLLATE) ) ? DB_COLLATE : 'utf8_general_ci';


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE $polls_table_name (
				poll_id BIGINT(20) NOT NULL AUTO_INCREMENT,
				poll_pollid BIGINT(20),
                                poll_title VARCHAR(255) NOT NULL,
                                poll_description TEXT,
                                poll_username VARCHAR(255) NOT NULL,
				poll_email VARCHAR(255) NOT NULL,
                                poll_options TEXT,
                                poll_xml TEXT,                                
				poll_latestchange VARCHAR(40) NOT NULL,
				poll_status SMALLINT(6) NOT NULL,				
				PRIMARY KEY  (poll_id)                             
			) DEFAULT CHARACTER SET $charset COLLATE $collate;";


        dbDelta($sql);
    }

    /**
     * Add form CSS to wp_head
     * 
     * @since 1.0 
     */
    public function css() {
        wp_enqueue_style('doodle-poll-creator-style', plugins_url("peer-doodle/css/pepper-ginder-custom.css"));
        wp_enqueue_style('peer-doodle', plugins_url("peer-doodle/css/peer-doodle.css"));
    }

    /**
     * Queue form validation scripts
     * 
     * @since 0.0.1 
     */
    public function scripts() {
        // Make sure scripts are only added once via shortcode
        $this->add_scripts = true;
        wp_enqueue_script('postbox');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui.multidatespicker.js', plugins_url("peer-doodle/js/jquery-ui.multidatespicker.js"));
        wp_enqueue_script('jquery-form-validation', 'https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js', array('jquery'), '', true);
        wp_enqueue_script('peer-doodle', plugins_url("peer-doodle/js/peer-doodle.js"), array('jquery'), '', true);
    }

    /**
     * Handle confirmation when vote is submitted
     * 
     * @since 1.3
     */
    function confirmation() {
        global $wpdb;
        global $current_user;
        require_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/class-poll-status-dictionary.php' );
        require_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/class-dpc-poll.php' );


        if (isset($_REQUEST['action_name'])) {

            switch ($_REQUEST['action_name']) {
                case 'create_poll':
                    $poll_location = "";
                    $poll_description = "";
                    $poll_title = $_REQUEST['pd_title'];
                    if (isset($_REQUEST['pd_location']))
                        $poll_location = $_REQUEST['pd_location'];
                    if (isset($_REQUEST['pd_description']))
                        $poll_description = $_REQUEST['pd_description'];


                    $poll_username = $current_user->user_login;
                    $poll_email = $current_user->user_email;
                    $poll_latestchange = time();
                    $poll_options = array();
                    $options = array();
                    $opt = $_REQUEST['dates_timestamp'];

                    foreach ($opt as $date) {
                        $options[] = substr($date, 0, strlen($date) - 3) + 3600;
                    }

                    $poll_options = json_encode($options);
                    $newdata = array(
                        'poll_title' => $poll_title,
                        'poll_description' => $poll_description,
                        'poll_username' => $poll_username,
                        'poll_email' => $poll_email,
                        'poll_latestchange' => date("c", $poll_latestchange),
                        'poll_status' => Doodle_Poll_Status::FINISHED,
                        'poll_options' => $poll_options
                    );
                    $wpdb->insert($this->polls_table_name, $newdata);
                    $poll_id = $wpdb->insert_id;

                    if ($poll_id > 0) {
                        $page_id = get_option('peer_doodle_page_id');
                        $page = get_post($page_id);

                        $query = "SELECT * FROM " . $this->polls_table_name . " WHERE poll_id=" . $poll_id;
                        $poll_model = $wpdb->get_results($query);

                        $poll = new DPC_Doodle_Poll($poll_model[0]);
                        $poll->build_xml();
                        $query = $wpdb->prepare("UPDATE " . $this->polls_table_name
                                . " SET poll_xml = '" . $poll->getXml() .
                                "' WHERE poll_id = %d", $poll_id
                        );
                        $res = $wpdb->query($query);

                        //print_r($poll);
                        $content = "[peer_doodle poll_id=" . $poll_model[0]->poll_id . "]";
                        $cont = $content . "<p></p>" . $page->post_content;
                        $page->post_content = $cont;
                        wp_update_post($page);
                        wp_redirect('?page_id=' . $page_id);
                        exit();
                    } else {
                        print_r("Unhandled Error");
                    }
                    //exit();
                    break;
                case 'participant_votes':
                    $options = array();
                    for ($i = 0; $i < $_REQUEST['options_counter']; $i++) {
                        $key = "option-" . $i;
                        if (array_key_exists($key, $_REQUEST)) {
                            $options[$i] = 1;
                        } else {
                            $options[$i] = 0;
                        }
                    }



                    if (strlen($_REQUEST['participant_name']) > 0) {
                        //TODO sql injection
                        $poll_id = $_REQUEST['poll'];
                        $page_id = get_option('peer_doodle_page_id');

                        $query = "SELECT * FROM " . $wpdb->prefix . "posts WHERE ID=" . $page_id;
                        $post = $wpdb->get_row($query);

                        $query = "SELECT * FROM " . $this->polls_table_name . " WHERE poll_id=" . $_REQUEST['poll'];
                        $poll_model = $wpdb->get_results($query);

                        $poll = new DPC_Doodle_Poll($poll_model[0]);
                        $poll->addParticipantVote(array(
                            'participant_name' => $_REQUEST['participant_name'],
                            'options' => $options,
                        ));


                        $query = $wpdb->prepare("UPDATE " . $this->polls_table_name
                                . " SET poll_xml = '" . $poll->getXml() .
                                "' WHERE poll_id = %d", $poll_id
                        );
                        $res = $wpdb->query($query);
                        //do_shortcode($post->post_content);
                        wp_update_post(array('ID' => $page_id));                        
                        wp_redirect('?page_id='.$page_id );
                        exit();
                    }

                    break;
            }
        }


//
//        if (strlen($_REQUEST['participant_name']) > 0) {
//            //TODO sql injection
//            $poll_id = $_REQUEST['poll'];
//            $post_id = $_REQUEST['post_id'];
//
//            $query = "SELECT * FROM " . $wpdb->prefix . "posts WHERE ID=" . $post_id;
//            $post = $wpdb->get_row($query);
//
//            $query = "SELECT * FROM " . $this->polls_table_name . " WHERE poll_id=" . $_REQUEST['poll'];
//            $poll_model = $wpdb->get_results($query);
//            $poll = new DPC_Doodle_Poll($poll_model[0]);
//            $poll->addParticipantVote(array(
//                'participant_name' => $_REQUEST['participant_name'],
//                'options' => $options,
//            ));
//
//            $query = $wpdb->prepare("UPDATE " . $this->polls_table_name
//                    . " SET poll_xml = '" . $poll->getXml() .
//                    "' WHERE poll_id = %d", $poll_id
//            );
//            $res = $wpdb->query($query);
//            //do_shortcode($post->post_content);
//
//            wp_update_post(array('ID' => $poll_id));
//        }
//		$form_id = ( isset( $_REQUEST['form_id'] ) ) ? (int) esc_html( $_REQUEST['form_id'] ) : '';
//		
//		if ( isset( $_REQUEST['visual-form-builder-submit'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'visual-form-builder-nonce' ) ) {
//			// Get forms
//			$order = sanitize_sql_orderby( 'form_id DESC' );
//			$forms 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->form_table_name WHERE form_id = %d ORDER BY $order", $form_id ) );
//			
//			foreach ( $forms as $form ) {
//				// If text, return output and format the HTML for display
//				if ( 'text' == $form->form_success_type )
//					return stripslashes( html_entity_decode( wp_kses_stripslashes( $form->form_success_message ) ) );
//				// If page, redirect to the permalink
//				elseif ( 'page' == $form->form_success_type ) {
//					$page = get_permalink( $form->form_success_message );
//					wp_redirect( $page );
//					exit();
//				}
//				// If redirect, redirect to the URL
//				elseif ( 'redirect' == $form->form_success_type ) {
//					wp_redirect( $form->form_success_message );
//					exit();
//				}
//			}
//		}
    }

    /**
     * Adds extra include files
     * 
     * @since 0.0.1
     */
    public function includes() {
        global $entries_list, $entries_detail;

        // Load the Entries List class
        require_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/class-entries-list.php' );
        $entries_list = new VisualFormBuilder_Entries_List();

        // Load the Entries Details class
        require_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/class-entries-detail.php' );
        $entries_detail = new VisualFormBuilder_Entries_Detail();
    }

    /**
     * Include the Import/Export files later because current_screen isn't available yet
     * 
     * @since 0.0.1
     */
    public function include_export() {
        global $export;

        // Load the Export class
        require_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/class-export.php' );
        $export = new VisualFormBuilder_Export();
    }

    /**
     * Add Settings link to Plugins page
     * 
     * @since 0.0.1 
     * @return $links array Links to add to plugin name
     */
    public function plugin_action_links($links, $file) {
        if ($file === plugin_basename(__FILE__)) {
            $links[] = '<a href="' . esc_url(admin_url('admin.php') . '?' . http_build_query(array('page' => 'doodle-poll-creator'))) . '">' . 'Settings' . '</a>';
        }

        return $links;
    }

    /**
     * Adds the media button image
     * 
     * @since 0.0.1
     */
    public function add_media_button($context) {
        //if (current_user_can('manage_options'))
        //    echo  '<a href="' . add_query_arg(array('action' => 'doodle_poll_creator_media_button', 'width' => '450', 'height' => '300'), admin_url('admin-ajax.php')) . '" class="thickbox" title="Insert Poll">Insert Poll</a>';

        if (current_user_can('manage_options'))
            $context .= '<a href="' . add_query_arg(array('action' => 'doodle_poll_creator_media_button', 'width' => '450', 'height' => '200'), admin_url('admin-ajax.php')) . '" class="thickbox" title="Insert Poll"><img width="20" height="20" src="' . plugins_url('doodle-poll-creator/images/check.png') . '" alt="Insert Doodle Poll" /></a>';

        return $context;
    }

    /**
     * Display the additional media button
     * 
     * Used for inserting the form shortcode with desired form ID
     *
     * @since 0.0.1
     */
    public function display_media_button() {
        global $wpdb;

        // Build our forms as an object
        $polls = $wpdb->get_results("SELECT * FROM $this->polls_table_name WHERE poll_status=1 OR poll_status=2 OR poll_status =3");
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $( '#add_dpc_form' ).submit(function(e){
                    e.preventDefault();
                                                                                                	                
                    window.send_to_editor( '[dpccode poll_id=' + $( '#dpc_polls' ).val() + ']' );
                                                                                                	                
                    window.tb_remove();
                });
            });
        </script>
        <div id="doodle_poll_creator_media_button">
            <form id="add_dpc_form" class="media-upload-form type-form validate">
                <h3 class="media-title">Insert Doodle Poll</h3>
                <?php
                if (count($polls) == 0):
                    echo "There is no Doodle Polls in database";
                    ?>  

                <?php else:
                    ?>
                    <select id="dpc_polls" name="dpc_polls">  
                        <?php
                        foreach ($polls as $poll) :
                            ?>
                            <p>Select a poll below to insert into any Post or Page.</p>
                            <option value="<?php echo $poll->poll_id; ?>"><?php echo $poll->poll_title; ?></option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                    <p><input type="submit" class="button-primary" value="Insert Poll" /></p>
                <?php endif;
                ?>


            </form>
        </div>
        <?php
        die(1);
    }

    /**
     * Queue plugin scripts for sorting form fields
     * 
     * @since 0.0.1
     */
    public function admin_scripts() {
//        wp_enqueue_script('jquery-ui-sortable');
//        wp_enqueue_script('prettify.js', plugins_url("doodle-poll-creator/js/prettify.js"));
//        wp_enqueue_script('jquery-ui-core');
//        wp_enqueue_script('jquery-ui-datepicker');
//        wp_enqueue_script('jquery-ui.multidatespicker.js', plugins_url("doodle-poll-creator/js/jquery-ui.multidatespicker.js"));
//        wp_enqueue_script('postbox');
//        wp_enqueue_script('jquery-form-validation', 'https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js', array('jquery'), '', true);
//        wp_enqueue_script('form-elements-add', plugins_url("doodle-poll-creator/js/doodle-poll-creator.js"), array('jquery', 'jquery-form-validation'), '', true);
//        wp_enqueue_script('doodle-poll-creator', plugins_url("doodle-poll-creator/js/doodle-poll-creator.js"), array('jquery', 'jquery-form-validation'), '', true);
//        wp_enqueue_script('nested-sortable', plugins_url('doodle-poll-creator/js/jquery.ui.nestedSortable.js'), array('jquery', 'jquery-ui-sortable'), '', true);
//        wp_enqueue_script('timeentry', plugins_url("doodle-poll-creator/js/jquery.timeentry.min.js"));
//
//
//        wp_enqueue_style('doodle-poll-creator-style', plugins_url("doodle-poll-creator/css/doodle-poll-creator-admin.css"));
//        //wp_enqueue_style( 'doodle-poll-creator-style-ginger', plugins_url( "doodle-poll-creator/css/pepper-ginger-custom.css" ) );
//        wp_enqueue_style('doodle-poll-creator-style-mdp', plugins_url("doodle-poll-creator/css/mdp.css"));
//        wp_enqueue_style('doodle-poll-creator-style-prettify', plugins_url("doodle-poll-creator/css/prettify.css"));
//        wp_enqueue_style('doodle-poll-creator-style-timeentry', plugins_url("doodle-poll-creator/css/jquery.timeentry.css"));
//        wp_enqueue_style('doodle-poll-creator-polls-style', plugins_url("doodle-poll-creator/css/polls.css"));
    }

    /**
     * Actions to save, update, and delete forms/form fields
     * 
     * 
     * @since 0.0.1
     */
    public function save() {
        global $wpdb;
        require_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/class-poll-status-dictionary.php' );
        require_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/class-dpc-poll.php' );




        if (isset($_REQUEST['page']) && in_array($_REQUEST['page'], array('doodle-poll-creator', 'dpc-add-new')) && isset($_POST['action'])) {

            switch ($_POST['action']) {
                case 'create_poll' :
                    if (isset($_REQUEST['poll']) && $_REQUEST['poll'] > 0) {
                        wp_redirect('admin.php?page=dpc-add-new');
                    } else {
                        $poll_title = ($_REQUEST['poll_title']);
                        $poll_description = ( $_REQUEST['poll-description'] );
                        //$poll_location = ( $_REQUEST['poll-location'] );
                        $poll_username = ( $_REQUEST['poll-username'] );
                        $poll_email = ( $_REQUEST['poll-user-email'] );
                        $poll_latestchange = time();

                        check_admin_referer('create_poll');

                        $newdata = array(
                            'poll_title' => $poll_title,
                            'poll_description' => $poll_description,
                            'poll_username' => $poll_username,

                            'poll_email' => $poll_email,
                            'poll_latestchange' => date("c", $poll_latestchange),
                            'poll_status' => Doodle_Poll_Status::NOTFINISHED
                        );

//                        $query = "INSERT INTO ".$this->polls_table_name."(poll_title,poll_description,
//                            poll_username,
//                            poll_email,
//                            poll_latestchange,
//                            poll_type,
//                            poll_status) VALUES(
//                                '".$poll_title."',
//                                '".$poll_description."',
//                                '".$poll_username."',
//                                '".$poll_email."',
//                                '".$poll_latestchange."',  
//                                '".$poll_type."',
//                                    0
//                            ) ";
//                        $wpdb->query($query);
                        $wpdb->insert($this->polls_table_name, $newdata);
//					// Insert the submit field 
//					$wpdb->insert( $this->field_table_name, $submit );
//					
//					// Redirect to keep the URL clean (use AJAX in the future?)
                        if ($wpdb->insert_id > 0)
                            wp_redirect('admin.php?page=dpc-add-new&action=' . $_POST['action'] . '&submit=' . strtolower($_REQUEST['submit']) . '&poll=' . $wpdb->insert_id);
                        else
                            print_r("Unhandled Error");
                        exit();
                    }
                    break;


                case 'edit_poll_dates' :
                    check_admin_referer('edit_poll_dates');
                    $poll_id = $_REQUEST['poll'];
                    $dates = $_POST['dates_timestamp'];
                    $tdates = array();

                    foreach ($dates as $date) {
                        $tdates[] = date('c', ($date / 1000 + 3600));
                    }
                    $json_dates = json_encode($tdates);
                    $query = "UPDATE " . $this->polls_table_name
                            . " SET poll_options = '" . $json_dates .
                            "' WHERE poll_id = " . $poll_id;
                    $res = $wpdb->query($query);

                    if ($res > 0)
                        wp_redirect('admin.php?page=dpc-add-new&action=' . $_POST['action'] . '&submit=' . strtolower($_POST['submit']) . '&poll=' . $_REQUEST['poll']);
                    else
                        print_r("Unhandled Error");
                    exit();
                    break;
                case 'edit_poll_times' :
                    //print_r($_REQUEST);
                    check_admin_referer('edit_poll_times');
                    $poll_id = $_REQUEST['poll'];
                    //update całej ankiety oraz jej wyświetlenie z przyciskiem finish
                    //wczytanie i update options
                    $dates = $_POST['dates'];
                    $new_dates = array();
                    $times = $_POST['times'];

                    //$new_dates[$i]['date'] = $dates[$i];
                    //    $new_dates[$i]['times'] = $times[$i];


                    for ($i = 0; $i < count($dates); $i++) {
                        for ($j = 0; $j < count($times[$i]); $j++) {
                            //print_r($times[$i]);
                            if (empty($times[$i][$j])) {
                                
                            } else {
                                $new_dates[$i]['times'][] = $times[$i][$j];
                            }
                        }
                        $new_dates[$i]['date'][] = $dates[$i];
                    }

                    $json_dates = json_encode($new_dates);
                    $query = "UPDATE " . $this->polls_table_name
                            . " SET poll_options = '" . $json_dates .
                            "' WHERE poll_id = " . $poll_id;
                    $res = $wpdb->query($query);

                    $query = "SELECT * FROM " . $this->polls_table_name . " WHERE poll_id=" . $_REQUEST['poll'];
                    $poll_model = $wpdb->get_results($query);
                    $poll = new DPC_Doodle_Poll($poll_model[0]);
                    $poll->build_xml();
                    $query = "UPDATE " . $this->polls_table_name
                            . " SET poll_xml = '" . $poll->getXml() .
                            "' WHERE poll_id = " . $poll_id;
                    $res = $wpdb->query($query);

                    if ($res > 0)
                        wp_redirect('admin.php?page=dpc-add-new&action=' . $_POST['action'] . '&submit=' . strtolower($_POST['submit']) . '&poll=' . $_REQUEST['poll']);
                    else
                        print_r("Unhandled Error");
                    exit();

                    break;
                case 'new_poll_finish':

                    //check_admin_referer('new_poll_finish');
                    $poll_id = $_REQUEST['poll'];
                    $query = "UPDATE " . $this->polls_table_name
                            . " SET poll_status = " . Doodle_Poll_Status::FINISHED .
                            " WHERE poll_id = " . $poll_id;
                    $res = $wpdb->query($query);

                    if ($res > 0)
                        wp_redirect('admin.php?page=dpc-poll-list');
                    else
                        print_r("Unhandled Error");
                    exit();
                    break;
            }
        }
    }

    /**
     * The jQuery field sorting callback
     * 
     * @since 0.0.1
     */
    public function process_sort_callback() {
        global $wpdb;

        $data = array();

        foreach ($_REQUEST['order'] as $k) {
            if ('root' !== $k['item_id']) {
                $data[] = array(
                    'field_id' => $k['item_id'],
                    'parent' => $k['parent_id']
                );
            }
        }

        foreach ($data as $k => $v) {
            // Update each field with it's new sequence and parent ID
            $wpdb->update($this->field_table_name, array('field_sequence' => $k, 'field_parent' => $v['parent']), array('field_id' => $v['field_id']));
        }

        die(1);
    }

    /**
     * All Polls output in admin
     * 
     * @since 0.0.1
     */
    public function all_polls() {
        global $wpdb;
        $order = sanitize_sql_orderby('poll_id ASC');
        $polls = $wpdb->get_results("SELECT * FROM $this->polls_table_name WHERE poll_status=2 OR poll_status=1 ORDER BY $order");

        $a = array();

        if ($polls) :
            // Loop through each for and build the tabs
            foreach ($polls as $poll) {
                
            }
            ?>
            <div class="vfb-form-alpha-list">
                <hr>
                <?php
                foreach ($a as $alpha => $value) :
                    ?>
                    <div class="vfb-form-alpha-group">
                        <h2 class='letter'><?php echo $alpha; ?></h2>
                        <?php
                        foreach ($value as $alphaForm) {
                            ?>

                            <div class="vfb-form-alpha-form">
                                <h3><a class="" href="<?php echo esc_url(add_query_arg(array('form' => $alphaForm['id']), admin_url('admin.php?page=visual-form-builder'))); ?>"><?php echo $alphaForm['title']; ?></a></h3>
                                <div class="vfb-publishing-actions">
                                    <p>
                                        <a class="" href="<?php echo esc_url(add_query_arg(array('form' => $alphaForm['id']), admin_url('admin.php?page=visual-form-builder'))); ?>">
                                            <strong><?php _e('Edit Form', 'visual-form-builder'); ?></strong>
                                        </a> |
                                        <a class="submitdelete menu-delete" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=visual-form-builder&amp;action=delete_form&amp;form=' . $alphaForm['id']), 'delete-form-' . $alphaForm['id'])); ?>" class=""><?php _e('Delete', 'visual-form-builder'); ?></a> |
                                        <a href="<?php echo esc_url(add_query_arg(array('form-filter' => $alphaForm['id']), admin_url('admin.php?page=vfb-entries'))); ?>"><?php echo $alphaForm['entries_count']; ?> Entries</a>

                                    </p>                            
                                </div> <!-- .vfb-publishing-actions -->
                            </div>
                            <div class="clear"></div>
                            <?php
                        }
                        ?>
                    </div> <!-- .vfb-form-alpha-group -->
                    <hr>
                <?php endforeach; ?>
            </div> <!-- .vfb-form-alpha-list -->

        <?php endif; ?>



        </div>
        <?php
    }

    /**
     * Add options page to Settings menu
     * 
     * 
     * @since 0.0.1
     * @uses add_options_page() Creates a menu item under the Settings menu.
     */
    public function add_admin() {
        add_menu_page(__('Doodle Poll Creator', 'doodle-poll-creator'), __('Doodle Poll Creator', 'doodle-poll-creator'), 'manage_options', 'doodle-poll-creator', array(&$this, 'admin'), plugins_url('doodle-poll-creator/images/vfb_icon.png'));
        add_submenu_page('doodle-poll-creator', __('All Polls', 'doodle-poll-creator'), __('All Polls', 'doodle-poll-creator'), 'manage_options', 'dpc-poll-list', array(&$this, 'admin'));
        add_submenu_page('doodle-poll-creator', __('Add New Poll', 'doodle-poll-creator'), __('Add New Poll', 'doodle-poll-creator'), 'manage_options', 'dpc-add-new', array(&$this, 'admin'));
    }

    /**
     * Builds the options settings page
     * 
     * @since 0.0.1
     */
    public function admin() {
        //TODO
        global $wpdb, $current_user; //, $entries_list, $entries_detail, $export;

        get_currentuserinfo();

        // Save current user ID
        $user_id = $current_user->ID;

        // Set variables depending on which tab is selected
        $form_nav_selected_id = ( isset($_REQUEST['form']) ) ? $_REQUEST['form'] : '0';
        ?>
        <div class="wrap">
            <?php screen_icon('options-general'); ?>
            <h2>
                <?php
                _e('Doodle Poll Creator', 'doodle-poll-creator');
                //echo ( isset( $_REQUEST['s'] ) && !empty( $_REQUEST['s'] ) && in_array( $_REQUEST['page'], array( 'dpc-entries' ) ) ) ? '<span class="subtitle">' . sprintf( __( 'Search results for "%s"' , 'visual-form-builder'), $_REQUEST['s'] ) : '';
                ?>
            </h2>            
            <ul class="sub-navigation">
                <?php
                $views = array();
                $pages = array(
                    'doodle-poll-creator' => array('page' => __('Settings', 'doodle-poll-creator')),
                    'dpc-poll-list' => array('page' => __('All polls', 'doodle-poll-creator')),
                    'dpc-add-new' => array('page' => __('Add New Poll', 'doodle-poll-creator'))
                );

                foreach ($pages as $page => $args) {
                    $class = ( isset($_REQUEST['page']) && in_array($_REQUEST['page'], array($page)) ) ? 'current' : '';

                    $views[$args['page']] = "\t<li><a class='$class' href='" . admin_url("admin.php?page=$page") . "'>{$args['page']}</a>";
                }
                echo implode(' |</li>', $views) . '</li>';
                ?>
            </ul>

            <?php
            // Display the list of polls
            if (isset($_REQUEST['page']) && in_array($_REQUEST['page'], array('dpc-poll-list'))) :

//				if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], array( 'view' ) ) ) :
//					$entries_detail->entries_detail();
//				else :
//					$entries_list->prepare_items();
                ?>
                <form id="entries-filter" method="post" action="">
                    <?php
                    include_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/admin-poll-list.php' );
                    ?>
                </form>
                <?php
//				endif;
            //add new poll
            elseif (isset($_REQUEST['page']) && in_array($_REQUEST['page'], array('dpc-add-new'))) :
                if (isset($_REQUEST['action'])) :

                    switch ($_REQUEST['action']) {

                        case 'create_poll':
                            if (isset($_REQUEST['submit'])) {
                                switch ($_REQUEST['submit']) {
                                    case 'back':
                                        break;
                                    case 'next':
                                        include_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/admin-new-poll-dates.php' );
                                        break;
                                }
                            }
                            break;
                        case 'edit_poll_dates':
                            include_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/admin-new-poll-times.php' );
                            break;
                        case 'edit_poll_times':
                            include_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/admin-new-poll-summary.php' );
                            break;
                    }

//                                if(isset($_REQUEST['step1'])) :
//                                    include_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/admin-new-poll-dates.php' );
//                                elseif(isset($_REQUEST['step2'])):
//                                    include_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/admin-new-poll-settings.php' );
//                                elseif(isset($_REQUEST['step3'])):
//                                    include_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/admin-new-poll-invite.php' );
//                                endif;

                else:
                    include_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/admin-new-poll.php' );
                endif;

            else:
                if (empty($form_nav_selected_id)) :
                    ?>
                    <div id="vfb-form-list">
                        <div id="vfb-sidebar">
                            <div id="new-form" class="vfb-box">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=dpc-add-new')); ?>">
                                    <img src="<?php echo plugins_url('doodle-poll-creator/images/plus-sign.png'); ?>" width="50" height="50" />
                                    <h3><?php _e('New Poll', 'doodle-poll-creator'); ?></h3>
                                </a>
                            </div> <!-- #new-form -->
                            <div class="clear"></div>
                        </div> <!-- #vfb-sidebar -->
                        <div id="vfb-main" class="vfb-order-type-list">
                            <?php
                            $this->all_polls();
                            ?>
                        </div> <!-- #vfb-main -->
                    </div> <!-- #vfb-form-list -->
                    <?php ?>

                    <?php
                elseif (!empty($form_nav_selected_id) && $form_nav_selected_id !== '0') :
                    include_once( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/admin-poll-creator.php' );
                endif;
                ?>   

            </div>
        <?php
        endif;
    }

    /**
     * Output form via shortcode
     * 
     * @since 0.0.1
     */
    public function peer_doodle($atts, $content = '') {
        require( trailingslashit(plugin_dir_path(__FILE__)) . 'includes/doodle-poll-html-post.php' );
        return $content;
    }

}

// On plugin activation, install the databases and add/update the DB version
register_activation_hook(__FILE__, array('Peer_Doodle', 'install'));
?>