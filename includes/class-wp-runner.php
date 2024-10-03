<?php 
/**
 * 
 * 
 * cauto_wp_runner
 * @since 1.0.0
 * 
 * 
 */

namespace cauto\includes;
use cauto\includes\cauto_utils;

if ( !function_exists( 'add_action' ) ) 
{
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( !function_exists( 'add_filter' ) )
{
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

class cauto_wp_runner extends cauto_utils
{

    public function __construct()
    {
        add_action('wp_ajax_cauto_step_check_meta_key', [$this, 'check_meta_value']);
    }

    public function check_meta_value()
    {
        if ( !wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
            echo json_encode(
                [
                    'status'    => 'failed',
                    'message'   => __('Invalid nonce please contact developer or clear your cache', 'autoqa-test-automation')
                ]
            );
            exit();
        }

        $post       = (isset($_POST['wp_post']))? sanitize_text_field($_POST['wp_post']) : null;
        $key        = (isset($_POST['key']))? sanitize_text_field($_POST['key']) : null;
        $condition  = (isset($_POST['condition']))? sanitize_text_field($_POST['condition']) : null;
        $value      = (isset($_POST['value']))? sanitize_text_field($_POST['value']) : null;

        $check_status = [];

        if ($post && $key && $condition && $value) {
            
            if ( is_array($post) ) {
                foreach ($post as $post_data)
                {
                    $check_status = $this->process_check_meta(
                        [
                            'post_data' => $post_data,
                            'key'       => $key,
                            'value'     => $value,
                            'condition' => $condition
                        ]
                    );
                }
            } else {

                $check_status = $this->process_check_meta(
                    [
                        'post_data' => $post,
                        'key'       => $key,
                        'value'     => $value,
                        'condition' => $condition
                    ]
                );

            }

        }

        wp_send_json([
            'status'    => 'success',
            'step'      => $check_status
        ]);

        exit();
    }

    public function get_post_id(string $post = '') {
        if ($post !== '') {
            $post = explode('-', $post);
            $post = trim($post[0]);
        }
        return $post;
    }

    public function check_with_condition($payload = []) {

        if (empty($payload)) {
            return;
        }

        $status     = 'failed';
        $message    = '"'.$payload['received'].'" '.$payload['condition'].' "'.$payload['expected'].'", {matched_text} Expected: "'.$payload['expected'].'" Received: "'.$payload['received'].'"';

        switch ($payload['condition']) {
            case 'is equals to':
                $status = ( ($payload['received'] === $payload['expected']) )? 'passed' : 'failed';
                break;
            case 'is not equals to':
                $status = ( ($payload['received'] !== $payload['expected']) )? 'passed' : 'failed';
                break;
            case 'is contains with':
                $status = ( strpos($payload['received'], $payload['expected']) !== false )? 'passed' : 'failed';
                break;
            case 'is start with':
                $left_part  = strlen($payload['expected']);
                $right_part = substr($payload['received'], 0, $left_part);
                $status = ($payload['expected'] === $right_part)? 'passed' : 'failed';
                break;
            case 'is end with':
                $left_part  = strpos($payload['received'], $payload['expected']);
                $right_part = substr($payload['received'], $left_part, $left_part);
                $status     = ($right_part === $payload['expected'])? 'passed' : 'failed';
                break;
            case 'is less than':
                if (is_numeric($payload['received']) && is_numeric($payload['expected'])) {
                    $payload = $this->process_numeric_data_type($payload);
                    $status = ($payload['received'] < $payload['expected'])? 'passed' : 'failed';
                } else {
                    $message = __('Expected value is invalid for the greater than operation','autoqa-test-automation');
                } 
                break;
            case 'is greater than':
                if (is_numeric($payload['received']) && is_numeric($payload['expected'])) {
                    $payload = $this->process_numeric_data_type($payload);
                    $status = ($payload['received'] > $payload['expected'])? 'passed' : 'failed';
                } else {
                    $message = __('Expected value is invalid for the greater than operation','autoqa-test-automation');
                } 
                break;
            case 'is less than or equal to':
                if (is_numeric($payload['received']) && is_numeric($payload['expected'])) {
                    $payload = $this->process_numeric_data_type($payload);
                    $status = ($payload['received'] <= $payload['expected'])? 'passed' : 'failed';
                } else {
                    $message = __('Expected value is invalid for the greater than operation','autoqa-test-automation');
                } 
                break;
            case 'is greater than or equal to':
                if (is_numeric($payload['received']) && is_numeric($payload['expected'])) {
                    $payload = $this->process_numeric_data_type($payload);
                    $status = ($payload['received'] >= $payload['expected'])? 'passed' : 'failed';
                } else {
                    $message = __('Expected value is invalid for the greater than operation','autoqa-test-automation');
                } 
                break;
            case 'has any':
                $status = (strlen($payload['received']) > 0)? 'passed' : 'failed';
                break;
        }
        
        $message = ($status === 'passed')? str_replace('{matched_text}', 'Matched: 1', $message) : str_replace('{matched_text}', 'Matched: 0', $message) ;

        return [
            'status'    => $status,
            'message'   => ucfirst($message)
        ]; 
    }

    public function process_check_meta($payload = [])
    {

        if (empty($payload)) return;
        

        $id     = $this->get_post_id($payload['post_data']);
        $saved_meta   = get_post_meta($id, $payload['key'], true); 
        if (is_array($saved_meta)) {
            $saved_meta   = json_encode($saved_meta);
        } 
        $saved_meta  = stripslashes($saved_meta);      
        $value  = stripslashes($payload['value']);
        return $this->check_with_condition(
            [
                'received'      => $saved_meta,
                'expected'      => $value,
                'condition'     => $payload['condition']
            ]
        );
    }

    public function process_numeric_data_type($payload = [])
    {
        if (is_float($payload['received'])) {
            $payload['received'] = (float) $payload['received'];
        } else {
            $payload['received'] = (int) $payload['received'];
        }
        if (is_float($payload['expected'])) {
            $payload['expected'] = (float) $payload['expected'];
        } else {
            $payload['expected'] = (int) $payload['expected'];
        }

        return $payload;
    }

}

new cauto_wp_runner();