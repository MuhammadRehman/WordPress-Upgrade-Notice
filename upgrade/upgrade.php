<?php

/**
 * Display upgrade message on plugin update area
 */
Class WP_Upgrade_Notice {

    // plugin slug should be copied from wordpress plugin URL eg. https://wordpress.org/plugins/wp-hooks-finder/
    public $slug = 'wp-hooks-finder';
    public $folder_name = 'wp-hooks-finder';
    public $file_name = 'wp-hooks-finder.php';

    public function __construct() {

        add_action( 'in_plugin_update_message-'.$this->folder_name.'/'.$this->file_name, array( $this, 'add_upgrade_msg'), 10, 3);
        add_action( 'admin_enqueue_scripts', array( $this, 'upgrade_css' ) );
        add_action( 'admin_notices', array( $this, 'upgrade_admin_notice' ) );
        add_action( 'wp_ajax_wpe_dismiss_notice', array( $this, 'dismiss_notice' ) );
    }

    /**
     * Extract readme content
     */
    function fetch_readme_svn() {

        $cache_key = 'wpe_readme_content_'. $this->slug;
        $content = wp_cache_get( $cache_key );
        if ( false === $content ) {

            $content = '';

            $readme_content = wp_remote_get('https://plugins.svn.wordpress.org/'.$this->slug.'/trunk/readme.txt', array(
                'headers' => array(
                    'Accept' => 'application/json',
                )
            ) );

            if( wp_remote_retrieve_response_code( $readme_content ) === 200 ) {
                $content = $readme_content['body'];
            }

            wp_cache_set( $cache_key, $content ); 
        }

        return $content;
    }

    /**
     * Display upgrade message on plugin update area
     */
    function add_upgrade_msg($plugin_data, $new_data, $return = 'Upgrade Notice' ) {

        if ( isset( $plugin_data['update'] ) && $plugin_data['update'] ) {

            $readme_content = $this->fetch_readme_svn();
            
            $readme_content = $this->get_upgrade_notice_from_readme($readme_content, $return);

            // If upgrade notice/admin notice not found
            if( $readme_content == false ) {
                return;
            }

            $upgrade_msg = explode('=', $readme_content);

            $message = $upgrade_msg[1];

            $upgrade_version = preg_replace ("/[^0-9\s]/","", $upgrade_msg[0]);
            $current_version = preg_replace ("/[^0-9\s]/","", PLUGIN_VERSION);
            
            if( $upgrade_version <= $current_version ) {
                return;
            }

            if( $return == 'Admin Notice' ) {
                return $message;
            } else if ( $return == 'Upgrade Notice' ) {
                echo '<div class="wp-uprade-msg">' . $message.'<div>';
            }
        }
    }

    /**
     * Extract upgrade message from readme
     */
    function get_upgrade_notice_from_readme($readme_contents, $return = 'Upgrade Notice' ) {

        // Extract the Upgrade Notice section from the readme.txt file
        preg_match('/== '.$return.' ==\s*=\s*([\s\S]*?)(?===|$)/i', $readme_contents, $matches);

        // Check if there's a match and return the upgrade notice content
        if (isset($matches[1])) {
            return trim($matches[1]);
        }
        
        return false;
    }

    /**
     * Upgrade Notice JS/CSS
     */
    function upgrade_css() {

        wp_enqueue_style( 'upgrade-style', PLUGIN_URL . 'upgrade/upgrade.css', array(), PLUGIN_VERSION);
        wp_enqueue_script( 'upgrade-script', PLUGIN_URL . 'upgrade/upgrade.js', array( 'jquery' ), PLUGIN_VERSION);
    
        wp_localize_script( 'upgrade-script', 'upgrade_notice',
            array(
                '_nonce' => wp_create_nonce("ugrade_delete_notice")
            )
	    );
    }

    function upgrade_admin_notice() {
        
        if( get_option( 'wpe-notice-dismiss_'.$this->slug ) == true ) {
            return;
        }

        $content = $this->add_upgrade_msg( array( 'update' => true ), array(), 'Admin Notice' );

        $html = $this->format_content($content);

        if( !empty( $html ) ) {
            ?>
                <div class="wpe-notice notice notice-warning is-dismissible">
                <p><?php _e( $html, 'sample-text-domain' ); ?></p>
                </div>
            <?php
        }
    }

    function format_content($content) {

        // Regular expression to match text between asterisks
        $pattern_bold = "/\*(.*?)\*/";

        // Replace matched text with HTML bold tags
        $result = preg_replace($pattern_bold, "<strong>$1</strong>", $content);

        // Regular expression to match the link syntax
        $pattern_anchor = "/\[(.*?)\]\((.*?)\)/";

        // Replace matched text with HTML anchor tags
        $result = preg_replace($pattern_anchor, "<a href=\"$2\">$1</a>", $result);

        return $result;
    }

    /**
     * AJAX Callback for dismiss the notice
     */
    function dismiss_notice() {

        if ( !wp_verify_nonce( $_REQUEST['nonce'], "ugrade_delete_notice")) {
            exit("Something wrong!");
        }

        update_option( 'wpe-notice-dismiss_'.$this->slug, true );
    }
}

$WP_Upgrade_Notice = new WP_Upgrade_Notice();
