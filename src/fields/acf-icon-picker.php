<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('acf_field_icon_picker') ) :

    class acf_field_icon_picker extends acf_field {

        function __construct( $settings ) {

            $this->name = 'icon-picker';

            $this->label = __('Icon Picker', 'acf-icon-picker');

            $this->category = 'choice';

            $this->defaults = array(
                'initial_value'	=> '',
            );

            $this->l10n = array(
                'error'	=> __('Error!', 'acf-icon-picker'),
            );

            $this->settings = $settings;

            $this->svgPicker = isset($_ENV["ICON_PICKER_SVG"]) ? filter_var($_ENV["ICON_PICKER_SVG"], FILTER_VALIDATE_BOOLEAN) : false;

            if($this->svgPicker) {
                $this->path_suffix = apply_filters( 'acf_icon_path_suffix', 'assets/images/icons/' );
            } else {
                $this->path_suffix = apply_filters( 'acf_icon_path_suffix', 'assets/fonts/icomoon/' );
            }

            $this->path = apply_filters( 'acf_icon_path', $this->settings['path'] ) . $this->path_suffix;

            $this->url = apply_filters( 'acf_icon_url', $this->settings['url'] ) . $this->path_suffix;

            $priority_dir_lookup = get_stylesheet_directory() . '/' . $this->path_suffix;

            if ( file_exists( $priority_dir_lookup ) ) {
                $this->path = $priority_dir_lookup;
                $this->url = get_stylesheet_directory_uri() . '/' . $this->path_suffix;
            }

            $this->icons = array();

            if($this->svgPicker) {
                $files = array_diff(scandir($this->path), array('.', '..'));
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) == 'svg') {
                        $exploded = explode('.', $file);
                        $icon = array(
                            'name' => $exploded[0],
                            'icon' => $file
                        );
                        array_push($this->icons, $icon);
                    }
                }
            }

            parent::__construct();
        }

        function BreakCSS($css)
        {
            $results = array();
            preg_match_all('/(?ims)([a-z0-9\s\,\.\:#_\-@*()\[\]"=]+)\{([^\}]*)\}/', $css, $arr);
            foreach ($arr[0] as $i => $x)
            {
                $selector = trim($arr[1][$i]);
                $selector = str_replace('.', '', $selector);
                if(!empty(preg_match("/:before/",$selector,$m)) && empty(preg_match("/path/",$selector,$m))){
                    $selector = str_replace(':before', '', $selector);
                    $results[] = $selector ;
                }
            }
            return $results;
        }

        function render_field_settings( $field ) {
            acf_render_field_setting( $field, array(
                'label'			=> __('Icônes par défaut'),
                'type'			=> 'text',
                'name'			=> 'icons',
            ));
        }

        function render_field( $field ) {
            $icons = !empty($field['icons']) ? $field['icons'] : '';
            $input_icon = $field['value'] != "" ? $field['value'] : $field['initial_value'];
            ?>
            <div class="acf-icon-picker">
                <div class="acf-icon-picker__img">
                    <?php
                    if ($input_icon) {
                        echo '<div class="acf-icon-picker__icon">';
                        if($this->svgPicker) {
                            $svg = get_stylesheet_directory_uri() . '/' . $this->path_suffix . $input_icon . '.svg';
                            echo '<img src="'.$svg.'" alt=""/>';
                        } else {
                            echo '<i class="'. $input_icon .'"></i>';
                        }
                        echo '</div>';
                    }else{
                        echo '<div class="acf-icon-picker__icon">';
                        echo '<span class="acf-icon-picker__icon--span">Ajouter</span>';
                        echo '</div>';
                    }
                    ?>
                    <input type="hidden" readonly class="icon-value" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($input_icon) ?>"/>
                    <input type="hidden" readonly class="icons-list" value='<?php echo json_encode($icons) ?>' />
                </div>
                <?php if ( $field['required' ] == false ) { ?>
                    <span class="acf-icon-picker__remove">
						Supprimer
					</span>
                <?php } ?>
            </div>
            <?php
        }

        function input_admin_enqueue_scripts() {

            $url = $this->settings['url'];
            $version = $this->settings['version'];

            if($this->svgPicker) {
                wp_register_script( 'acf-input-icon-picker', "{$url}/src/assets/js/input-svg.js", array('acf-input'), $version );
                wp_enqueue_script('acf-input-icon-picker');

                wp_localize_script( 'acf-input-icon-picker', 'iv', array(
                    'path' => $this->url,
                    'svgs' => $this->icons,
                    'no_icons_msg' => sprintf( esc_html__('Pour ajouter des icônes (svg), sauvegarder le contenu de votre dossier images dans /%s de votre thème.', 'acf-icon-picker'), $this->path_suffix)
                ) );
            }
            else {
                $css_url = $this->path . 'style.css';

                if(!file_exists($css_url)) {
                    return;
                }

                $css = file_get_contents($css_url);

                $this->icons = self::BreakCSS($css);

                wp_register_script( 'acf-input-icon-picker', "{$url}/src/assets/js/input.js", array('acf-input'), $version );
                wp_enqueue_script('acf-input-icon-picker');

                wp_localize_script( 'acf-input-icon-picker', 'iv', array(
                    'path' => $this->url,
                    'icons' => $this->icons,
                    'no_icons_msg' => sprintf( esc_html__('Pour ajouter des icônes, sauvegarder le contenu de votre dossier icomoon dans /%s de votre thème.', 'acf-icon-picker'), $this->path_suffix)
                ) );
            }

            wp_register_style( 'acf-input-icon-picker', "{$url}/src/assets/css/input.css", array('acf-input'), $version );
            wp_register_style( 'acf-input-icon-picker-icomoon', "{$this->url}/style.css", array('acf-input'), $version );
            wp_enqueue_style('acf-input-icon-picker');
            wp_enqueue_style('acf-input-icon-picker-icomoon');
        }
    }
    new acf_field_icon_picker( $this->settings );

endif;

?>
