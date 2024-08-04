<?php 
if (!empty($data['data']['fields'])): 
    foreach ($data['data']['fields'] as $field):  
        switch($field['field']):  
            case 'input':
?>
                <div class="cauto-ui-wrapper">
                    <div class="cauto-input-wrapper">
                        <?php 
                            if ($field['attr']['type'] === 'checkbox'):
                        ?>  
                        <label>
                            <input <?php echo $field['iattr']; ?>> <?php echo esc_html($field['label']); ?>
                        </label>
                        <?php
                            else:
                        ?>
                        <label><?php echo esc_html($field['label']); ?>
                            <input <?php echo $field['iattr']; ?>>
                        </label>
                        <?php endif; ?>
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
                                        <input <?php echo $field['iattr']; ?> value="<?php echo esc_attr($option['value']); ?>"> <?php echo esc_html($option['label']); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>
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