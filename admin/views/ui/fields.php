<?php 
if (!empty($data['data']['fields'])): 
    foreach ($data['data']['fields'] as $field):  
        switch($field['field']):  
            case 'input':
?>
                <div class="cauto-ui-wrapper">
                    <div class="cauto-input-wrapper">
                        <label><?php echo esc_html($field['label']); ?>
                            <input <?php echo $field['iattr'] ?>>
                        </label>
                    </div>
                </div>
<?php
                break;
            case 'select':
?>
            <div class="cauto-ui-wrapper">
                <div class="cauto-select-wrapper">
                    <label><?php echo esc_html($field['label']); ?>
                        <select <?php echo $field['iattr'] ?>>
                            <?php if (!empty($field['options'])): 
                                foreach ($field['options'] as $value => $label):    
                            ?>
                                <option value="<?php echo esc_attr($value) ?>"><?php echo  esc_html($label); ?></option>
                            <?php 
                                endforeach;
                            endif; ?>
                        </select>
                    </label>
                </div>
            </div>
<?php
                break;

            case 'toggle':
?>
            <div class="cauto-ui-wrapper">
                <div class="cauto-toggle-wrapper">
                    <input <?php echo $field['iattr']; ?> /><label class="cauto-toggle-label" for="<?php echo (isset($field['attr']['id']))? $field['attr']['id'] : null; ?>"></label> <?php echo esc_html($field['label']); ?>
                </div>
            </div>
<?php
            break;
            case 'custom':
?>
                <div class="cauto-custom-wrapper">
                <?php echo $field['html']; ?>
                </div>
<?php 
            break;
        endswitch;
    endforeach;
endif;
?>