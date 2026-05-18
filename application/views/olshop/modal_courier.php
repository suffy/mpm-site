<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="courier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Courier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('olshop/update_courier'); ?>
            <div class="modal-body">

                
                <select name="courier" class="form-control" required>
                    <option value="">-- Pilih Courier --</option>
                    <option value="JNE">JNE</option>
                    <option value="GO-SEND">GOSEND</option>
                    <option value="JNE-PLUS">JNE-PLUS</option>
                </select>
                
                <input type="hidden" name="inv_olshop_courier" id="inv_olshop_courier">

            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'update courier', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function get_courier(params){
        // console.log(params)
        $("#inv_olshop_courier").val(params)
    }
</script>