<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="resi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Update Resi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('olshop/update_resi'); ?>
            <div class="modal-body">

                
                <input type="text" class="form-control" name="resi" value="" placeholder="masukkan resi">
                
                <input type="hidden" name="inv_olshop_resi" id="inv_olshop_resi">

            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'update resi', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function get_resi(params){
        // console.log(params)
        $("#inv_olshop_resi").val(params)
    }
</script>