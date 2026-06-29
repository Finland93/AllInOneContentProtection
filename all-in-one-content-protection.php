<?php
/*
Plugin Name: All in one Content protection
Plugin URI: https://github.com/Finland93/AllInOneContentProtection
Description: Deters casual copying of your content: disable right-click, text selection, copy/cut, image dragging, common shortcuts, and printing — each toggleable from a settings page. Includes a configurable developer-console warning.
Version: 2.0.0
Author: Finland93
Author URI: https://github.com/Finland93
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: all-in-one-content-protection
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AIOCP_VERSION', '2.0.0' );
define( 'AIOCP_FILE', __FILE__ );
define( 'AIOCP_URL', plugin_dir_url( __FILE__ ) );

final class AIOCP_Content_Protection {

	const OPTION = 'aiocp_options';

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_assets' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( AIOCP_FILE ), array( $this, 'action_links' ) );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'all-in-one-content-protection', false, dirname( plugin_basename( AIOCP_FILE ) ) . '/languages' );
	}

	private function defaults() {
		return array(
			'contextmenu'   => 'on',
			'selection'     => 'on',
			'drag'          => 'on',
			'shortcuts'     => 'on',
			'print_protect' => 'on',
			'console_msg'   => 'on',
			'guests_only'   => '',
			'console_title' => 'STOP!',
			'console_text'  => "This browser console is intended for developers only.\nIf someone told you to copy or paste something here, it could be a scam.\nDo not continue unless you fully understand what you are doing.",
		);
	}

	private function options() {
		$opts = get_option( self::OPTION, array() );
		return wp_parse_args( is_array( $opts ) ? $opts : array(), $this->defaults() );
	}

	private function is_on( $key, $opts ) {
		return isset( $opts[ $key ] ) && 'on' === $opts[ $key ];
	}

	/* ---------------------------------------------------------------------
	 * Front-end
	 * ------------------------------------------------------------------- */

	public function frontend_assets() {
		$opts = $this->options();

		// Let logged-in editors work normally when "guests only" is enabled.
		if ( $this->is_on( 'guests_only', $opts ) && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			return;
		}

		// ---- Build the CSS for the enabled protections ----
		$css = '';

		if ( $this->is_on( 'selection', $opts ) ) {
			$css .= 'body{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;}';
			// Keep form fields usable.
			$css .= 'input,textarea,select,[contenteditable="true"]{-webkit-user-select:text;-moz-user-select:text;-ms-user-select:text;user-select:text;}';
		}

		if ( $this->is_on( 'drag', $opts ) ) {
			$css .= 'img,a,[draggable]{-webkit-user-drag:none;user-drag:none;}';
		}

		if ( $this->is_on( 'print_protect', $opts ) ) {
			$css .= '@media print{html,body{display:none!important;}@page{margin:0;}}';
		}

		// A registered style with no src lets us attach inline CSS cleanly,
		// instead of echoing a <style> block straight into wp_head.
		wp_register_style( 'aiocp', false, array(), AIOCP_VERSION );
		wp_enqueue_style( 'aiocp' );
		if ( '' !== $css ) {
			wp_add_inline_style( 'aiocp', $css );
		}

		// ---- JS for the interactive protections ----
		wp_enqueue_script( 'aiocp', AIOCP_URL . 'assets/protection.js', array(), AIOCP_VERSION, true );
		wp_localize_script(
			'aiocp',
			'AIOCP',
			array(
				'contextmenu'  => $this->is_on( 'contextmenu', $opts ),
				'selection'    => $this->is_on( 'selection', $opts ),
				'drag'         => $this->is_on( 'drag', $opts ),
				'shortcuts'    => $this->is_on( 'shortcuts', $opts ),
				'printProtect' => $this->is_on( 'print_protect', $opts ),
				'consoleMsg'   => $this->is_on( 'console_msg', $opts ),
				'consoleTitle' => (string) $opts['console_title'],
				'consoleText'  => (string) $opts['console_text'],
			)
		);
	}

	/* ---------------------------------------------------------------------
	 * Settings
	 * ------------------------------------------------------------------- */

	public function action_links( $links ) {
		$url       = admin_url( 'options-general.php?page=aiocp' );
		$settings  = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'all-in-one-content-protection' ) . '</a>';
		array_unshift( $links, $settings );
		return $links;
	}

	public function admin_menu() {
		add_options_page(
			__( 'Content Protection', 'all-in-one-content-protection' ),
			__( 'Content Protection', 'all-in-one-content-protection' ),
			'manage_options',
			'aiocp',
			array( $this, 'render_settings_page' )
		);
	}

	public function register_settings() {
		register_setting( 'aiocp_group', self::OPTION, array( 'sanitize_callback' => array( $this, 'sanitize' ) ) );
	}

	public function sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();
		$out   = array();

		foreach ( array( 'contextmenu', 'selection', 'drag', 'shortcuts', 'print_protect', 'console_msg', 'guests_only' ) as $cb ) {
			$out[ $cb ] = ( isset( $input[ $cb ] ) && 'on' === $input[ $cb ] ) ? 'on' : '';
		}

		$out['console_title'] = isset( $input['console_title'] ) ? sanitize_text_field( $input['console_title'] ) : 'STOP!';
		$out['console_text']  = isset( $input['console_text'] ) ? sanitize_textarea_field( $input['console_text'] ) : '';

		return $out;
	}

	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$opts = $this->options();

		$checkbox = function ( $key, $label, $desc ) use ( $opts ) {
			printf(
				'<tr><th scope="row">%1$s</th><td><label><input type="checkbox" name="%2$s[%3$s]" %4$s> %5$s</label></td></tr>',
				esc_html( $label ),
				esc_attr( self::OPTION ),
				esc_attr( $key ),
				checked( 'on', isset( $opts[ $key ] ) ? $opts[ $key ] : '', false ),
				esc_html( $desc )
			);
		};
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Content Protection', 'all-in-one-content-protection' ); ?></h1>

			<div class="notice notice-info inline" style="max-width:760px;">
				<p><?php esc_html_e( 'These protections deter casual copying and right-click saving. They run in the visitor\'s browser, so a determined user can still bypass them — treat this as a deterrent, not DRM.', 'all-in-one-content-protection' ); ?></p>
			</div>

			<form method="post" action="options.php">
				<?php settings_fields( 'aiocp_group' ); ?>
				<table class="form-table" role="presentation">
					<?php
					$checkbox( 'contextmenu', __( 'Right-click', 'all-in-one-content-protection' ), __( 'Disable the right-click context menu.', 'all-in-one-content-protection' ) );
					$checkbox( 'selection', __( 'Text selection', 'all-in-one-content-protection' ), __( 'Prevent selecting, copying and cutting text (form fields stay usable).', 'all-in-one-content-protection' ) );
					$checkbox( 'drag', __( 'Image / link dragging', 'all-in-one-content-protection' ), __( 'Prevent dragging images and links.', 'all-in-one-content-protection' ) );
					$checkbox( 'shortcuts', __( 'Keyboard shortcuts', 'all-in-one-content-protection' ), __( 'Block Ctrl/Cmd+C/A/U/S/P/X and dev-tools shortcuts (F12, etc.).', 'all-in-one-content-protection' ) );
					$checkbox( 'print_protect', __( 'Printing', 'all-in-one-content-protection' ), __( 'Hide the page when the visitor tries to print.', 'all-in-one-content-protection' ) );
					$checkbox( 'console_msg', __( 'Console warning', 'all-in-one-content-protection' ), __( 'Show a warning in the browser developer console.', 'all-in-one-content-protection' ) );
					$checkbox( 'guests_only', __( 'Guests only', 'all-in-one-content-protection' ), __( 'Skip protections for logged-in users who can edit content.', 'all-in-one-content-protection' ) );
					?>
					<tr>
						<th scope="row"><?php esc_html_e( 'Console title', 'all-in-one-content-protection' ); ?></th>
						<td><input type="text" name="<?php echo esc_attr( self::OPTION ); ?>[console_title]" value="<?php echo esc_attr( $opts['console_title'] ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Console message', 'all-in-one-content-protection' ); ?></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION ); ?>[console_text]" rows="4" class="large-text"><?php echo esc_textarea( $opts['console_text'] ); ?></textarea></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

AIOCP_Content_Protection::instance();
