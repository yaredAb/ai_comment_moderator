<?php

/**
 * Plugin Name: AI Comment Moderator
 * Description: Automatically moderates WordPress comments using AI.
 * Version: 1.0.0
 * Author: Yared Sebsbe
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AI_COMMENT_MODERATION_VERSION', '1.0.0');
define('AI_COMMENT_MODERATION_PATH', plugin_dir_path(__FILE__));
define('AI_COMMENT_MODERATION_URL', plugin_dir_url(__FILE__));

require_once AI_COMMENT_MODERATION_PATH . 'includes/class-ai-moderator.php';
require_once AI_COMMENT_MODERATION_PATH . 'admin/settings-page.php';

// Initialize plugin
AI_Comment_Moderator::init();
