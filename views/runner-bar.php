<div class="cuato-runner-indicator ended">
    <span class="cauto-warming-up"><?php esc_html_e('Preparing runner...', 'autoqa-test-automation'); ?></span>
    <div class="cauto-runner-bars"></div>
    <div class="cauto-runner-completed">
        <div class="cauto-completed-content">
            <div class="result"><?php esc_html_e('No result found, contact developer', 'autoqa-test-automation'); ?></div>
            <p><input type="button" class="cauto-runner-button" id="cauto-close-runner-modal-result" value="<?php esc_attr_e('Close', 'autoqa-test-automation'); ?>"></p>
        </div>
    </div>
    <div class="cauto-runner-manual-ui">
        <div><?php esc_html_e('Paused by manual input, click continue if you are ready.', 'autoqa-test-automation'); ?></div>
        <div><input type="button" class="cauto-runner-button" id="cauto-continue-run-runner" value="<?php esc_attr_e('Continue', 'autoqa-test-automation'); ?>"></div>
    </div>
</div>