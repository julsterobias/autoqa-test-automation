<?php 
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
?>
<?php 
if (!empty($data['data']['fields'])): 
    foreach ($data['data']['fields'] as $field):  
        switch($field['field']):  
            case 'input':
                $value = $this->prepare_value($field, $data, $field['field']);
?>
                <div class="cauto-ui-wrapper">
                    <div class="cauto-input-wrapper">
                        <?php if ($field['attr']['type'] === 'checkbox'): ?>  
                        <label>
                            <input <?php echo htmlspecialchars_decode(esc_attr($field['iattr'])); ?> <?php echo ($value)? 'checked' : null; ?>> <?php echo esc_html($field['label']); ?>
                        </label>
                        <?php else: ?>
                        <label><?php echo esc_html($field['label']); ?>
                            <?php 
                                if ($field['attr']['type'] === 'range') {
                                    $value = ($value)? $value : 500;
                                }
                            ?>
                            <input <?php echo htmlspecialchars_decode(esc_attr($field['iattr'])); ?> value="<?php echo esc_attr($value); ?>">
                        </label>
                            <?php if (isset($field['help-text'])): ?>
                                <span class="cauto-inline-tip"><?php echo esc_attr($field['help-text']); ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($field['attr']['type'] === 'range'): ?>  
                            <span class="cauto-input-range-value"><span><?php echo ($value)? esc_html($value) : 500; ?></span>px</span>
                        <?php endif; ?>
                    </div>
                </div>
<?php
                break;
            case 'textarea':
                $value = $this->prepare_value($field, $data, $field['field']);
?>
                <div class="cauto-ui-wrapper">
                    <div class="cauto-input-wrapper">
                        <label><?php echo esc_html($field['label']); ?>
                            <textarea <?php echo htmlspecialchars_decode(esc_attr($field['iattr'])); ?>><?php echo esc_attr($value); ?></textarea>
                            <?php if (isset($field['help-text'])): ?>
                                <span class="cauto-inline-tip"><?php echo esc_attr($field['help-text']); ?></span>
                            <?php endif; ?>
                        </label>
                    </div>
                </div>
<?php
            break;
            case 'select':
                $selected               = $this->prepare_value($field, $data, $field['field']);
                $inter_act_properties   = (isset($field['field-interact']))? esc_attr(wp_json_encode($field['field-interact'])) : null;
                $el_interaction         = ($inter_act_properties)? $inter_act_properties : null;
                $select2                = null;
                if (isset($field['select2'])) {
                    if (is_array($field['select2'])) {
                        $select2 = wp_json_encode($field['select2']); 
                    }
                }
?>
            <div class="cauto-ui-wrapper">
                <div class="cauto-select-wrapper"><?php if (isset($field['select2_allow_clear'])): ?><span class="cauto-clear-select2"><?php esc_html_e('Clear', 'autoqa') ?></span><?php endif; ?>
                    <label><?php echo esc_html($field['label']); ?>
                        <select <?php echo htmlspecialchars_decode(esc_attr($field['iattr'])); ?> data-interact="<?php echo esc_attr($el_interaction); ?>"  data-select-source="<?php echo esc_attr($select2); ?>">
                            <?php if (!empty($field['options'])): 
                                foreach ($field['options'] as $value => $label):    
                            ?>
                                <option value="<?php echo esc_attr($value) ?>" <?php echo ($selected === $value)? 'selected' : null; ?>><?php echo  esc_html($label); ?></option>
                            <?php 
                                endforeach;
                            endif; ?>
                            <?php if ($select2 && !is_array($selected) && $selected): ?>
                                <option value="<?php echo esc_attr($selected); ?>" selected><?php echo esc_attr($selected); ?></option>
                            <?php elseif ($select2 && is_array($selected)): 
                                foreach ($selected as $select_value):    
                            ?>
                                <option value="<?php echo esc_attr($select_value); ?>" selected><?php echo esc_attr($select_value); ?></option>
                            <?php 
                                endforeach;
                            endif; ?>
                        </select>
                    </label>
                    <?php if (isset($field['help-text'])): ?>
                        <span class="cauto-inline-tip"><?php echo esc_attr($field['help-text']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
<?php
                break;

            case 'toggle':
?>
            <div class="cauto-ui-wrapper">
                <div class="cauto-toggle-wrapper">
                    <input <?php echo htmlspecialchars_decode(esc_attr($field['iattr'])); ?> /><label class="cauto-toggle-label" for="<?php echo (isset($field['attr']['id']))? esc_attr($field['attr']['id']) : null; ?>"></label> <?php echo esc_html($field['label']); ?>
                </div>
                <?php if (isset($field['help-text'])): ?>
                    <span class="cauto-inline-tip"><?php echo esc_attr($field['help-text']); ?></span>
                <?php endif; ?>
            </div>
<?php
            break;
            case 'group':
?>
            <div class="cauto-ui-wrapper">
                <div class="cauto-group-wrapper">
                    <?php if ($field['label']): ?>
                        <h4><?php echo esc_html($field['label']); ?></h4>
                        <?php if (count($field['options']) > 0): ?>
                            <ul>
                            <?php foreach ($field['options'] as $option): ?>
                                <li>
                                    <label>
                                        <input <?php echo htmlspecialchars_decode(esc_attr($field['iattr'])); ?> value="<?php echo esc_attr($option['value']); ?>"> <?php echo esc_html($option['label']); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (isset($field['help-text'])): ?>
                        <span class="cauto-inline-tip"><?php echo esc_attr($field['help-text']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
<?php              
            break;
            case 'custom':
?>
                <div class="cauto-custom-wrapper">
                <?php echo htmlspecialchars_decode(esc_html($field['html'])); ?>
                <?php if (isset($field['help-text'])): ?>
                    <span class="cauto-inline-tip"><?php echo esc_attr($field['help-text']); ?></span>
                <?php endif; ?>
                </div>
<?php 
            break;
        endswitch;
    endforeach;
endif;
?>