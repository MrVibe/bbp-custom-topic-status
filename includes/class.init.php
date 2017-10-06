<?php

if ( !defined( 'ABSPATH' ) ) exit;

/*
 Initialise class for Custom Topic status
 */
class BBP_Custom_Topic_Status{

	public static $instance;

	public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new BBP_Custom_Topic_Status();
        return self::$instance;
    }

	public function __construct(){
		add_action('bbp_forum_metabox',array($this,'add_topic_statuses'),10,1);
		add_action('bbp_forum_attributes_metabox_save',array($this,'save_topic_statuses'),10,1);
	}

	function add_topic_statuses($forum_id){

		?>
		<p>
			<input type="checkbox" name="bbp_cts" value="1" id="bbp_cts"><label class="screen-reader-text" for="bbp_cts"><?php esc_html_e( '
			Enable Custom Topic statuses', 'bbpcts' ) ?></label>
		<?php 
	}

	function save_topic_statuses($forum_id){

		if(isset($_POST['bbp_cts'])){
			update_post_meta($forum_id,'bbp_cts',1);
		}else{
			delete_post_meta($forum_id,'bbp_cts');
		}
	}
}

BBP_Custom_Topic_Status::init();
