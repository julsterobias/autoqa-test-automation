<div class="cauto-saved-flows">
    <?php 
    if(!empty($data['flows'])): 
        foreach ($data['flows'] as $flow):
    ?>
            <div class="cauto-flow-el cauto-settings-wrappers">
                <div class="wrapper">
                    <div class="col-3 col">
                        <div class="cuato-flow-status">
                            <span class="dashicons dashicons-minus"></span>
                        </div>
                    </div>
                    <div class="col-94 col-details">
                        <?php do_action('cauto_flow_meta_details', $flow) ?>
                        <?php do_action('cauto_flow_after_meta_details', $flow) ?>
                    </div>
                    <div class="col-3 col">
                        <div class="cauto-details-run-control">
                            <?php do_action('cauto_flow_run_button', $flow); ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php 
        endforeach;
    endif; ?>
</div>