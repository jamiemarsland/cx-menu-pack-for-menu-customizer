<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Pootlepress_Menu_Customizer_Menu_Pack Class
 *
 * Base class for the Pootlepress Menu Customizer Menu Pack.
 *
 * @package WordPress
 * @subpackage Pootlepress_Menu_Customizer_Menu_Pack
 * @category Core
 * @author Pootlepress
 * @since 1.0.0

 */
class Pootlepress_Menu_Customizer_Menu_Pack {
	public $token = 'pootlepress-menu-customizer-menu-pack';
	public $version;
	public $file;

    public $options;

	/**
	 * Constructor.
	 * @param string $file The base file of the plugin.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function __construct ( $file ) {
		$this->file = $file;

		// Run this on activation.
		register_activation_hook( $file, array( &$this, 'activation' ) );

        add_action('customize_register', array($this, 'register') );

        add_action('init', array($this, 'handle_submit'));

    } // End __construct()

    public function register(WP_Customize_Manager $customizeManager)
    {

        require_once( dirname(__FILE__) . '/class-pnm-menu-pack-control.php' );

        // sections
        // section id cannot start with "pnm_", or it will be put inside "Primary Nav" section by Menu Customizer
        $customizeManager->add_section('menu_pack_pnm', array(
            'title' => 'Menu Pack',
            'priority' => 15
        ));

        // need at least a dummy setting or there will be fatal error in WP_Customize_Control
        $customizeManager->add_setting('pnm_menu_pack_dummy', array(
            'type' => 'option'
        ));

        $customizeManager->add_control(new PNM_Menu_Pack_Control($customizeManager, 'pnm_menu_pack_dummy', array(
            'section' => 'menu_pack_pnm',
            'menus' => $this->get_menus())));
    }

    public function handle_submit() {
        global $pagenow;
        if ($pagenow == 'customize.php') {
            if (isset($_REQUEST['pnm-menu-pack'])) {
                $menuPack = $_REQUEST['pnm-menu-pack'];

                $menus = $this->get_menus();

                if (isset($menus[$menuPack])) {
                    // menu pack valid
                    $settings = $menus[$menuPack];
                    foreach ($settings as $key => $value) {
                        update_option($key, $value);
                    }

                    wp_safe_redirect(admin_url('customize.php'));
                    die();
                }
            }
        }

    }

    private function get_menus() {
        global $pootlepress_menu_customizer_menu_pack;
        $menusDirPath = dirname($pootlepress_menu_customizer_menu_pack->file) . '/menus';

        $result = array();
        $entries = array();
        if ($handle = opendir($menusDirPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $entries[] = $entry;
                }
            }
            closedir($handle);
        }

        foreach ($entries as $entry) {
            $entryPath = $menusDirPath . '/' . $entry;
            if (is_file($entryPath)) {
                $fileContent = file_get_contents($entryPath);
                if (is_string($fileContent)) {

                    // the settings are in json, convert it
                    $data = json_decode($fileContent, true);

                    $result[$entry] = $data;
                }
            }
        }

        return $result;
    }

	/**
	 * Run on activation.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * Register the plugin's version.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	private function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( $this->token . '-version', $this->version );
		}
	} // End register_plugin_version()

} // End Class


