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

		add_action('bbp_theme_before_topic_form_status',array($this,'add_custom_topic_status_dropdown'));
		add_action('bbp_new_topic',array($this,'save_custom_topic_status'),2);
		add_action('bbp_edit_topic',array($this,'save_custom_topic_status'),2);

		add_action('bbp_theme_before_topic_title',array($this,'show_custom_topic_status'));

		add_action('wp_enqueue_scripts',array($this,'custom_Status_styling'));
	}


	function get_topic_statuses($forum_id=null){
		$fetch_value = get_post_meta($forum_id,'bbp_cts',true);
		if(isset($fetch_value)){
			return apply_filters('bbp_cts_topic_statuses',array(
					'support' 		=> __('Support','bbpcts'),
					'resolved' 		=> __('Resolved','bbpcts'),
					'bug' 			=> __('Bug','bbpcts'),
					'in-progress'	=>__('In Progress','bbpcts'),
					'feature'		=>__('Feature','bbpcts'),
					'idea'			=>__('Idea','bbpcts'),
					'delivered'		=>__('Delivered','bbpcts'),
					'update'		=>__('Updated','bbpcts'),
				),$forum_id);
		}
		return apply_filters('bbp_cts_topic_statuses',array(
					'support' 		=> __('Support','bbpcts'),
					'resolved' 		=> __('Resolved','bbpcts'),
					'bug' 			=> __('Bug','bbpcts'),
					'in-progress'	=>__('In Progress','bbpcts'),
					'feature'		=>__('Feature','bbpcts'),
					'idea'			=>__('Idea','bbpcts'),
					'delivered'		=>__('Delivered','bbpcts'),
					'update'		=>__('Updated','bbpcts'),
				),0);
	}

	function add_topic_statuses($forum_id){
		$fetch_value = get_post_meta($forum_id,'bbp_cts',true);
		?>
		<p>
			<input type="checkbox" name="bbp_cts" value="1" id="bbp_cts" <?php checked($fetch_value,1); ?>><label for="bbp_cts"><?php esc_html_e( '
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

	function add_custom_topic_status_dropdown(){
		
		if(!current_user_can('read_hidden_forums'))
			return;

		$topic_id = bbp_get_topic_id();
		$topic_status='';
		if(isset($topic_id)){
			$topic_status = get_post_meta($topic_id,'bbp_cts_status',true);
		}

		?>
		<p>
			<label for="bbp_topic_status"><?php _e( ' Custom Topic Status:', 'bbpcts' ); ?></label><br />
			<?php 
				$statuses = $this->get_topic_statuses();
				if(isset($statuses)){
					?>

					<select name="bbp_cts_status" id="bbp_custom_topic_status">
						<option value="">None</option>
						<?php 
							foreach ( $statuses as $key => $label ) { 
								echo '<option value="'.$key.'" '.selected($key,$topic_status).'>'.esc_html( $label ).'</option>';
							}
						?>
					</select>
					<?php
				}
			?>
		</p>
		<?php
	}

	function save_custom_topic_status($topic_id, $forum_id){
		if(isset($_POST['bbp_cts_status'])){
			update_post_meta($topic_id,'bbp_cts_status',$_POST['bbp_cts_status']);
		}
	}

	function show_custom_topic_status(){

		$topic_id = bbp_get_topic_id(0);
		$forum_id = bbp_get_forum_id();
		$enabled = get_post_meta($forum_id,'bbp_cts',true);
		if(isset($enabled)){
			$status = get_post_meta($topic_id,'bbp_cts_status',true);
			$statuses = $this->get_topic_statuses($forum_id);
			if($status){
				echo '<span class="custom_topic_status '.$status.'">'.$statuses[$status].'</span>';
			}
		}
	}


	function custom_Status_styling(){

		if(!is_singular(array('forum','topic')))
			return;

		?>
		<style>
			.custom_topic_status {
			    font-size:10px;letter-spacing:1px;
			    text-transform:uppercase;
			    font-weight:600;
			    padding:1px 5px;
			    border-radius:2px;
			    background:#ddd;
			    position:absolute;
			}
			.custom_topic_status.support{
				background:#2E60FF;
				color:#fff;
			}
			.custom_topic_status.bug{
			    background:#C20A27;
			    color:#fff;
			}
			.custom_topic_status.in-progress{
				background:#009dd8;
				color:#fff;
			}
			.custom_topic_status.feature{
				background:#FFD90D;
				color:#fff;
			}
			.custom_topic_status.resolved{
				background:#007F22;
				color:#fff;
			}
			.custom_topic_status.idea{
				background:#9833E8;
				color:#fff;
			}
			.custom_topic_status.delivered{
				background:#14A820;
				color:#fff;
			}
			.custom_topic_status.update{
				background:#FF8A2A;
				color:#fff;
			}
		</style>
		<?php		
	}
}
BBP_Custom_Topic_Status::init();