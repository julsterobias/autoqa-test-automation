<?php if (!empty($data['results'])): 
do_action('autoqa-results-before-list', $data['results']);    
?>

<ul id="cauto-results-list" data-rucnt="<?php echo $data['runner_count']; ?>" data-flow-id="<?php echo $data['flow_id']; ?>">
    <?php foreach ($data['results'] as $result): ?>
        <li data-runner-id="<?php echo esc_attr($result['ID']); ?>">
            <div class="wrapper">
                <div class="cauto-result-m-status col-10">
                    <?php if (isset($result['flow_status'])): ?>
                        <span class="dashicons dashicons-no"></span>
                    <?php else: ?>
                        <span class="dashicons dashicons-saved"></span>
                    <?php endif; ?>                    
                </div>
                <div class="cauto-result-m-details col-80">
                    <span class="cauto-result-meta-name"><?php echo esc_html($result['name']); ?></span>
                    <span class="cauto-result-meta-date"><?php echo esc_html($result['date']); ?></span>
                </div>
                <div class="cauto-result-m-arrow col-10">
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </div>
            </div>
            
        </li>
    <?php endforeach; ?>
</ul>
<div class="cauto-see-other-runners"><span><?php esc_html_e('Show more', 'autoqa-test-automation'); ?></span></div>

<?php 
do_action('autoqa-results-end-list', $data['results']);    
endif; ?>