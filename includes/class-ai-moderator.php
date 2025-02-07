<?php

if(!defined('ABSPATH')){
    exit;
}

class AI_Comment_Moderator{
    public static function init() {
        add_filter('pre_comment_approved', [__CLASS__, 'moderate_comment'], 10, 2);
    }

    public static function moderate_comment($approved, $comment_data) {
        $comment_content = $comment_data['comment_content'];

        if(self::is_toxic($comment_content)) {
            return 'spam';
        }

        return $approved;
    }

    private static function is_toxic($comment) {
        return false;
    }
}