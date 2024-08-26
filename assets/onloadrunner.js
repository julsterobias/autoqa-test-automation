/**
 * 
 * codecorun test automation
 * @since 1.0.0
 * script to detect if flow is running or not.
 * 
 */
var cauto_is_running_flow=!1;if(window.frameElement){let a=window.frameElement,e=a.className;"cauto-runner-area-frame"===e&&(cauto_is_running_flow=!0)}