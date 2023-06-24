<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Coalition {
	private $_templateDirectoryUri;
	private $_themeName = "Coalition";
	private $_themeSlug = "coalitiontheme";
	private $_assetsUrl;
	private $_homePageTemplateFileRelativePath = "templates/homepage.php";
	private $_homePageTemplateName;
	private $_homePageTitle = "Homepage";
	private $_homePageSlug;
	public $_textDomain = "ct-custom";
	public $_settingsPageSlug;
	public $_settingsPageGeneralName;
	public $_settingsPageSectionID;
	public $_settingsPageOptionGroup;
	public $_mediaLibraryLoaderID;
	public $_TGMPluginActivatorFilePath;
	private $_contactForm7Slug = "contact-form-7";
	private $_contactForm7PostTypeSlug = "wpcf7_contact_form";
	private $_defaultCF7FormSlug;

	function __construct() {
		$this->_templateDirectoryUri       = get_template_directory_uri();
		$this->_assetsUrl                  = $this->_templateDirectoryUri . "/assets/";
		$this->_homePageTemplateName       = $this->_themeName . " Home Page";
		$this->_homePageSlug               = strtolower( $this->_themeSlug . "-" . $this->_homePageTitle );
		$this->_settingsPageSlug           = $this->_themeSlug . "_settings";
		$this->_settingsPageSectionID      = $this->_settingsPageSlug . "_section";
		$this->_settingsPageOptionGroup    = $this->_settingsPageSlug . "_option_group";
		$this->_settingsPageGeneralName    = $this->_settingsPageSlug . "_general_settings";
		$this->_mediaLibraryLoaderID       = $this->_settingsPageSlug . "_media_library";
		$this->_TGMPluginActivatorFilePath = dirname( __FILE__ ) . '/TGM/class-tgm-plugin-activation.php';
		$this->_defaultCF7FormSlug         = $this->_themeSlug . "-default-cf7-form";

	}

	public function run() {
		add_action( "after_switch_theme", [ $this, "onThemeActivation" ] );
		require_once $this->_TGMPluginActivatorFilePath;
		add_action( 'tgmpa_register', [ $this, "register_required_plugins" ] );

		add_action( 'wp_enqueue_scripts', array( $this, "enqueue_public_css_files" ) );
		add_action( 'wp_enqueue_scripts', array( $this, "enqueue_public_javascript_files" ) );
		add_action( 'admin_menu', array( $this, 'addThemeAdminMenu' ), 9 );
		add_action( 'admin_init', array( $this, 'registerAndBuildFields' ) );
		add_action('init', [$this, "register_theme_menu"]);
	}

	public function getThemeDefaultData($defaultContactFormID="") {
		if($defaultContactFormID == ""){
			$checkCF7FormExist = query_posts( [
				'name'      => $this->_defaultCF7FormSlug,
				"post_type" => $this->_contactForm7PostTypeSlug
			] );
			if(isset($checkCF7FormExist[0])){
				$defaultContactFormID = $checkCF7FormExist[0]->ID;
			}
		}
		return [
			"main-color"       => "#fd6301",
			"logo"           => "",
			"fallbackLogo"           => "YOUR|LOGO",
			"phone"           => "385 154 11 28 28",
			"address"         => "535 La Plata Street<br/> 4200 Argentina",
			"fax"             => "385 154 35 65 78",
			"contact_form_id" => $defaultContactFormID,
			"facebook"=>"www.facebook.com",
			"twitter"=>"www.twitter.com",
			"instagram"=>"www.instagram.com",
			"pinterest"=>"www.pinterest.com",
		];
	}

	public function getThemeData() {
		return [
			"main-color"  => get_option( $this->getFieldID( "main-color" ) ),
			"logo"  => get_option( $this->getFieldID( "logo" ) ),
			"fallbackLogo"  => get_option( $this->getFieldID( "fallbackLogo" ) ),
			"phone" => get_option( $this->getFieldID( "phone" ) ),
			"address" => get_option( $this->getFieldID( "address" ) ),
			"fax" => get_option( $this->getFieldID( "fax" ) ),
			"contact_form_id" => get_option( $this->getFieldID( "contact_form_id" ) ),
			"facebook" => get_option( $this->getFieldID( "facebook" ) ),
			"twitter" => get_option( $this->getFieldID( "twitter" ) ),
			"instagram" => get_option( $this->getFieldID( "instagram" ) ),
			"pinterest" => get_option( $this->getFieldID( "pinterest" ) ),
		];
	}



	public function register_theme_menu(){
		register_nav_menus(array( // Using array to specify more menus if needed
			$this->_themeSlug.'-header-menu' => __('Coalition Header Menu', $this->_textDomain),
		));
	}

	public function createDefaultCF7Form() {
		$cf7FormID = "";
		$checkCF7FormExist = query_posts( [
			'name'      => $this->_defaultCF7FormSlug,
			"post_type" => $this->_contactForm7PostTypeSlug
		] );
		if ( ! isset( $checkCF7FormExist[0] ) ) {
			$cf7FormID = wp_insert_post( [
				'post_type'   => $this->_contactForm7PostTypeSlug,
				'post_title'  => __( "Default Coalition Theme Contact Form", $this->_textDomain ),
				'post_name'   => $this->_defaultCF7FormSlug,
				"post_status" => "publish",
				"meta_input"  => [
					"_form" => '[text* fullname placeholder "Name"]
<div>
    [tel* phone placeholder "Phone"]
    [email* mail placeholder "Email"]
</div>
[textarea* message x5 placeholder "Message"]
[submit "Submit"]'
				]
			] );
		}else{
			$cf7FormID = $checkCF7FormExist[0]->ID;
		}
		return $cf7FormID;
	}

	public function getAssetsURL( $additional = "" ) {
		return $this->_assetsUrl . $additional;
	}


	public function enqueue_public_css_files() {
		$file_array = array(
			$this->_themeSlug . "-all-css" => "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css",
			$this->_themeSlug . "-style"   => $this->_assetsUrl . "public/css/styles.css",
		);
		//self::varDumpPre($file_array, true);

		foreach ( $file_array as $file_key => $file_url ) {
			wp_register_style( $file_key, $file_url, [], "1.003" );
			wp_enqueue_style( $file_key );
		}
	}


	public function enqueue_public_javascript_files() {
		$file_array = array(
			$this->_themeSlug . "-index" => $this->_assetsUrl . 'public/js/index.js',
		);

		foreach ( $file_array as $file_key => $file_url ) {
			wp_register_script( $file_key, $file_url, [], "1.000", true );
			wp_enqueue_script( $file_key );
		}
	}

	public function enqueue_admin_css_files() {
		$file_array = array(
			$this->_settingsPageSlug . "-style" => $this->_assetsUrl . "admin/css/settings.css",
		);

		foreach ( $file_array as $file_key => $file_url ) {
			wp_enqueue_style( $file_key, $file_url, [], "1.002" );
		}
	}

	public function enqueue_admin_javascript_files() {
		$file_array = array(
			$this->_settingsPageSlug => $this->_assetsUrl . 'admin/js/settings.js',
		);

		foreach ( $file_array as $file_key => $file_url ) {
			wp_enqueue_script( $file_key, $file_url, array( 'jquery-ui-core', 'jquery-ui-tabs' ) );
		}
		wp_enqueue_media();
		wp_localize_script( $this->_settingsPageSlug, 'coalitionJSData', [
			"mediaGalleryLoaderSelector" => $this->_mediaLibraryLoaderID,
			"mediaGalleryButtonText"     => __( "Insert", $this->_textDomain )
		] );
	}

	public function addThemeAdminMenu() {
		$mySettingPage = add_menu_page( __( "Coalition Theme Settings", $this->_textDomain ), __( "Coalition Theme Settings", $this->_textDomain ), 'administrator', $this->_settingsPageSlug, array(
			$this,
			'displayThemeSettingsAdminDashboard'
		), 'dashicons-chart-area', 26 );

		//load JS
		add_action( 'load-' . $mySettingPage, [ $this, "loadSettingsJSAndCSS" ] );
	}

	public function loadSettingsJSAndCSS() {
		add_action( 'admin_enqueue_scripts', [ $this, "enqueue_admin_javascript_files" ] );
		add_action( 'admin_enqueue_scripts', [ $this, "enqueue_admin_css_files" ] );
	}

	public function displayThemeSettingsAdminDashboard() {
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		if ( isset( $_GET['error_message'] ) ) {
			add_action( 'admin_notices', array( $this, 'settingsPageSettingsMessages' ) );
			do_action( 'admin_notices', $_GET['error_message'] );
		}
		require_once $this->_themeSlug . '-admin-settings-display.php';
	}


	public function settingsPageSettingsMessages( $error_message ) {
		$message       = "";
		$err_code      = "";
		$setting_field = "";
		$type          = 'error';
		switch ( $error_message ) {
			case '1':
				$message       = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', $this->_textDomain );
				$err_code      = esc_attr( 'settings_page_example_setting' );
				$setting_field = 'settings_page_example_setting';
				break;
		}
		add_settings_error(
			$setting_field,
			$err_code,
			$message,
			$type
		);
	}

	private function getFieldID( $fieldName ) {
		return $this->_settingsPageSlug . "_" . $fieldName;
	}

	private function getContactFormsList() {
		$lists   = [];
		$results = get_posts( array(
			'post_type'   => $this->_contactForm7PostTypeSlug,
			'numberposts' => - 1
		) );
		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$lists[] = [
					"value" => $result->ID,
					"label" => $result->post_title,
				];
			}
		}

		return $lists;
	}


	public function registerAndBuildFields() {
		/**
		 * First, we add_settings_section. This is necessary since all future settings must belong to one.
		 * Second, add_settings_field
		 * Third, register_setting
		 */
		add_settings_section(
		// ID used to identify this section and with which to register options
			$this->_settingsPageSectionID,
			// Title to be displayed on the administration page
			'',
			// Callback used to render the description of the section
			array( $this, 'setting_page_general_description' ),
			// Page on which to add this section of options
			$this->_settingsPageGeneralName
		);
		unset( $args );
		$fieldsLists = [
			array(
				'type'             => 'media_library',
				'buttonLabel'      => __( "Select Image", $this->_textDomain ),
				//'subtype'          => 'text',
				'id'               => $this->getFieldID( "logo" ),
				'name'             => $this->getFieldID( "logo" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Select Site Logo", $this->_textDomain ),
			),
			array(
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => $this->getFieldID( "fallbackLogo" ),
				'name'             => $this->getFieldID( "fallbackLogo" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Text Logo", $this->_textDomain ),
			),array(
				'type'             => 'input',
				'subtype'          => 'color',
				'id'               => $this->getFieldID( "main-color" ),
				'name'             => $this->getFieldID( "main-color" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Main Color", $this->_textDomain ),
			),
			array(
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => $this->getFieldID( "phone" ),
				'name'             => $this->getFieldID( "phone" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Site Telephone", $this->_textDomain ),
			),
			array(
				'type'             => 'textarea',
				'rows'             => 3,
				'id'               => $this->getFieldID( "address" ),
				'name'             => $this->getFieldID( "address" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Site Address", $this->_textDomain ),
			),
			array(
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => $this->getFieldID( "fax" ),
				'name'             => $this->getFieldID( "fax" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Site Fax", $this->_textDomain ),
			),
			array(
				'type'             => 'select',
				'id'               => $this->getFieldID( "contact_form_id" ),
				'name'             => $this->getFieldID( "contact_form_id" ),
				//'required'         => 'true',
				'get_options_list' => $this->getContactFormsList(),
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Contact Form to use", $this->_textDomain ),
			),

			array(
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => $this->getFieldID( "facebook" ),
				'name'             => $this->getFieldID( "facebook" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Facebook URL", $this->_textDomain ),
			),
			array(
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => $this->getFieldID( "twitter" ),
				'name'             => $this->getFieldID( "twitter" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Twitter URL", $this->_textDomain ),
			),
			array(
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => $this->getFieldID( "instagram" ),
				'name'             => $this->getFieldID( "instagram" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Instagram URL", $this->_textDomain ),
			),
			array(
				'type'             => 'input',
				'subtype'          => 'text',
				'id'               => $this->getFieldID( "pinterest" ),
				'name'             => $this->getFieldID( "pinterest" ),
				//'required'         => 'true',
				'get_options_list' => '',
				'value_type'       => 'normal',
				'wp_data'          => 'option',
				'title'            => __( "Pinterest URL", $this->_textDomain ),
			),


		];
		if ( ! empty( $fieldsLists ) ) {
			foreach ( $fieldsLists as $args ) {
				add_settings_field(
					$args["id"],
					$args["title"],
					array( $this, 'settings_page_render_settings_field' ),
					$this->_settingsPageGeneralName,
					$this->_settingsPageSectionID,
					$args
				);
				register_setting(
					$this->_settingsPageGeneralName,
					$args["id"]
				);
			}
		}

	}

	public function setting_page_general_description() {
		echo '<p>These settings apply to this theme functionality.</p>';
	}

	public function settings_page_render_settings_field( $args ) {
		if ( $args['wp_data'] == 'option' ) {
			$wp_data_value = get_option( $args['name'] );
		} elseif ( $args['wp_data'] == 'post_meta' ) {
			$wp_data_value = get_post_meta( $args['post_id'], $args['name'], true );
		}
		/*if($wp_data_value === false){
			$wp_data_value = self::maybeNullOrEmpty($args, "defaultValue", false);
		}*/
		$value    = ( $args['value_type'] == 'serialized' ) ? serialize( $wp_data_value ) : $wp_data_value;
		$required = isset( $args["required"] ) ? 'required=""' : '';
		switch ( $args['type'] ) {
			case "media_library":
				//$value = ! ! esc_attr( $value ) && is_numeric(esc_attr( $value )) ? wp_get_attachment_image_url( $value, "full" ) : "";
				echo '<div class="coalition-media-gallery-preview-container">
					<img src="' . (! ! esc_attr( $value ) && is_numeric(esc_attr( $value )) ? wp_get_attachment_image_url( $value, "full" ) : "") . '" class="' . ( ! ! $value ? "" : "is-hidden" ) . '" />
					<input type="hidden" name="' . $args["name"] . '" value="' . esc_attr( $value ) . '" />
					<a href="#" data-title="' . $args["title"] . '" class="' . $this->_mediaLibraryLoaderID . ' button">' . $args["buttonLabel"] . '</a></div>';
				break;

			case "select":
				$options = '<option>' . __( "Select", $this->_textDomain ) . '</option>';
				if ( ! empty( $args["get_options_list"] ) ) {
					foreach ( $args["get_options_list"] as $option ) {
						$selected = esc_attr( $value ) == $option["value"] ? 'selected="1"' : '';
						$options  .= '<option value="' . $option["value"] . '" ' . $selected . '>' . $option["label"] . '</option>';
					}
				}
				echo '<div class="select" ><select name="' . $args["name"] . '" id="' . $args["id"] . '">' . $options . '</select></div>';
				break;
			case 'input':
				if ( $args['subtype'] != 'checkbox' ) {
					$prependStart = ( isset( $args['prepend_value'] ) ) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
					$prependEnd   = ( isset( $args['prepend_value'] ) ) ? '</div>' : '';
					$step         = ( isset( $args['step'] ) ) ? 'step="' . $args['step'] . '"' : '';
					$min          = ( isset( $args['min'] ) ) ? 'min="' . $args['min'] . '"' : '';
					$max          = ( isset( $args['max'] ) ) ? 'max="' . $args['max'] . '"' : '';
					if ( isset( $args['disabled'] ) ) {
						// hide the actual input bc if it was just a disabled input the info saved in the database would be wrong - bc it would pass empty values and wipe the actual information
						echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr( $value ) . '" /><input type="hidden" id="' . $args['id'] . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr( $value ) . '" />' . $prependEnd;
					} else {
						echo $prependStart . '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $required . '" ' . $step . ' ' . $max . ' ' . $min . ' name="' . $args['name'] . '" size="40" value="' . esc_attr( $value ) . '" />' . $prependEnd;
					}
					/*<input required="required" '.$disabled.' type="number" step="any" id="'.$this->plugin_name.'_cost2" name="'.$this->plugin_name.'_cost2" value="' . esc_attr( $cost ) . '" size="25" /><input type="hidden" id="'.$this->plugin_name.'_cost" step="any" name="'.$this->plugin_name.'_cost" value="' . esc_attr( $cost ) . '" />*/

				} else {
					$checked = ( $value ) ? 'checked' : '';
					echo '<input type="' . $args['subtype'] . '" id="' . $args['id'] . '" "' . $required . '" name="' . $args['name'] . '" size="40" value="1" ' . $checked . ' />';
				}
				break;
			case 'textarea':
				$prependStart = ( isset( $args['prepend_value'] ) ) ? '<div class="input-prepend"> <span class="add-on">' . $args['prepend_value'] . '</span>' : '';
				$prependEnd   = ( isset( $args['prepend_value'] ) ) ? '</div>' : '';
				if ( isset( $args['disabled'] ) ) {
					// hide the actual input bc if it was just a disabled input the info saved in the database would be wrong - bc it would pass empty values and wipe the actual information
					echo $prependStart . '<textarea type="' . $args['subtype'] . '" id="' . $args['id'] . '_disabled" ' . ' name="' . $args['name'] . '_disabled" size="40" disabled value="' . esc_attr( $value ) . '" /><input type="hidden" id="' . $args['id'] . '" ' . ' name="' . $args['name'] . '" size="40" value="' . esc_attr( $value ) . '" />' . $prependEnd;
				} else {
					echo $prependStart . '<textarea id="' . $args['id'] . '" "' . $required . '" ' . ' name="' . $args['name'] . '" row="' . $args['rows'] . '" >' . esc_attr( $value ) . '</textarea>' . $prependEnd;
				}
				break;
			default:
				# code...
				break;
		}
	}


	public function onThemeActivation() {
		//CREATES HOME PAGE
		$this->createHomePage();
		//CREATES DEFAULT CONTACT FORM
		$cf7FormID = $this->createDefaultCF7Form();
		//SET THEME DEFAULT DATA
		//checking to see if default data has been previously set
		$hasBeenSetKey = $this->_themeSlug."-theme-default-data-has-been-set";
		if(!get_option($hasBeenSetKey)){
			$defaultThemeData = $this->getThemeDefaultData($cf7FormID);
			if(!empty($defaultThemeData)){
				foreach ($defaultThemeData as $key => $data){
					update_option($this->getFieldID($key), $data);
				}
				update_option($hasBeenSetKey, "1");
			}
		}


	}

	private function createHomePage() {
		//checking if page exists already
		$checkPageExist = query_posts( [
			'name'      => $this->_homePageSlug,
			"post_type" => 'page'
		] );
		// If the page doesn't already exist, create it
		if ( ! isset( $checkPageExist[0] ) ) {
			$pageID = wp_insert_post( [
				'post_type'     => 'page',
				'post_title'    => $this->_homePageTitle,
				'post_name'     => $this->_homePageSlug,
				"page_template" => $this->_homePageTemplateFileRelativePath,
				"post_status"   => "publish",
				//'post_name'     => 'home'
			] );
		} else {
			$pageID = $checkPageExist[0]->ID;
		}
		if ( ! ! $pageID ) {
			self::setPageAsHomePage( $pageID );
		}
	}

	public function register_required_plugins() {

		/**
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(

			// CONTACT FORM 7
			array(
				'name'     => 'Contact Form 7',
				'slug'     => $this->_contactForm7Slug,
				'required' => true,
				'force_activation'   => true,
				//'force_deactivation' => true
			)
		);

		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'default_path' => '',
			// Default absolute path to pre-packaged plugins.
			'menu'         => 'tgmpa-install-plugins',
			// Menu slug.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,
			// Automatically activate plugins after installation or not.
			'message'      => '',
			// Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
				'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
				'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ),
				// %s = plugin name.
				'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
				// %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
				// %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
				// %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
				// %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
				// %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
				// %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
				// %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
				// %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
				'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
				'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
				'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ),
				// %s = dashboard link.
				'nag_type'                        => 'updated'
				// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);

		tgmpa( $plugins, $config );

	}

	static function setPageAsHomePage( $pageID ) {
		update_option( 'page_on_front', $pageID );
		update_option( 'show_on_front', 'page' );
	}

	static function maybeNullOrEmpty( $element, $property, $defaultValue = "" ) {
		if ( is_object( $element ) ) {
			$element = (array) $element;
		}
		if ( isset( $element[ $property ] ) ) {
			return $element[ $property ];
		} else {
			return $defaultValue;
		}

	}

	static function varDumpPre( $toBeDumped, $exit = false ) {
		echo "<br/><pre>" . var_dump( $toBeDumped ) . "</pre>";
		if ( $exit ) {
			exit;
		}
	}

	static function convertUrlToGoodUrl ($url){
		if  ( $ret = parse_url($url) ) {

			if ( !isset($ret["scheme"]) )
			{
				$url = "http://{$url}";
			}
		}
		return $url;
	}

	public function showMenu ($location = '', $class = '', $id = ''){
		$location = $location == "" ? $location :$this->_themeSlug.'-header-menu';
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'menu' => '',
				'container' => 'div',
				'container_class' => 'menu-{menu slug}-container',
				'container_id' => '',
				'menu_class' => 'menu',
				'menu_id' => '',
				'echo' => true,
				'fallback_cb' => 'wp_page_menu',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '<ul class="' . $class . ' id=' . $id . '">%3$s</ul>',
				'depth' => 0,
				'walker' => ''
			)
		);

	}

	public function showCF7FormViaShortcode($cf7FormID){
		if(!!$cf7FormID){
			return do_shortcode('['.$this->_contactForm7Slug.' id="'.$cf7FormID.'"]');
		}
		return "";
	}


}

$coalitionTheme = new Coalition();
$coalitionTheme->run();
