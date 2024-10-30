<?php

if (!class_exists('mgps_framework')) {

    class mgps_framework {

        public $args        = array();
        public $sections    = array();
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

           
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);           

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        function compiler_action($options, $css, $changed_values) {

			 $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
        }


       

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            /*
            $sample_patterns_path   = ReduxFramework::$_dir . '../settings/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../settings/patterns/';
            */
            $google_url = "https://3b073ac2c18e8837dfd43e1fefb99744f0b466e0.googledrive.com/host/0B_asRBcBoGviZXB2LVB6cDBNbHM/images/";


            $this->sections[] = array(
                'icon'      => 'dashicons dashicons-admin-appearance',
                'title'     => __('Background', 'mgps-slider'),
                'heading'   => __('Choose Slider Background Pattern.', 'mgps-slider'),
                'desc'      => __('<p class="description">Select your faviorate background pattern for parallax slider. Custom Upload background patterns is comming soon.</p>', 'mgps-slider'),
                'fields'    => array(
	  				array(
                        'id'        => 'mg-slider-default-background',
                        'type'      => 'image_select',
                        'compiler'	=> array('.da-slider'),
                        'title'     => __('Choose Default Backgrounds', 'mgps-slider'),
                        'subtitle'  => __('No validation can be done on this field type', 'mgps-slider'),
                        'desc'      => __('This is the description field, again good for additional info.', 'mgps-slider'),
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(

                        	$google_url. 'default-bg.gif'  => array('title' => 'Default', 'img' => $google_url. 'default-bg-1.gif'),
                            $google_url . '036.jpg' => array('title' => 'Pattern 1', 'img' => $google_url . '036-1.jpg'),
                            $google_url . '037.jpg' => array('title' => 'Pattern 2', 'img' => $google_url . '037-1.jpg'),
                            $google_url . '033.jpg' => array('title' => 'Pattern 3', 'img' => $google_url . '033-1.jpg'),
                            $google_url . '034.jpg' => array('title' => 'Pattern 4', 'img' => $google_url . '034-1.jpg'),
                            $google_url . '026.jpg' => array('title' => 'Pattern 5', 'img' => $google_url. '026-1.jpg'),
                            $google_url . '029.jpg' => array('title' => 'Pattern 6', 'img' => $google_url. '029-1.jpg'),
                            $google_url. '031.jpg' => array('title' => 'Pattern 7', 'img' => $google_url. '031-1.jpg'),
                            $google_url. '022.jpg' => array('title' => 'Pattern 8', 'img' => $google_url. '022-1.jpg'),
                            $google_url. '023.jpg' => array('title' => 'Pattern 9', 'img' => $google_url. '023-1.jpg'),
                            $google_url. '024.jpg' => array('title' => 'Pattern 10', 'img' => $google_url. '024-1.jpg'),
                            $google_url. '014.jpg' => array('title' => 'Pattern 11', 'img' => $google_url. '014-1.jpg'),
                            $google_url. '015.jpg' => array('title' => 'Pattern 12', 'img' => $google_url. '015-1.jpg'),
                            $google_url. '009.jpg' => array('title' => 'Pattern 13', 'img' => $google_url. '009-1.jpg'),
                            $google_url. '001.jpg' => array('title' => 'Pattern 14', 'img' => $google_url. '001-1.jpg'),
                            $google_url. 'bgr1.jpg' => array('title' => 'Pattern 15', 'img' => $google_url. 'bgr1-1.jpg'),
                            $google_url. '001.jpg' => array('title' => 'Pattern 16', 'img' => $google_url. '001-1.jpg'),
                            $google_url. 'bgr2.jpg' => array('title' => 'Pattern 17', 'img' => $google_url. 'bgr2-1.jpg'),
                            $google_url. '032.jpg' => array('title' => 'Pattern 18', 'img' => $google_url. '032-1.jpg')
                        ), 
                        'default'   => $google_url. 'default-bg.gif',
                    ),
					array(
                        'id'        => 'mg-slider-border',
                        'type'      => 'border',
                        'all'		=> false,
                        'title'     => __('Header Border Option', 'mgps-slider'),
                        'subtitle'  => __('Only color validation can be done on this field type', 'mgps-slider'),
                        'output'    => array('.da-slider'), // An array of CSS selectors to apply this font style to
                        'desc'      => __('This is the description field, again good for additional info.', 'mgps-slider'),
                        'default'   => array(
                            'border-color'  => '#aa4b4b', 
                            'border-style'  => 'solid', 
                            'border-top'    => '8px', 
                            'border-bottom' => '8px',
                            'border-right' => '0px',
                            'border-left' => '0px'
                        )
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'dashicons-format-gallery dashicons',
                'title'     => __('Slides', 'mgps-slider'),
                'heading'   => __('Unlimited Slides.', 'mgps-slider'),
                'desc'      => __('<p class="description"><strong>"We provide two ways for creating slider." 1. </strong> If you want to create slides using "MG Slider" custom post. Then Create slides "MG Slider->Add New". And Use shortcode <strong>[mgps-slider-post].</strong> Or <strong> 2 </strong>Use below Unlimited slides useing below section and use <strong>[mgps-slider]</strong>.</p>', 'mgps-slider'),
                'fields'    => array(
                	array(
					    'id'       => 'mg-mgps-thumb-enable',
					    'type'     => 'switch',
					    'title'    => __('Custom Size:', 'redux-framework-demo', 'mgps-slider'),
					    'subtitle' => __('Do you want to enable thumb size 300 x 225.', 'mgps-slider'),
					    'desc'     => __('NOTE: If you enable this MGPS use 300x225 images for all slides. Else it use default thumbnail size. (To enable this your theme must have "add_theme_support" enable.)', 'mgps-slider'),
					    'default'  => false,
					),
					array(
                        'id'        => 'mg-slider-slides',
                        'type'      => 'slides',
                        'title'     => __('Add Slides', 'mgps-slider'),
                        'subtitle'  => __('Add unlimited slides. Use 300 x 225 [WidthxHeight] Resolution images for better use.', 'mgps-slider'),
                        'desc'      => __('For this slides Use shortcode <strong>[mgps-slider]</strong>. OR Create slides using "MG Slider->Add New" and use <strong>[mgps-slider-post]</strong>', 'mgps-slider'),
                        'placeholder'   => array(
                            'title'         => __('MENDATORY field! Please enter some title.', 'mgps-slider'),
                            'description'   => __('Description Here', 'mgps-slider'),
                            'url'           => __('Give us a link!', 'mgps-slider'),
                        ),
                    ),

                )
            );



            $this->sections[] = array(
                'icon'      => 'dashicons dashicons-editor-quote',
                'title'     => __('Title', 'mgps-slider'),
                'heading'   => __('Slide TITLE typography settings.', 'mgps-slider'),
                'desc'      => __('<p class="description">Choose best font face, sice, color which is suitable for your theme.</p>', 'mgps-slider'),
                'fields'    => array(
	                array(
                        'id'        => 'mg-slider-title',
                        'type'      => 'typography',
                        'compiler'	=> array('.da-slide h2'),
                        'title'     => __('Title Font', 'mgps-slider'),
                        'subtitle'  => __('Specify the body font properties.', 'mgps-slider'),
                        'default'     => array(
					        'color'       => '#fff',
					        'font-family' => 'Verdana',
					        'font-size'   => '60px'
					    ),
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'dashicons-before dashicons-testimonial',
                'title'     => __('Contents', 'mgps-slider'),
                'heading'   => __('Slide CONTENTS typography settings.', 'mgps-slider'),
                'desc'      => __('<p class="description">Choose best font face, sice, color which is suitable for your theme.</p>', 'mgps-slider'),
                'fields'    => array(
	                array(
                        'id'        => 'mg-slider-contents',
                        'type'      => 'typography',
                        'compiler'	=> array('.da-slide p'),
                        'title'     => __('Title Font', 'mgps-slider'),
                        'subtitle'  => __('Specify the body font properties.', 'mgps-slider'),
                        'default'     => array(
					        'color'       => '#cda0a0',
					        'font-family' => 'Trebuchet MS',
					        'font-size'   => '18px'
					    ),
                    ),
                )
            );

            $this->sections[] = array(
                'icon'      => 'dashicons dashicons-editor-code',
                'title'     => __('Navigation Arrows', 'mgps-slider'),
                'heading'   => __('Upload NAVIGATION image.', 'mgps-slider'),
                'desc'      => __('<p class="description">You can upload your own navigation images.</p>', 'mgps-slider'),
                'fields'    => array(
					array(         
						'id'       => 'mg-slider-icons',
					    'type'     => 'background',
                        'compiler'	=> array('.da-arrows span:after'),
					    'background-color' => false,
					    'background-repeat' => false,
					    'background-attachment' => false,
					    'background-position' => false,
					    'background-size' => false,
					    'preview'	=> false,
					    'preview_media' => true,
					    'title'    => __('Navigation Icons', 'mgps-slider'),
					    'subtitle' => __('upload navigation png if applicable.', 'mgps-slider'),
					    'desc'     => __('Choose 40px x 20px Transparent [.png] image for better usage..', 'mgps-slider'),
					    'default'  => array(
					        'background-image' => $google_url. 'arrows.png',
					    ),
                	),
                	array(
					    'id'       => 'mg-slider-arrow-color',
					    'type'     => 'color',
					    'mode'	   => 'background',
					    'title'    => __('Arrow & Bullets Background Colors', 'mgps-slider'),
					    'subtitle' => __('Pick a background color for the arrows & bullets (default: #e44444).', 'mgps-slider'),
					    'default'  => '#e44444',
					    'validate' => 'color',
                        'compiler'	=> array('.da-arrows span', '.da-dots span'),
					),
                )
            );

            $this->sections[] = array(
                'title'     => __('Import / Export', 'mgps-slider'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'mgps-slider'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'mgps-slider'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'mgps-slider')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'mgps-slider'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'mgps-slider')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'mgps-slider');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'mgps',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => 'MG Parallax Slider',     // Name that appears at the top of your panel
                'display_version'   => '1.0.',  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('MGPS Options', 'mgps-slider'),
                'page_title'        => __('MGPS Options', 'mgps-slider'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'f7af13ad265a4eb7c5559503effb039534cd6a19', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => false,                    // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => 'mgps_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/maheshwaghmare/mg-parallax-slider',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/mgwebthemes',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://twitter.com/mwaghmare7',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://in.linkedin.com/in/mwaghmare7',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );

            

            $this->args['intro_text'] = __('<p>Welcome to <strong>MG Parallax Slider</strong>...!</p>', 'mgps-slider');
            

            // Add content after the form.
            $this->args['footer_text'] = __('<p>Thanks for using "MG Parallax Slider". Special Thanks to Redux.</p>', 'mgps-slider');
        }

    }
    
    global $reduxConfig;
    $reduxConfig = new mgps_framework();
}

