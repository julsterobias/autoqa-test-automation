<div class="cauto-modal-control-area">
    <div class="wrapper">
        <div class="col-20">
            <?php
            if (!empty($data['left_controls'])): 
                $this->render_ui(['buttons' => $data['left_controls']], 'buttons', []); 
            endif;
            ?>
        </div>
        <div class="col-80" id="cauto-step-config-control-area">
            <?php
            if (!empty($data['right_controls'])): 
                $this->render_ui(['buttons' => $data['right_controls']], 'buttons', []); 
            endif;
            ?>
            <input type="hidden" value="<?php echo esc_attr(json_encode($data['field_ids'])); ?>">
        </div>
    </div>
</div>
