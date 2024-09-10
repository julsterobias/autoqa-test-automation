/**
 * 
 * cauto_default_click_step
 * @since 1.0.0
 * 
 * 
 */

var cauto_default_click_step = (params = null) => {

    if (!params) {
        return [
            {
                status: 'failed',
                message: 'Error:' + cauto_runner.unconfigured_msg
            }
        ];
    }

    return [
        {
            status: 'passed',
            message: "Test click"
        }
    ];
}