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

class cauto_test_runners extends cauto_utils
{

    private string $runner_name         = '';

    private string $runner_status       = 'publish';

    private mixed $id                   = null;

    private int $flow_id                = 0;

    private array $flow_steps           = [];

    private string $meta_flow_id_key    = '_flow_id';

    private string $meta_flow_steps_key = '_flow_steps';

    public function __construct($id = null)
    {   
        if ($id) {
            $this->id = $id;
        }
    }

    public function set_flow_id($id)
    {
        $this->flow_id = $id;
    }

    public function set_name($name = null)
    {
        $this->runner_name = $name;
    }

    public function set_status($status = null)
    {
        $this->runner_status = $status;
    }

    public function set_steps($steps = [])
    {
        $this->flow_steps = $steps;
    }

    public function get_name()
    {
        return $this->runner_name;
    }

    public function get_status()
    {
        return $this->runner_status;
    }

    public function get_flow_id()
    {
        return $this->flow_id;
    }

    public function get_steps()
    {
        return $this->flow_steps;
    }

    public function save()
    {
        //create flow
        $post_data = array(
            'post_title'    => $this->get_name(),
            'post_status'   => $this->get_status(),
            'post_type'     => $this->runner_slug
        );

        $metas = [];

        if ( $this->get_flow_id() > 0 ) {
            $metas[$this->meta_flow_id_key] = $this->get_flow_id();
        }

        if ( !empty( $this->get_steps() ) ) {
            $metas[$this->meta_flow_steps_key] = $this->get_steps();
        }

        if (!empty($metas)) {
            $post_data['meta_input'] = $metas;
        }   

        return wp_insert_post($post_data);
    }

    public function get_runners($other_args = [])
    {
        $args = [
            'posts_per_page'    => -1,
            'post_type'         => $this->runner_slug,
            'post_status'       => $this->get_status(),
            'order_by'          => 'date',
            'order'             => 'DESC'
        ];

        if (!empty($this->id) && is_array($this->id)) {
            $args['post__in']   = $this->id;
        }

        if (!empty($other_args)) {
            $args = array_merge($args, $other_args);
        }

        return get_posts($args);
        
    }

    public function get_runner_steps()
    {
        if ($this->get_flow_id() === 0) return;

        $args = [
            'posts_per_page'    => -1,
            'post_type'         => $this->runner_slug,
            'post_status'       => $this->get_status(),
            'meta_key'          => [
                [
                    'key'       => $this->meta_flow_id_key,
                    'value'     => $this->get_flow_id(),
                    'compare'   => '='
                ]
            ],
            'order_by'          => 'date',
            'order'             => 'DESC'   
        ];

        $steps      = [];
        $runners    = get_posts($args);

        if (!empty($runners)) {
            foreach ($runners as $runner) {
                $run_steps = get_post_meta($runner->ID, $this->flow_steps_key, true);
                $steps[] = [
                    'ID'        => $runner->ID,
                    'name'      => $runner->post_title,
                    'date'      => get_the_date('', $runner->ID),
                    'steps'     => $run_steps
                ];
            }
        }

        return $steps;

    }

    public function update_runner_steps($index = null, $result = [])
    {
    
        if ($this->id > 0 && $index >= 0 && $this->get_flow_id() > 0 && !empty($result)) {

            $flow       = get_post_meta($this->id, $this->meta_flow_id_key, true);

            if ((int)$flow !== (int)$this->get_flow_id()) return;

            $steps      = get_post_meta($this->id, $this->meta_flow_steps_key, true);

            if (isset($steps[$index])) {
                $steps[$index]['result'] = $result;
                error_log($index);
                update_post_meta($this->id, $this->meta_flow_steps_key, $steps);
            } 
            
        }
    }
}