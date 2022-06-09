<?php
/**
 * 
 * @package   CdFeedbackForm
 * 
 * functionalities and hooks of the plugin
 * 
 */
class CdFeedbackForm
{
    protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->version = defined( 'CdFeedback_FORM_VERSION' ) ?  CdFeedback_FORM_VERSION : '1.0.0';
		$this->plugin_name = 'cd-feedback-form';
		$this->define_hooks();
	}

	/**
	* Register all the hooks related to admin and public functionality of the plugin
	* 
	* @since    1.0.0
	* @access   private
	* 
	*/
	private function define_hooks() {
		
		// Scripts and Styles
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_public' ] );

		// Load Translations
		add_action('plugins_loaded', [$this, 'cdfeedbackform_load_textdomain']);
		
		// Use [cdfeedbackform] for showing form
		add_shortcode('cdfeedbackform', [$this, 'display_cdfeedbackform']);
		// Use [cdfeedbackformdata] for showing list of enteries by form
		add_shortcode('cdfeedbackformdata', [$this, 'display_form_data']);

		// Ajax Functions
		add_action('wp_ajax_nopriv_submit_display_cdfeedbackform', [$this, 'submit_display_cdfeedbackform_callback']);
		add_action('wp_ajax_submit_display_cdfeedbackform', [$this, 'submit_display_cdfeedbackform_callback']);


		add_action('wp_ajax_form_table_data', [$this, 'ajax_form_table_data']);
		add_action('wp_ajax_form_details_data', [$this, 'form_details_data_callback']);

	}

	/**
	 * Load plugin textdomain.
	 * 
	 * @since 1.0.0
	 * 
	 */
	function cdfeedbackform_load_textdomain(){
		$plugin_rel_path = basename( dirname( __DIR__ ) ) . '/languages'; /* Relative to WP_PLUGIN_DIR */
		load_plugin_textdomain( 'cdfeedback-form', false, $plugin_rel_path );
	}

	/**
	 * 
	 * Shortcode to display form.
	 * 
	 * @since 1.0.0
	 * 
	 */
	function display_cdfeedbackform(){
		$email = "";
		$fname = "";
		$lname = "";
		if ( is_user_logged_in() ) 
   		{
			$current_user = wp_get_current_user();
			$email = $current_user->user_email;
			$fname = $current_user->user_firstname;
			$lname =$current_user->user_lastname;
		}
		$output="
		<div class='cdfeedback-form-message'></div>
			<div class='cdfeedback-form-section'>
				<h3>".__('Submit your feedback','cdfeedback-form')."</h3>				
				<form name='cdfeedback-form' class='cdfeedback-form' method='POST'>
					<div class='half-width'>
						<label for='first-name'> ".__('First Name','cdfeedback-form')." </label>
						<input name='first-name' class='first-name required' type='text' value='$fname' required='required'/>
					</div>
					<div class='half-width'>
						<label for='first-name'> ".__('Last Name','cdfeedback-form')." </label>
						<input name='last-name' class='last-name required' type='text' value='$lname' required='required'/>
					</div>
					<div class='half-width'>
						<label for='email'> ".__('Email','cdfeedback-form')." </label>
						<input name='email'  class='email required' type='email' value='$email' required='required'/>
					</div>
					<div class='half-width'>
						<label for='subject'> ".__('Subject','cdfeedback-form')." </label>
						<input name='subject' class='subject required' type='text' value='' required='required'/>
					</div>
					<div class='full-width'>
						<label for='message'> ".__('Message','cdfeedback-form')." </label>
						<textarea name='message' class='message required' required='required'></textarea>
					</div>

					<div class='full-width'>	
						<input type='submit' class='btn-cdfeedback-form' value='Submit Form'/>
					</div>

				</form>
			</div>";
		return $output;

	}

	/**
	 * Ajax callback for saving form data
	 */
	function submit_display_cdfeedbackform_callback(){
		// Check for nonce security
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Busted!');
		}
		global $wpdb;
		$table_name    = $wpdb->prefix.'cd_feedback_form';

		//save data to new table
		$fname = sanitize_text_field($_POST["first-name"]);
		$lname = sanitize_text_field($_POST["last-name"]);
		$email = sanitize_email($_POST["email"]);
		$subject = sanitize_text_field($_POST["subject"]);
		$message = sanitize_textarea_field($_POST["message"]);
		$form_data = [];
		$form_data["first-name"] = $fname;
		$form_data["last-name"] = $lname;
		$form_data["email"] = $email;
		$form_data["subject"] = $subject;
		$form_data["message"] = $message;
		$form_post_id = '';
		$form_value = serialize($form_data);
		$form_date    = current_time('Y-m-d H:i:s');
		$wpdb->insert( $table_name, [
            'id' => $form_post_id,
            'form_data'   => $form_value,
            'form_date'    => $form_date
		]);		
		$insert_form_id = $wpdb->insert_id;
		wp_die();
	}

	/**
	 * Methods for form table layout
	 */
	function form_table_data(){
		$this->access_to_adminonly();
		global $wpdb;
		$table_name  = $wpdb->prefix.'cd_feedback_form';
		$limit = 10;
		$paged = isset($_POST['paged']) ? $_POST['paged'] : 1;
		$page = $paged-1;
		$start = $page*$limit;
		$totalitems   = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
		$totalpages = ceil($totalitems / $limit);
		$results = $wpdb->get_results( "SELECT * FROM $table_name LIMIT $start,$limit", OBJECT );
		$data = [];
		foreach ( $results as $result ) {
			$form_data = unserialize( $result->form_data );
			$form_data['id'] = $result->id;
			//print_r($form_data);
			$data[] = $form_data;
		}
		$output='';
		$output.="<table>
			<tr>
				<th>".__('First Name','cdfeedback-form')."</th>
				<th>".__('Last Name','cdfeedback-form')."</th>
				<th>".__('Email','cdfeedback-form')."</th>
				<th>".__('Subject','cdfeedback-form')."</th>
			</tr>
		";
		foreach($data as $t => $val){
				$form_id = $val['id'];
				$output.="<tr class='form-short-details' data-form-id='$form_id'>
				<td>".esc_html($val["first-name"])."</td>
				<td>".esc_html($val["last-name"])."</td>
				<td>".esc_html($val["email"])."</td>
				<td>".esc_html($val["subject"])."</td>
				</tr>";
		}
		$disable_prev  = false;
		$disable_next  = false;
		if($paged == 1){
			$disable_prev  = true;
		}

		if($paged == $totalpages){
			$disable_next  = true;
		}
		$prev_page = $paged-1;
		$next_page = $paged+1;
		$output.="
		<tr>
			<td colspan='5'> 
				".__('Total','cdfeedback-form')." : $totalitems items |  $paged of $totalpages ".__('Pages','cdfeedback-form')."   
				<span class='pagination-links'>";
					if($disable_prev==true){
						$output.="<span class='tablenav-pages-navspan button disabled' aria-hidden='true'>‹</span>";
					}else{
						$output.="
							<a class='prev-page button pagination' data-page-id='$prev_page'>
								<span class='screen-reader-text'>".__('Previous page','cdfeedback-form')."</span>
								<span aria-hidden='true'>‹</span>
							</a>
						";
					}
					if($disable_next==true){
						$output.="<span class='tablenav-pages-navspan button disabled' aria-hidden='true'>›</span>";
					}else{
						$output.="
							<a class='next-page button pagination' data-page-id='$next_page'>
								<span class='screen-reader-text'>".__('Next page','cdfeedback-form')."</span>
								<span aria-hidden='true'>›</span>
							</a>
						";
					}		
			$output.="
				</span>
			</td>
		</tr>
		</table>
		<div class='full-form-details' style='display: none'></div>
		";	
		return $output;
	}
	
	/**
	 * 
	 * Ajax data when pagination clicked
	 */
	function ajax_form_table_data(){
		$this->access_to_adminonly();
		echo $this->form_table_data();
		wp_die();
	}
	/**
	 * 
	 * Ajax method for form details when clicked on any form table
	 */
	function form_details_data_callback(){
		$this->access_to_adminonly();
		global $wpdb;
		$table_name  = $wpdb->prefix.'cd_feedback_form';
		$formid = absint($_POST["form-id"]);
		$results = $wpdb->get_results( "SELECT * FROM $table_name where id='$formid' LIMIT 1 ", OBJECT );
		if ( empty($results) ) {
			$message = __('Incorrect form id!','cdfeedback-form');
            wp_die( $message );
		}
		$form_details = unserialize($results[0]->form_data);
		$form_title = __('Form Full Details','cdfeedback-form');
		$output = "
		<div class='form-details-section'>
			<h4> $form_title </h4>
			<table>
				<tr><th width='150'>".__('Name','cdfeedback-form')."</th> <td>".esc_html($form_details['first-name'])." ".esc_html($form_details['last-name'])."</td></tr>
				<tr><th>".__('Email','cdfeedback-form')."</th> <td>".esc_html($form_details['email'])."</td> </tr>
				<tr><th>".__('Subject','cdfeedback-form')."</th> <td>".esc_html($form_details['subject'])."</td> </tr>
				<tr><th>".__('Message','cdfeedback-form')."</th> <td>".esc_html($form_details['message'])."</td> </tr>
			</table>
		</div>
		";
		echo $output;
		wp_die();
	}

	/**
	 * 
	 * Shortcode to display form data.
	 * 
	 * @since 1.0.0
	 * 
	 */
	function display_form_data(){
		$output="";
		$output.="<div class='cdfeedback-form-data-section'>";
		$error_msg = __('You are not authorized to view the content of this page.','cdfeedback-form');
		if(is_user_logged_in()){
			if(current_user_can('administrator')){
				
				$output.="<div class='cdfeedback-ajax-table'>";
				$output.=$this->form_table_data();
				$output.="</div>";
			}else{
				$output.=$error_msg;
			}
		}else{
			$output.=$error_msg;
		}
		$output.="</div>";
		return $output;
	}

	/**
	 * 
	 * Enqueue style and javascript files of the plugin of pulic.
	 *
	 * @since    1.0.0
	 * 
	 */
	public function enqueue_scripts_public(){
		$localizations = [
			'ajax_url' => admin_url( 'admin-ajax.php'),
			'success_msg'=>__('Thank you for sending us your feedback','cdfeedback-form'),
			'nonce' => wp_create_nonce('ajax-nonce')
		];
		wp_enqueue_script( 'form-validate', plugins_url( '../assets/js/jquery.validate.js', __FILE__ ), ['jquery'] );
		wp_enqueue_style( 'feedbackform-style', plugins_url( '../assets/css/style.css', __FILE__ ), [], $this->version, false );
		wp_enqueue_script( 'feedbackform-main', plugins_url( '../assets/js/main.js', __FILE__ ), ['jquery'], $this->version, false);
		wp_localize_script( 'feedbackform-main', 'localizedVars', $localizations );
	}


	/**
	 * 
	 * Block non admin users
	 *
	 * @since    1.0.0
	 * 
	 */
	public function access_to_adminonly(){
		if(is_user_logged_in()){
			if(!current_user_can('administrator')){
				wp_die();
			}
		}else{
			wp_die();
		}
	}

}