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
use cauto\includes\cauto_test_runners;

session_start();


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

    private string $test_name               = '';

    private string $test_status             = 'publish';

    private bool $stop_on_error             = false;

    private mixed $id                       = null;

    private string $session_runner_name     = '_cauto_running_flows';

    private string $stop_on_error_key       = '_stop_on_error';


    public function __construct($id = null)
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

    public function set_running_flow($running_flow = [], $update_after_stop = false)
    {
        //store multiple running flows
        //clean them after the runner completed

        $expiration_time = 24 * HOUR_IN_SECONDS;

        if (empty($running_flow)) {
            if ($update_after_stop) {
                set_transient( $this->session_runner_name, $running_flow, $expiration_time);
            } else {
                delete_transient($this->session_runner_name);
            }
            
        } else {
            $running_saved_flow = get_transient($this->session_runner_name);
            $running_saved_flow = ($running_saved_flow)? $running_saved_flow : [];
            $running_saved_flow[$running_flow['flow_id']] = $running_flow['runner_id'];
            set_transient( $this->session_runner_name, $running_saved_flow, $expiration_time);
        }
        
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
        if (false  === $this->stop_on_error && $this->id) {
            return get_post_meta($this->id, $this->stop_on_error_key, true);
        }
        return $this->stop_on_error;
    }

    public function get_running_flow()
    {
        return get_transient($this->session_runner_name);
    }

    public function save_flow($flow_id = 0)
    {
        //create flow
        $post_data = array(
            'post_title'    => $this->get_name(),
            'post_status'   => $this->get_status(),
            'post_type'     => $this->slug
        );

        if ($this->get_stop_on_error()) {
            $post_data['meta_input'] = [
                $this->stop_on_error_key => $this->get_stop_on_error()
            ];
        } else {
            $saved_stop_on_error = get_post_meta($flow_id, $this->stop_on_error_key, true);
            if ($saved_stop_on_error) {
                delete_post_meta($flow_id, $this->stop_on_error_key);
            }
        }
        
        if ($flow_id > 0) {
            $post_data['ID'] = $flow_id;
            $res = wp_update_post($post_data); // update
            if ($res) {
                return $flow_id;
            }
        } else {
            return wp_insert_post($post_data); // insert
        }

    }

    public function get_flows($other_args = [])
    {

        $args = [
            'posts_per_page'    => -1,
            'post_type'         => $this->slug,
            'post_status'       => $this->get_status(),
            'orderby'           => 'post_title',
            'order'             => 'ASC'
        ];

        if (!empty($this->id)) {
            if (is_array($this->id)) {
                $args['post__in']   = $this->id;
            } else {
                $args['p']   = $this->id;
            }   
        }

        if (!empty($other_args)) {
            $args = array_merge($args, $other_args);
        }

        return get_posts($args);
        
    }


    public function get_flow()
    {
        $flow_details = [];
        if ($this->id) {
            $flow = get_post($this->id);
            if ($flow) {
                $flow_details = [
                    'flow_data'         => $flow,
                    'stop_on_error'     => get_post_meta($this->id, $this->stop_on_error_key, true)
                ];
            }
        }

        return $flow_details;
    }


    public function start($runner_id = 0)
    {
        $this->set_running_flow(
            [
                'flow_id'     => $this->id,
                'runner_id'   => $runner_id
            ]
        );

        return ($this->id && $runner_id > 0)? true : false;

    }

    public function stop()
    {

        if ($this->id) {
            $running_flows = $this->get_running_flow();
            unset($running_flows[$this->id]);
            $this->set_running_flow($running_flows, true);
        }

        return ($this->id)? true : false;
    }

}