<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="vendor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Upload Surat Jalan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart('relokasi/upload_surat_jalan'); ?>
            <div class="modal-body">

                <input type="file" name="file" class="form-control">
                <input type="hidden" name="id_relokasi" id="id_relokasi">

            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'upload', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function get_id_relokasi(params){
        // console.log(params)
        $("#id_relokasi").val(params)
    }
</script>