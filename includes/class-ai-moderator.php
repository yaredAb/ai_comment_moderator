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

        $api_key = get_option('ai_moderation_api_key');
        if(empty($api_key)) {
            error_log("empty api key");
            return false;
        }
        error_log("api key - " . $api_key);

        //open ai request
        $response = self::send_to_perspective($comment, $api_key);

        return $response['toxicity'] ?? false;
    }

    private static function send_to_perspective($comment, $api_key) {
    
        $url = 'https://commentanalyzer.googleapis.com/v1alpha1/comments:analyze?key=' .$api_key;
        
        //prepare for request
        $body = json_encode([
            'comment' => [
                'text' => $comment
            ],
            'requestedAttributes' => [
                'TOXICITY' => new stdClass()
            ]
        ]);

        $response = wp_remote_post($url, [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $body
        ]);

        if(is_wp_error($response)) {
            error_log("error with google perspective api request". $response->get_error_message());
            return ['toxicity' => false];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Log the response for debugging
        error_log('Toxicity Score: ' .$data['attributeScores']['TOXICITY']['summaryScore']['value']);
        error_log('The Comment: ' .$comment);

        // Check the toxicity score
        if (isset($data['attributeScores']['TOXICITY']['summaryScore']['value'])) {
            $toxicity_score = $data['attributeScores']['TOXICITY']['summaryScore']['value'];
            // If toxicity score is higher than a threshold (e.g., 0.7), mark it as toxic
            $toxicity = $toxicity_score > 0.7; // Adjust the threshold based on your preference
            return ['toxicity' => $toxicity];
        } else {
            error_log('Google Perspective response does not contain valid toxicity data.');
            return ['toxicity' => false];
        }
    }
}