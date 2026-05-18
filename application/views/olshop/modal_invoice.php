<?php

// $required = "";
$required = "required";

?>
<!-- modal tambah profile -->
<div class="modal fade" id="vendor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Update No Faktur SDS (SLSXXXX)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open_multipart('olshop/update_modal_invoice'); ?>
            <div class="modal-body">

                <?php 
                    $invoice_sds = NULL;
                    if ($invoice_sds) {
                        $params_invoice = $invoice_sds;
                    }else{
                        $params_invoice = "";
                    }
                ?>

                <input type="hidden" id="signature_header" value="" name="signature_header">
                <input type="hidden" id="id_ref" value="" name="id_ref">

                <label for="faktur_sds">No Faktur SDS</label>
                <input type="text" class="form-control" name="faktur_sds" id="faktur_sds" placeholder="input nomor faktur disini" value=<?= $params_invoice ?>>

                <br>

                <label for="tanggal_faktur">Tanggal Faktur SDS</label>
                <input type="date" class="form-control" name="tanggal_faktur" id="tanggal_faktur">

                <br>

                <label for="nominal">Nominal Faktur SDS</label>
                <input type="number" class="form-control" name="nominal" id="nominal">

                <br>
                
                <label for="capture_faktur">Capture Faktur</label>
                <input type="file" name="capture_faktur" id="capture_faktur" class="form-control">

            </div>
            <div class="modal-footer">
                <a href="history_invoice" target="_blank" class="btn btn-dark">History Faktur</a>
                <?php echo form_submit('submit', 'update data', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>