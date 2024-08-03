<?php 
/**
 * 
 * 
 * test_automation
 * @since 1.0.0
 * 
 * 
 */

namespace cauto\includes;
use cauto\includes\cauto_utils;

if ( !function_exists( 'add_action' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( !function_exists( 'add_filter' ) ){
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

class cauto_test_automation extends cauto_utils
{

    private string $test_name       = '';

    private string $test_status     = 'publish';

    private bool $stop_on_error     = false;

    private array $id               = [];


    public function __construct($id = [])
    {   
        if (!empty($id)) {
            $this->id = $id;
        }
    }

    public function set_name($name = null)
    {
        $this->test_name = $name;
    }

    public function set_status($status = null)
    {
        $this->test_status = $status;
    }

    public function set_stop_on_error($stop_on_error)
    {
        $this->stop_on_error = $stop_on_error;
    }

    public function get_name()
    {
        return $this->test_name;
    }

    public function get_status()
    {
        return $this->test_status;
    }

    public function get_stop_on_error()
    {
        return $this->stop_on_error;
    }

    public function save_flow()
    {
        //create flow
        $post_data = array(
            'post_title'    => $this->get_name(),
            'post_status'   => $this->get_status(),
            'post_type'     => $this->slug
        );

        if ($this->get_stop_on_error()) {
            $post_data['meta_input'] = [
                '_stop_on_error' => $this->get_stop_on_error()
            ];
        }

        return wp_insert_post($post_data);
    }

    public function get_flows($other_args = [])
    {

        $args = [
            'posts_per_page'    => -1,
            'post_type'         => $this->slug,
            'post_status'       => $this->get_status(),
            'order_by'          => 'date',
            'order'             => 'DESC'
        ];

        if (!empty($this->id)) {
            $args['post__in']   = $this->id;
        }

        if (!empty($other_args)) {
            $args = array_merge($args, $other_args);
        }

        return get_posts($args);
        
    }
}