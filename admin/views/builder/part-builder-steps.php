<ul>
    <?php 
        if (!empty($data['data'])):
            foreach ($data['data'] as $type => $step):
                if (!empty($step['label'])):
                    $is_divider = (!empty($step['divider']))? 'cauto-step-divider' : 'cauto-steps-el cauto-steps-draggable';
                    $group = (isset($step['group']))? 'cauto-step-group_'.$step['group'] : null; 
    ?>
    <li class="<?php echo esc_attr($is_divider); ?> <?php echo $group; ?>">
        <div class="cauto-step-element" data-step="<?php echo esc_attr($type); ?>"><?php echo (!empty($step['icon']))? $step['icon'] : null; ?><?php echo esc_html($step['label']); ?></div>
    </li>
    <?php 
                endif;
            endforeach;
        endif;
    ?>
</ul>