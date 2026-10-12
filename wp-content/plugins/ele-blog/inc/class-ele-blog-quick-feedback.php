<?php

if (! defined('ABSPATH')) {
	exit;
}

if (! class_exists('Eleblog_Quck_Feedback')) {

	class Eleblog_Quck_Feedback
	{

		private $eleblog_version = '1.6';
		private $home_url = '';
		private $plugin_file = '';
		private $plugin_name = '';
		private $options = array();
		private $require_optin = true;
		private $include_goodbye_form = true;


		/**
		 * Class constructor
		 *
		 * @param $_home_url				The URL to the site we're sending data to
		 * @param $_plugin_file				The file path for this plugin
		 * @param $_options					Plugin options to track
		 * @param $_require_optin			Whether user opt-in is required (always required on WordPress.org)
		 * @param $_include_goodbye_form	Whether to include a form when the user deactivates
		 * @param $_marketing				Marketing method:
		 *									0: Don't collect email addresses
		 *									1: Request permission same time as tracking opt-in
		 *									2: Request permission after opt-in
		 */
		public function __construct(
			$_plugin_file,
			$_home_url,

			$_require_optin = true,
			$_include_goodbye_form = true
		) {

			$this->plugin_file = $_plugin_file;
			$this->home_url = 'sazzad.bevilard@gmail.com';
			$this->plugin_name = basename($this->plugin_file, '.php');

			$this->require_optin = $_require_optin;
			$this->include_goodbye_form = $_include_goodbye_form;


			// Deactivation hook
			register_deactivation_hook($this->plugin_file, array($this, 'deactivate_this_plugin'));

			// Get it going
			$this->init();
		}

		public function init()
		{
			global $pagenow;

			// Deactivation
			add_filter('plugin_action_links_' . plugin_basename($this->plugin_file), array($this, 'filter_action_links'));
			add_action('admin_footer-plugins.php', array($this, 'form_submit'));
			add_action('wp_ajax_eleblog_goodbye_form', array($this, 'goodbye_form_callback'));
			if ($pagenow == 'plugins.php') {
				add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
			}
		}


		public function admin_enqueue_scripts()
		{

			wp_enqueue_style('ele-blog-admin', ELE_BLOG_CSS . '/admin.css', array(), ELE_BLOG_VERSION, 'all');
		}


		// In theme's functions.php or plug-in code:

		function set_content_type()
		{
			return "text/html";
		}


		/**
		 * Send the data to the home site
		 *
		 * @since 1.0.0
		 */
		public function send_data($body)
		{
			$message = '';
			foreach ($body as $key => $value) {

				if ($key == 'active_plugins') {
					$message .= '<p> <b>' . $key . '</b>: ' . (implode(', ', $value)) . ' </p>';
				} elseif ($key == 'inactive_plugins') {
					$message .= '<p> <b>' . $key . '</b>: ' . (implode(', ', $value)) . ' </p>';
				} else {
					$message .= '<p> <b>' . $key . '</b>: ' . $value . ' </p>';
				}
			}

			$title   = esc_html('EleBlog Deactivation Notice');
			$headers = array('From: Eleblog - Elementor Blog Addons');

			add_filter('wp_mail_content_type', array($this, 'set_content_type'));
			$email = wp_mail($this->home_url, $title, $message, $headers);
			remove_filter('wp_mail_content_type', array($this, 'set_content_type'));

			return $email;
		}

		/**
		 * Here we collect most of the data
		 * 
		 * @since 1.0.0
		 */
		public function get_data()
		{

			// Use this to pass error messages back if necessary
			$body['message'] = '';

			// Use this array to send data back
			$body = array();



			/**
			 * Get our plugin data
			 * Currently we grab plugin name and version
			 * Or, return a message if the plugin data is not available
			 * @since 1.0.0
			 */
			$plugin = $this->plugin_data();
			if (empty($plugin)) {
				// We can't find the plugin data
				// Send a message back to our home site
				$body['message'] .= esc_html__('User has not submit anythings', 'ele-blog');
				$body['status'] = esc_html('Data not found'); // Never translated
			} else {
				if (isset($plugin['Name'])) {
					$body['plugin'] = sanitize_text_field($plugin['Name']);
				}
				if (isset($plugin['Version'])) {
					$body['version'] = sanitize_text_field($plugin['Version']);
				}
			}

			// Return the data
			return $body;
		}

		/**
		 * Return plugin data
		 * @since 1.0.0
		 */
		public function plugin_data()
		{
			// Being cautious here
			if (! function_exists('get_plugin_data')) {
				include ABSPATH . '/wp-admin/includes/plugin.php';
			}
			// Retrieve current plugin information
			$plugin = get_plugin_data($this->plugin_file);
			return $plugin;
		}

		/**
		 * Deactivating plugin
		 * @since 1.0.0
		 */
		public function deactivate_this_plugin()
		{

			$body = $this->get_data();
			$body['status'] = 'Deactivated'; // Never translated
			$body['deactivated_date'] = gmdate('Y-m-d');
			$body['url'] = home_url();
			$body['contact1'] = get_option('admin_email');

			// Add deactivation form data
			if (false !== get_option('eleblog_deactivation_reason_' . $this->plugin_name)) {
				$body['deactivation_reason'] = get_option('eleblog_deactivation_reason_' . $this->plugin_name);
				delete_option('eleblog_deactivation_reason_' . $this->plugin_name);
			}
			if (false !== get_option('eleblog_deactivation_details_' . $this->plugin_name)) {
				$body['deactivation_details'] = get_option('eleblog_deactivation_details_' . $this->plugin_name);
				delete_option('eleblog_deactivation_details_' . $this->plugin_name);
			}

			if (false !== get_option('eleblog_deactivation_email_' . $this->plugin_name)) {
				$body['deactivation_email'] = get_option('eleblog_deactivation_email_' . $this->plugin_name);
				delete_option('eleblog_deactivation_email_' . $this->plugin_name);
			}

			if (false !== get_option('eleblog_deactivation_main_reason_' . $this->plugin_name)) {
				$body['deactivation_main_reason'] = get_option('eleblog_deactivation_main_reason_' . $this->plugin_name);
				delete_option('eleblog_deactivation_main_reason_' . $this->plugin_name);
			}

			if (isset($body['deactivation_reason']) or isset($body['deactivation_details']))
				$this->send_data($body);
		}

		/**
		 * Filter the deactivation link to allow us to present a form when the user deactivates the plugin
		 * @since 1.0.0
		 */
		public function filter_action_links($links)
		{

			if (isset($links['deactivate']) && $this->include_goodbye_form) {
				$deactivation_link = $links['deactivate'];
				// Insert an onClick action to allow form before deactivating
				$deactivation_link = str_replace('<a ', '<div class="ele-blog-goodbye-form-wrapper"><span class="ele-blog-goodbye-form" id="ele-blog-goodbye-form-' . esc_attr($this->plugin_name) . '"></span></div><a onclick="javascript:event.preventDefault();" id="ele-blog-goodbye-link-eleblog-' . esc_attr($this->plugin_name) . '" ', $deactivation_link);
				$links['deactivate'] = $deactivation_link;
			}
			return $links;
		}

		/*
		 * Form text strings
		 * These are non-filterable and used as fallback in case filtered strings aren't set correctly
		 * @since 1.0.0
		 */
		public function form_default_text()
		{
			$form = array();
			$form['body'] = __('If you have a moment, please share why you are deactivating Elementor:', 'ele-blog');
			$form['options'] = array(
				__('Found a Bug', 'ele-blog'),
				__('Need More Features', 'ele-blog'),
				__('I found a different plugin that I like better.', 'ele-blog'),
				__(' It does not do what I need.', 'ele-blog'),
				__('Deactivating Temporarily', 'ele-blog'),

			);
			$form['email'] = __('Please provide your email so we can contact you if needed.', 'ele-blog');
			$form['details'] = __('Please provide some details so we can improve the plugin', 'ele-blog');
			return $form;
		}

		/**
		 * Form text strings
		 * These can be filtered
		 * The filter hook must be unique to the plugin
		 * @since 1.0.0
		 */
		public function form_filterable_text()
		{
			$form = $this->form_default_text();
			return apply_filters('eleblog_form_text_' . esc_attr($this->plugin_name), $form);
		}

		/**
		 * Form text strings
		 * These can be filtered
		 * @since 1.0.0
		 */
		public function form_submit()
		{

			$html = '';

			// Get our strings for the form
			$form = $this->form_filterable_text();
			if (! isset($form['heading']) || ! isset($form['body']) || ! isset($form['options']) || ! is_array($form['options']) || ! isset($form['details'])) {
				// If the form hasn't been filtered correctly, we revert to the default form
				$form = $this->form_default_text();
			}
			// Build the HTML to go in the form
			$html .= '<div class="ele-blog-goodbye-form-body"><p>' . esc_html($form['body']) . '</p>';
			if (is_array($form['options'])) {
				$html .= '<div class="eleblog-goodbye-options"><p>';
				foreach ($form['options'] as $option) {
					$html .= '<input type="radio" name="eleblog-goodbye-options" id="' . str_replace(" ", "", esc_attr($option)) . '" value="' . esc_attr($option) . '"> <label for="' . str_replace(" ", "", esc_attr($option)) . '">' . esc_attr($option) . '</label><br>';
				}
				$html .= '</p><div id="eleblog_additional_content" style="display:none;"><label for="eleblog-goodbye-reasons">' . esc_html($form['email']) . '</label><br><input type="email" name="eleblog-goodbye-email" id="eleblog-goodbye-email" value="' . get_option('admin_email') . '" /> (Optional)';

				$html .= '<br><label for="eleblog-goodbye-reasons">' . esc_html($form['details']) . '</label><textarea name="eleblog-goodbye-reasons" id="eleblog-goodbye-reasons" rows="2" style="width:100%"></textarea></div>';
				$html .= '</div><!-- .eleblog-goodbye-options -->';
			}
			$html .= '</div><!-- .ele-blog-goodbye-form-body -->';
			$html .= '<p class="deactivating-spinner style="width:200px;height:200px"><span class="spinner"></span> ' . __('Submitting form', 'ele-blog') . '</p>';

?>
			<div class="eleblog-goodbye-form-bg"></div>


			<script>
				jQuery(document).ready(function($) {
					$(document).on('change', '.eleblog-goodbye-options input[type=radio]', function() {
						if ($(this).val() == 'Deactivating Temporarily' || $(this).val() == 'Upgrading to Pro') {
							$('#eleblog_additional_content').hide();
						} else {
							$('#eleblog_additional_content').show();
						}

					});

					$('#ele-blog-goodbye-link-eleblog-<?php echo esc_attr($this->plugin_name); ?>').on('click', function() {
						var url = document.getElementById('ele-blog-goodbye-link-eleblog-<?php echo esc_attr($this->plugin_name); ?>');
						$('body').toggleClass('ele-form-active');
						$('#ele-blog-goodbye-form-<?php echo esc_attr($this->plugin_name); ?>').fadeIn();
						$('#ele-blog-goodbye-form-<?php echo esc_attr($this->plugin_name); ?>').html('<?php echo wp_kses_post($html); ?>' + '<div class="ele-blog-goodbye-form-footer"><p><a id=\'ele-blog-submit-form\' class=\'button button-primary\' href=\'#\'>Submit and Deactivate</a>&nbsp;<a class=\'secondary button\' style="float:right;opacity:.5" href=\'' + url + '\'>Just Deactivate</a></p></div>');
						$('#ele-blog-submit-form').on('click', function(e) {

							$('#ele-blog-goodbye-form-<?php echo esc_attr($this->plugin_name); ?> .ele-blog-goodbye-form-body').fadeOut();
							$('#ele-blog-goodbye-form-<?php echo esc_attr($this->plugin_name); ?> .ele-blog-goodbye-form-footer').fadeOut();

							$('#ele-blog-goodbye-form-<?php echo esc_attr($this->plugin_name); ?> .deactivating-spinner').fadeIn();
							e.preventDefault();
							var values = new Array();
							$.each($('input[name=\'eleblog-goodbye-options[]\']:checked'), function() {
								values.push($(this).val());
							});
							var email = $('#eleblog-goodbye-email').val();
							var details = $('#eleblog-goodbye-reasons').val();
							var deactivate_main_reason = $('input[name="eleblog-goodbye-options"]:checked').val();
							var data = {
								'action': 'eleblog_goodbye_form',
								'values': values,
								'main_reason': deactivate_main_reason,
								'details': details,
								'email': email,
								'security': '<?php echo esc_js(wp_create_nonce('eleblog_goodbye_form')); ?>',
								'dataType': 'json'
							}

							$.post(
								ajaxurl,
								data,
								function(response) {

									window.location.href = url;
								}
							);
						});

						$('.eleblog-goodbye-form-bg').on('click', function() {
							$('#ele-blog-goodbye-form-<?php echo esc_attr($this->plugin_name); ?>').fadeOut();
							$('body').removeClass('ele-form-active');
						});
					});
				});
			</script>
<?php }

		/**
		 * AJAX callback when the form is submitted
		 * @since 1.0.0
		 */
		public function goodbye_form_callback()
		{
			// Capability check: only administrators can submit deactivation feedback.
			if (! current_user_can('manage_options')) {
				wp_send_json_error('Insufficient permissions.', 403);
				wp_die();
			}

			// Nonce verification to prevent CSRF attacks.
			if (! isset($_POST['security']) || ! check_ajax_referer('eleblog_goodbye_form', 'security', false)) {
				wp_send_json_error('Nonce verification failed.', 403);
				wp_die();
			}

			if (isset($_POST['values'])) {
				$values = wp_json_encode(array_map('sanitize_text_field', wp_unslash((array) $_POST['values'])));
				update_option('eleblog_deactivation_reason_' . $this->plugin_name, $values);
			}
			if (isset($_POST['details'])) {
				$details = sanitize_text_field(wp_unslash($_POST['details']));
				update_option('eleblog_deactivation_details_' . $this->plugin_name, $details);
			}

			if (isset($_POST['email'])) {
				$email = sanitize_email(wp_unslash($_POST['email']));
				update_option('eleblog_deactivation_email_' . $this->plugin_name, $email);
			}

			if (isset($_POST['main_reason'])) {
				$main_reason = sanitize_text_field(wp_unslash($_POST['main_reason']));
				update_option('eleblog_deactivation_main_reason_' . $this->plugin_name, $main_reason);
			}

			echo 'success';
			wp_die();
		}
	}
}
