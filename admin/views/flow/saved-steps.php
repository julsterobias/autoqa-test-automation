<?php 
if (!empty($data['flow_steps'])):
    foreach ($data['flow_steps'] as $steps):        
?>
    <li class="cauto-steps-el cauto-steps-el-saved cauto-step-group_<?php echo esc_attr($steps['step_group']); ?> cauto-added-step cauto_step_set_wide" >
        <div class="cauto-step-element" data-step="<?php echo esc_attr($steps['step']); ?>"><?php echo $steps['icon'].$steps['step_label']; ?><span class="cauto_describe_step_label"> <b><?php echo esc_html($steps['describe_label']); ?></b></span></div>
    <input type="hidden" value="<?php echo esc_attr(json_encode($steps['record'])); ?>"></li>
<?php 
    endforeach;
endif;
?>