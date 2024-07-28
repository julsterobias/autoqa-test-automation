<?php 
if (!empty($data['data']['fields'])): 
    foreach ($data['data']['fields'] as $field):  
        switch($field['field']):  
            case 'input':
?>
                    <div class="cauto-input-wrapper">
                        <label>
                            <input <?php echo $field['iattr'] ?> placeholder="<?php echo esc_html($field['label']); ?>">
                        </label>
                    </div>
<?php
                break;
            case 'toggle':
?>
                    <div class="cauto-toggle-wrapper">
                    <input <?php echo $field['iattr']; ?> /><label class="cauto-toggle-label" for="<?php echo (isset($field['attr']['id']))? $field['attr']['id'] : null; ?>"></label> <?php echo esc_html($field['label']); ?>
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