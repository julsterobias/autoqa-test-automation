<?php 
/**
 * 
 * 
 * cauto_test_automation
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

    private string $runner_name         = '';

    private string $runner_status       = 'publish';

    private array $id                   = [];


    public function __construct($id = [])
    {   
        if (!empty($id)) {
            $this->id = $id;
        }
    }

    public function set_name($name = null)
    {
        $this->runner_name = $name;
    }

    public function set_status($status = null)
    {
        $this->runner_status = $status;
    }

    public function get_name()
    {
        return $this->runner_name;
    }

    public function get_status()
    {
        return $this->runner_status;
    }

    public function save_flow()
    {
        //create flow
        $post_data = array(
            'post_title'    => $this->get_name(),
            'post_status'   => $this->get_status(),
            'post_type'     => $this->slug
        );

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