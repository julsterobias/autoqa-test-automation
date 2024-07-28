<?php 
if (!empty($data['data']['buttons'])): 
    foreach ($data['data']['buttons'] as $button):
        switch ($button['field']):
            case 'button':
?>
            <button <?php echo $button['iattr']; ?> ><?php echo (isset($button['icon']))? $button['icon'] : null; ?><span class="cauto_button_text"><?php echo esc_html($button['label']); ?></span></button>
<?php   
            break;
        endswitch;
    endforeach;
endif; 
?>