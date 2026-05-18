<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Switch Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('dc/update_vendor'); ?>
            <div class="modal-body">

                
                <select name="vendor" class="form-control" required>
                    <option value="">-- Pilih Vendor --</option>
                    <option value="hitory">PT. HITORI JAYA LOGISTIK</option>
                    <option value="indo">PT. INDO JAYA ABADI KARGO</option>
                </select>
                
                <input type="hidden" name="site_code" id="site_code">

            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'update data', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function parsing_data(a,b){
        // console.log(params)
        $("#site_code").val(a)
    }
</script>