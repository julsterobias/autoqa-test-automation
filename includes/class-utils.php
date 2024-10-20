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

    public string $settings_key         = 'autoqa-test-automation-options';

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
                            $composed_string .= sprintf("%s=\"%s\" ", esc_attr($iii), esc_attr($attr));
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
        $adjectives_index = wp_rand(0, 19);

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
        $person_nouns_index = wp_rand(0, 19);

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
        $human_animal_nouns_index = wp_rand(0, 19);

        return $adjectives[$adjectives_index].' '.$human_animal_nouns[$human_animal_nouns_index].' '.$person_nouns[$person_nouns_index];
    }

    public function runner_available_variables($flow_id)
    {
        $default_runner_variables = [ 
            __('FullDate', 'autoqa-test-automation'),
            __('Date', 'autoqa-test-automation'),
            __('Day', 'autoqa-test-automation'),
            __('Month', 'autoqa-test-automation'),
            __('Year', 'autoqa-test-automation'),
            __('Time', 'autoqa-test-automation'),
            __('Hour', 'autoqa-test-automation'),
            __('Minute', 'autoqa-test-automation'),
            __('Second', 'autoqa-test-automation'),
            __('UnixTimeStamp', 'autoqa-test-automation')
        ];

        if ($flow_id) {
            //get the steps and append the saved data steps to default variables
            $flow_steps = get_post_meta($flow_id, $this->flow_steps_key, true);
            if ($flow_steps) {

                $get_steps  = cauto_steps::steps();
                $data_steps = []; 
                foreach ($get_steps as $type => $step) {

                    if (!isset($step['group']) || empty($step['group'])) continue;

                    if ($step['group'] === 'data') {
                        $data_steps[] = $type;
                    }
                }
                foreach ($flow_steps as $row_step) {
                    if ( in_array($row_step['step'], $data_steps) ) {
                        array_unshift($default_runner_variables, $row_step['record'][0]['value']);
                    }
                }
                
            }
        }

        return $default_runner_variables;
    }

    public function generate_image ($payload = [])
    {

        if (empty($payload)) return;

        header("Content-Type: image/{$payload['type']}");

        $width      = (isset($payload['width']))? $payload['width'] : 250;
        $height     = (isset($payload['height']))? $payload['height'] : 250;
        $image      = imagecreatetruecolor($width, $height);

        $bgColor = imagecolorallocate($image, 242, 85, 5);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        $text_font_size = 5;
        $text           = "AutoQA";
        $charWidth = imagefontwidth($text_font_size);
        $textWidth =  strlen($text) * $charWidth; 
        $textX = ($width - $textWidth) / 2;
        $textY = ($height - imagefontheight($text_font_size)) / 2 - ($text_font_size * 1.5);
        imagestring($image, $text_font_size, $textX, $textY, $text, $textColor);

        $text_font_size = 5;
        $text_dimension = "$width X $height";
        $charWidth = imagefontwidth($text_font_size);
        $textWidth =  strlen($text_dimension) * $charWidth; 
        $textX = ($width - $textWidth) / 2;
        $textY = ($height - imagefontheight($text_font_size)) / 2 + ($text_font_size * 1.5);
        imagestring($image, $text_font_size, $textX, $textY, $text_dimension, $textColor);

        switch ($payload['type']) {
            case 'png':
                imagepng($image);
                break;
            case 'jpeg':
                imagejpeg($image);
                break;
        }
        imagedestroy($image);

    }

    public function generate_pdf($data)
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$data['filename'].'.pdf"');

        // Define the basic PDF structure
        $pdf = "%PDF-1.4\n";
        $pdf .= "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n";
        $pdf .= "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n";
        $pdf .= "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << >> >> endobj\n";
        $pdf .= "4 0 obj << /Length 44 >> stream\n";
        $pdf .= "BT\n/F1 12 Tf\n50 730 Td\n(".$data['content'].") Tj\nET\n";
        $pdf .= "endstream endobj\n";
        $pdf .= "xref\n";
        $pdf .= "0 5\n";
        $pdf .= "0000000000 65535 f \n";
        $pdf .= "0000000010 00000 n \n";
        $pdf .= "0000000079 00000 n \n";
        $pdf .= "0000000178 00000 n \n";
        $pdf .= "0000000329 00000 n \n";
        $pdf .= "trailer << /Size 5 /Root 1 0 R >>\n";
        $pdf .= "startxref\n";
        $pdf .= "394\n";
        $pdf .= "%%EOF";

        // Output the PDF content to the browser
        echo $pdf;
    }

}
?>