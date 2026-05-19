<div class="az-content">
<div class="container-fluid">

<?= $this->load->view('penta/component/sidebar');?>

<div class="az-content-body pd-lg-l-40">

<h3><?= $title; ?></h3>

<form method="post" action="<?= base_url($url); ?>">

<div class="card p-3">

<div class="row">

<div class="col-md-6">

    <div class="form-group">
        <label>Org ID</label>
        <input type="text" name="org_id" class="form-control" value="<?= $row->org_id ?>" disabled>
    </div>

    <div class="form-group">
        <label>Org Name</label>
        <input type="text" name="org_name" class="form-control" value="<?= $row->org_name ?>" disabled>
    </div>

    <div class="form-group">
        <label>Kode Customer</label>
        <input type="text" name="location" class="form-control" value="<?= $row->location ?>">
    </div>

    <div class="form-group">
        <label>City</label>
        <input type="text" name="city" class="form-control" value="<?= $row->city ?>">
    </div>

    <div class="form-group">
        <label>Province</label>
        <input type="text" name="province" class="form-control" value="<?= $row->province ?>">
    </div>

    <div class="form-group">
        <label>Salesman</label>
        <input type="text" name="salesman_name" class="form-control" value="<?= $row->salesman_name ?>">
    </div>

</div>

<div class="col-md-6">

    <div class="form-group">
        <label>Customer Name</label>
        <input type="text" name="bill_ship_cust_name" class="form-control" value="<?= $row->bill_ship_cust_name ?>">
    </div>

    <div class="form-group">
        <label>Type</label>
        <!-- <input type="text" name="typeid" class="form-control" value="<?= $row->typeid ?>"> -->
        <select name="typeid" id="typeid" class="form-control select2" required>
            <option value=""></option>
            <?php foreach ($get_type->result() as $a) { ?>
                <option value="<?= $a->kode_type ?>"
                    <?= ($row->typeid == $a->kode_type) ? 'selected' : ''; ?>>

                    <?= $a->kode_type ?> - <?= $a->nama_type ?>

                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label>Class</label>
        <!-- <input type="text" name="classid" class="form-control" value="<?= $row->classid ?>"> -->
        <select name="classid" id="classid" class="form-control select2" required>
            <option value=""></option>
            <?php foreach ($get_class->result() as $a) { ?>
                <option value="<?= $a->kode ?>"
                    <?= ($row->classid == $a->kode) ? 'selected' : ''; ?>>

                    <?= $a->kode ?> - <?= $a->jenis ?>

                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label>Spot</label>
        <!-- <input type="text" name="spot" class="form-control" value="<?= $row->spot ?>"> -->
        <select name="spot" id="spot" class="form-control select2" required>
            <option value=""></option>
            <?php foreach ($get_spot->result() as $a) { ?>
                <!-- <option value="<?= $a->kode_spot_mapping ?>"><?= $a->kode_spot_mapping ?> - <?= $a->nama_spot_mapping ?></option> -->
                 <option value="<?= $a->kode_spot_mapping ?>"
                    <?= ($row->spot == $a->kode_spot_mapping) ? 'selected' : ''; ?>>

                    <?= $a->kode_spot_mapping ?> - <?= $a->nama_spot_mapping ?>

                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label>Address 1</label>
        <textarea name="address1" class="form-control"><?= $row->address1 ?></textarea>
    </div>

    <div class="form-group">
        <label>Address 2</label>
        <textarea name="address2" class="form-control"><?= $row->address2 ?></textarea>
    </div>

    <div class="form-group">
        <label>Address 3</label>
        <textarea name="address3" class="form-control"><?= $row->address3 ?></textarea>
    </div>

</div>

</div>

<hr>

<div class="text-right">
    <a href="<?= base_url('penta/request_customer'); ?>" class="btn btn-secondary">
        Kembali
    </a>

    <button type="submit" class="btn btn-primary">
        Update
    </button>
</div>

</div>

</form>

</div>
</div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: "-- Pilih --",
        allowClear: true,
        width: '100%'
    });
});
</script>