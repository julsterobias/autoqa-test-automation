<?php 
/**
 * 
 * 
 * cuato_utils
 * @since 1.0.0
 * 
 * 
 */

namespace cauto\includes;

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

class cauto_utils 
{


    public string $slug             = 'codecorun-automation';

    public string $nonce            = 'cauto-S3cR3t_KEY';

    public string $settings_page    = 'test-tools';

    public string $flow_steps_key   = '_cauto_test_automation_steps';

    /**
	*
	*
	* get_view
	* render content
	* @param $file , $data
	* @return void
	*
	*
	* @since 1.0.0
	*/
	public function get_view($file, $data = [])
	{
	    if (!$file)
				return;

		$other = null;

		if (strpos($file,'.php') === false)
			$file = $file.'.php';

		if (isset($data['path'])) {
			$other = $data['path'].'/';
		}

		$plugin_folder = explode('/',CAUTO_PLUGIN_URL);
		$plugin_folder = array_filter($plugin_folder);

		$path = get_stylesheet_directory().'/'.end($plugin_folder).'/'.$other.'views';

		if (file_exists($path.'/'.$file)) {
			include $path.'/'.$file;
		}else{
			include CAUTO_PLUGIN_PATH.$other.'/views/'.$file;
		}
	}


    /**
     * 
     * 
     * prepare_attr
     * prepare html attribute
     * @since 1.0.0
     * @param array
     * @return array
     * 
     * 
     */
    public function prepare_attr($data = [])
    {
        if (empty($data)) return;

        foreach ($data as $i => $controls) {
            if (!empty($controls)) {
                foreach ($controls as $ii => $control) {
                    if ($ii === 'attr' && is_array($control)) {
                        $composed_string = '';
                        foreach ($control as $iii => $attr) {
                            $attr = (is_array($attr))? implode(' ', $attr) : $attr;
                            $composed_string .= sprintf("%s=\"%s\" ", $iii, $attr);
                        }
                        $data[$i]['iattr'] = $composed_string; 
                    }
                }
            }
        } 
        return $data;
    }


    /**
     * 
     * 
     * prepare_value
     * @since 1.0.0 
     * 
     * 
     */
    public function prepare_value($step_setttings = [], $data =[], $type = null)
    {
        if (empty($step_setttings) || empty($data) || !$type) return;
        

        $value = '';

        if (!empty($data['value'])) {
            foreach ($data['value'] as $saved_data) {
                if ($saved_data->class === $step_setttings['attr']['class'] && $saved_data->id === $step_setttings['attr']['id'] && $saved_data->field === $type) {
                    $value = $saved_data->value;
                    break;
                }
            }
        }

        return $value;

    }

    /**
     * 
     * prepare_run_url
     * 
     */

     public function prepare_run_url($flow_id)
     {
        $get_steps = get_post_meta($flow_id, $this->flow_steps_key, true);
        $url = 'javascript:void(0);';
        //check URL for start
        if (isset($get_steps[0])) {
            if ($get_steps[0]['step'] === 'start' && isset($get_steps[0]['record'][0]['value'])) {

                if (strpos($get_steps[0]['record'][0]['value'], get_admin_url()) !== false) {
                    $url = $get_steps[0]['record'][0]['value'].'&run=1';
                } else {
                    $url = $get_steps[0]['record'][0]['value'].'?flow='.$flow_id.'&run=1';
                }
            }
        }

        return $url;

     }
}
?>