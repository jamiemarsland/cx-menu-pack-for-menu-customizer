<?php

if ( ! class_exists( 'PNM_Menu_Pack_Control' ) ) :
    if (!class_exists( 'WP_Customize_Control' )) {
        require_once(ABSPATH . '/wp-includes/class-wp-customize-control.php');
    }

	class PNM_Menu_Pack_Control extends WP_Customize_Control {

        public $option_name;
        public $type = 'menu-pack';

        public $menus = array();

		/**
		 * Constructor.
		 *
		 * If $args['settings'] is not defined, use the $id as the setting ID.
		 *
		 * @since 3.4.0
		 * @uses WP_Customize_Upload_Control::__construct()
		 *
		 * @param WP_Customize_Manager $manager
		 * @param string $id
		 * @param array $args
		 */
		public function __construct( $manager, $id, $args = array() ) {


			parent::__construct( $manager, $id, $args );

		}



        public function enqueue() {
            parent::enqueue();
        }

		public function render_content() {

            ?>
            <label>
                <span class="customize-control-title">Menus</span>
                <div class="customize-control-content">

                    <select class="pnm-menu-pack" name="pnm-menu-pack">
                        <option value="">Select</option>
                    <?php

                    foreach ($this->menus as $name => $settings) {
                        ?>
                        <option value="<?php esc_attr_e($name) ?>" ><?php esc_html_e($name) ?></option>
                        <?php
                    }

                    ?>
                    </select>

                    <input type="button" class="pnm-menu-pack-apply-button button" value="Apply" />

                    <script>
                        (function ($) {

                            $(document).ready(function () {
                                $('.pnm-menu-pack-apply-button').click(function () {
                                    var menuPack = $('.pnm-menu-pack').val();
                                    if (menuPack != '') {
                                        window.location.href = '?pnm-menu-pack=' + encodeURIComponent(menuPack);
                                    }

                                });
                            });

                        })(jQuery);
                    </script>

                </div>
            </label>
            <?php
		}
	}
endif;