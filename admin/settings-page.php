<?php

if(!defined('ABSPATH')){
    exit;
}

class AI_Comment_Moderation_Settings {

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_setting']);
    }

    public static function add_settings_page() {
        add_options_page(
            'AI Comment Moderation',
            'AI Moderation',
            'manage_options',
            'ai-comment-moderation',
            [__CLASS__, 'render_settings_page']
        );
    }

    public static function register_setting() {
        register_setting('ai_moderation_settings', 'ai_moderation_api_key');
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>AI Comment Moderation Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('ai_moderation_settings'); ?>
                <?php do_settings_sections('ai_moderation_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="ai_moderation_api_key">Perspective API Key</label></th>
                        <td>
                            <input type="text" id="ai_moderation_api_key" name="ai_moderation_api_key" 
                                   value="<?php echo esc_attr(get_option('ai_moderation_api_key', '')); ?>" 
                                   class="regular-text">
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

AI_Comment_Moderation_Settings::init();