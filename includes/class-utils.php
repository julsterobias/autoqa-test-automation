<?php 
/**
 * 
 * 
 * class_utils
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

class class_utils 
{


    public string $slug = 'codecorun-test-automation';

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
}
?>