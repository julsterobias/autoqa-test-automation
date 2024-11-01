<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<ul class="cauto-steps-drag-container">
    <?php 
        if (!empty($data['data'])):
            foreach ($data['data'] as $type => $step):
                if (!empty($step['label'])):
                    $is_divider     = (!empty($step['divider']))? 'cauto-step-divider' : 'cauto-steps-el cauto-steps-draggable';
                    $group          = (isset($step['group']))? 'cauto-step-group_'.$step['group'] : null; 
                    $no_settings    = (isset($step['no_settings']))? 1 : null;
    ?>
    <li class="<?php echo esc_attr($is_divider); ?> <?php echo esc_attr($group); ?>">
        <div class="cauto-step-element" data-no-settings="<?php echo esc_attr($no_settings); ?>" data-step="<?php echo esc_attr($type); ?>"><?php echo (!empty($step['icon']))? htmlspecialchars_decode(esc_html($step['icon'])) : null; ?><?php echo esc_html($step['label']); ?></div>
    </li>
    <?php 
                endif;
            endforeach;
        endif;
    ?>
</ul>