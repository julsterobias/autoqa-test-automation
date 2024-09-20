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


    public string $slug                 = 'autoqa-automation';

    public string $runner_slug          = 'autoqa-runner';

    public string $nonce                = 'cauto-S3cR3t_KEY';

    public string $settings_page        = 'cauto-test-tools';

    public string $flow_steps_key       = '_cauto_test_automation_steps';

    public string $flow_stop_on_error   = '_stop_on_error';

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
                //I excluded the class from verification 
                //commented code ---> $saved_data->class === $step_setttings['attr']['class'] && 
                //does ID and type is enough?
                if ($saved_data->id === $step_setttings['attr']['id'] && $saved_data->field === $type) {
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


    /**
     * 
     * generate_runner_name
     * 
     */
    public function generate_runner_name()
    {
        $adjectives = [
            __("Agile", 'autoqa-test-automation'),
            __("Brave", 'autoqa-test-automation'),
            __("Calm", 'autoqa-test-automation'),
            __("Diligent", 'autoqa-test-automation'),
            __("Eager", 'autoqa-test-automation'),
            __("Fearless", 'autoqa-test-automation'),
            __("Gentle", 'autoqa-test-automation'),
            __("Humble", 'autoqa-test-automation'),
            __("Inquisitive", 'autoqa-test-automation'),
            __("Jovial", 'autoqa-test-automation'),
            __("Keen", 'autoqa-test-automation'),
            __("Luminous", 'autoqa-test-automation'),
            __("Meticulous", 'autoqa-test-automation'),
            __("Noble", 'autoqa-test-automation'),
            __("Optimistic", 'autoqa-test-automation'),
            __("Prudent", 'autoqa-test-automation'),
            __("Radiant", 'autoqa-test-automation'),
            __("Sincere", 'autoqa-test-automation'),
            __("Tenacious", 'autoqa-test-automation'),
            __("Vibrant", 'autoqa-test-automation')
        ];
        $adjectives_index = rand(0, 19);

        $person_nouns = [
            __("Runner", 'autoqa-test-automation'),
            __("Diver", 'autoqa-test-automation'),
            __("Killer", 'autoqa-test-automation'),
            __("Driver", 'autoqa-test-automation'),
            __("Builder", 'autoqa-test-automation'),
            __("Teacher", 'autoqa-test-automation'),
            __("Leader", 'autoqa-test-automation'),
            __("Designer", 'autoqa-test-automation'),
            __("Writer", 'autoqa-test-automation'),
            __("Speaker", 'autoqa-test-automation'),
            __("Painter", 'autoqa-test-automation'),
            __("Actor", 'autoqa-test-automation'),
            __("Manager", 'autoqa-test-automation'),
            __("Coder", 'autoqa-test-automation'),
            __("Helper", 'autoqa-test-automation'),
            __("Player", 'autoqa-test-automation'),
            __("Researcher", 'autoqa-test-automation'),
            __("Inventor", 'autoqa-test-automation'),
            __("Explorer", 'autoqa-test-automation'),
            __("Fighter", 'autoqa-test-automation')
        ];
        $person_nouns_index = rand(0, 19);

        $human_animal_nouns = [
            __("Dog", 'autoqa-test-automation'),
            __("Man", 'autoqa-test-automation'),
            __("Astronaut", 'autoqa-test-automation'),
            __("Cat", 'autoqa-test-automation'),
            __("Woman", 'autoqa-test-automation'),
            __("Lion", 'autoqa-test-automation'),
            __("Elephant", 'autoqa-test-automation'),
            __("Tiger", 'autoqa-test-automation'),
            __("Soldier", 'autoqa-test-automation'),
            __("Scientist", 'autoqa-test-automation'),
            __("Giraffe", 'autoqa-test-automation'),
            __("Engineer", 'autoqa-test-automation'),
            __("Shark", 'autoqa-test-automation'),
            __("Bird", 'autoqa-test-automation'),
            __("Human", 'autoqa-test-automation'),
            __("Pilot", 'autoqa-test-automation'),
            __("Bear", 'autoqa-test-automation'),
            __("Dolphin", 'autoqa-test-automation'),
            __("Monkey", 'autoqa-test-automation'),
            __("Cowboy", 'autoqa-test-automation')
        ];
        $human_animal_nouns_index = rand(0, 19);

        return $adjectives[$adjectives_index].' '.$human_animal_nouns[$human_animal_nouns_index].' '.$person_nouns[$person_nouns_index];
    }
}
?>