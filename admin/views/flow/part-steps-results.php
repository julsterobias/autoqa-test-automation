<div class="cauto-runner-steps-results">
    <?php 
    if (!empty($data['steps'])): 
        foreach ($data['steps'] as $step):  

            $status_icon_class = 'dashicons-no';
            $status_class      = 'failed';
           
            
            if (!empty($step['result'])) {
                if ($step['result'][0]->status === 'passed') {
                    $status_icon_class = 'dashicons-saved';
                    $status_class      = 'passed';
                }
                $message = $step['result'][0]->message;
            } else {
                $status_class = 'no-run';
                $status_icon_class = 'dashicons-minus';
                $message = '--';
            }
            
            $step_name = ucwords(str_replace('-', ' ', $step['step']));
    ?>
        <div class="cauto-runner-step-item">
            <div class="item-status-<?php echo esc_attr($status_class); ?>"><span class="dashicons <?php echo esc_attr($status_icon_class); ?>"></span></div>
            <div>
                <span><?php echo esc_html($step_name); ?></span>
                <span><?php echo esc_html($message); ?></span>
            </div>
        </div>
    <?php 
        endforeach;
    endif; ?>
</div>