/**
 * 
 * 
 * cauto_default_delay_runner_for
 * 
 * 
 */

var cauto_default_delay_runner_for = (params = null) => {

    
    if (!params || !Array.isArray(params)) {
        return [
            {
                status: 'failed',
                message: cauto_translable_labels['The step is not configured']
            }
        ];
    }

    for (let x in params) {
        if (typeof params[x].value === 'undefined') {
            return [
                {
                    status: 'failed',
                    message: cauto_translable_labels['The step is not configured']
                }
            ];
            break;
        }
    }

    let delay       = (params[0].value)? parseInt(params[0].value) : 0;
    let toseconds   = delay * 1000;

    if (typeof cauto_runner_delay !== 'undefined') {
        cauto_runner_delay = cauto_runner_delay + toseconds;
    }

    return [
        {
            status: 'passed',
            message: cauto_translable_labels['Runner is delayed for'] + ' ' + toseconds + 'ms'
        }
    ];

}