<div class="builder-wrapper">
    <div class="wrapper">
        <div class="col-20">
            <div class="cauto-builder-steps">
                <?php do_action('cauto_load_builder_steps'); ?>
            </div>
        </div>
        <div class="col-80">
            <div class="cauto-builder-area">
                <div class="builder-header">
                    <?php do_action('cauto_load_builder_control', $data['details']); ?>
                </div>
                <div class="builder-site">
                    <ul class="cauto_steps_builder"></ul>
                </div>
            </div>
        </div>
    </div>
</div>