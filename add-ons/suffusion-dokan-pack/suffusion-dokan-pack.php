<?php
/**
 * Plugin Name: Suffusion Dokan Pack
 * Description: This plugin is an add-on to the Suffusion WordPress Theme. It provides styling for Dokan vendor dashboard to match Suffusion skins.
 * Version: 1.0.1
 * Author: Sayontan Sinha
 * License: GNU General Public License (GPL), v3 (or newer)
 */

include_once(plugin_dir_path(__FILE__) . '/suffusion-integration-pack.php');

class Suffusion_Dokan_Pack extends Suffusion_Integration_Pack {
	function __construct() {
		if (!defined('SUFFUSION_DOKAN_PACK_VERSION')) {
			define('SUFFUSION_DOKAN_PACK_VERSION', '1.0.1');
		}
		parent::__construct('Suffusion Dokan Pack', 'Suffusion Dokan Pack', 'suffusion-dokan-pack', SUFFUSION_DOKAN_PACK_VERSION);

		// Use high priority to ensure this loads after Dokan's own CSS
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_dokan_styles'), 999);
	}

	function add_admin_scripts($hook) {
		if ($hook == $this->option_page) {
			wp_enqueue_style('sdp-admin', plugins_url('include/css/admin.css', __FILE__), array(), $this->version);
		}
	}

	function add_scripts() {
		// Parent class calls this, but we use enqueue_dokan_styles for more control
	}

	function enqueue_dokan_styles() {
		// Check for common Dokan functions/classes to verify activation
		if (function_exists('dokan') || class_exists('WeDevs_Dokan')) {
			$options = get_option('suffusion_dokan_pack_options');
			if (!isset($options['match_skin']) || $options['match_skin'] == 'on') {
				wp_enqueue_style('suffusion-dokan-pack', plugins_url('include/css/sdp.css', __FILE__), array(), $this->version);
			}
		}
	}

	function render_options() {
		if (isset($_POST['sdp_save'])) {
			check_admin_referer('sdp_options_save', 'sdp_nonce');
			$options = array(
				'match_skin' => isset($_POST['match_skin']) ? 'on' : 'off',
			);
			update_option('suffusion_dokan_pack_options', $options);
			echo "<div class='updated'><p>Settings saved.</p></div>";
		}

		$options = get_option('suffusion_dokan_pack_options');
		$match_skin = !isset($options['match_skin']) || $options['match_skin'] == 'on';

		$dokan_active = function_exists('dokan') || class_exists('WeDevs_Dokan');
?>
		<div class="suf-ip-wrapper">
			<h1>Suffusion Dokan Pack</h1>
			<?php $this->check_theme(); ?>

			<div class="updated" style="margin-top: 1em;">
				<p><strong>Status:</strong> <?php echo $dokan_active ? "Dokan plugin detected." : "Dokan plugin not detected. Please ensure Dokan is installed and active."; ?></p>
			</div>

			<form method="post">
				<?php wp_nonce_field('sdp_options_save', 'sdp_nonce'); ?>
				<fieldset>
					<legend>Dashboard Styling</legend>
					<p>
						<input type="checkbox" name="match_skin" id="match_skin" <?php checked($match_skin); ?> />
						<label for="match_skin">Match Dokan Dashboard styling with Suffusion skin</label>
					</p>
					<p class="description">
						This option will apply a custom stylesheet to match your active Suffusion skin on the Dokan Vendor Dashboard.
					</p>
					<p class="submit">
						<input type="submit" name="sdp_save" class="button-primary" value="Save Settings" />
					</p>
				</fieldset>
			</form>
			<?php $this->other_plugins(); ?>
		</div>
<?php
	}
}

add_action('init', 'init_suffusion_dokan_pack');
function init_suffusion_dokan_pack() {
	global $Suffusion_Dokan_Pack;
	$Suffusion_Dokan_Pack = new Suffusion_Dokan_Pack();
}
