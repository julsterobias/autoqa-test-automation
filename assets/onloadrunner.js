/**
 * 
 * autoQA test automation
 * @since 1.0.0
 * script to detect if flow is running or not.
 * 
 */
var cauto_is_running_flow   = false;
var cauto_running_flow_data = {}; 
if (window.frameElement) {
    let frame = window.frameElement;
    let frame_class = frame.className;
    let flow_id = frame.dataset.flowId;
    let runner_id = frame.dataset.runnerId;
    if (frame_class === 'cauto-runner-area-frame') {
        cauto_is_running_flow = true;
        cauto_running_flow_data['flow_id']      = flow_id;
        cauto_running_flow_data['runner_id']    = runner_id;
    }
}