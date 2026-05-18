<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Input Asset</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="search">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">From</label>
            <div class="col-sm-4">
              <input class="form-control" type="date" name="from" id="from" required />
            </div> To
            <div class="col-sm-4">
              <input class="form-control" type="date" name="to" id="to" required />
            </div>
            <br>
          </div>
          <div class="form-group row" hidden>
            <label class="col-sm-3 col-form-label">Data</label>
            <div class="col-sm-4">
              <select name="data_table" id="data_table" class="form-control">
                <option value="1">Kas</option>
                <option value="2" selected>Jurnal</option>
              </select>
            </div>
          </div>
          <div align="center">
            <button class="btn btn-success btn-sm cari" onclick="search();" disabled>Cari</button>
          </div>
        </div>

        <?= form_open_multipart($url); ?>
        <div id="form">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Pilih Voucher</label>
            <div class="col-sm-7 inline">
              <select class="form-control" type="text" name="voucher" id="voucher">
                <option value=''>Pilih Voucher</option>
              </select>
            </div>
            <div class="col-sm-2 inline">
              <a href="#" class="btn btn-default btn-sm" id="refresh_voucher"><i class="fa fa-refresh fa-2x"></i></a>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">No. Voucher</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="no_voucher" id="no_voucher" readonly />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nick Voucher</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="nick_voucher" id="nick_voucher" readonly />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nourut</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="nourut" id="nourut" readonly />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tanggal Payroll</label>
            <div class="col-sm-8">
              <input class="form-control" type="date" name="tgl_payroll" id="tgl_entry" readonly />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Keperluan :</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="keperluan" id="cos_description" readonly />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nilai Perolehan</label>
            <div class="col-sm-8">
              <input class="form-control" type="text" name="nilai_perolehan" id="nilai_perolehan" readonly />
            </div>
          </div>

          <hr>

          <div class="form_assets">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">No. POF</label>
              <div class="col-6">
                <?php
                  $nopo=array();
                  $nopo['']='- Pilih -';
                  foreach($pr as $value)
                  {
                      $nopo[$value->no_po.'-'.$value->id_barang]= "$value->no_po - $value->id_barang | $value->username_penerima";
                  }
                  echo form_dropdown('no_po', $nopo,'','class="form-control edit-dropdown" id="input_no_po"');
                ?>
              </div>
              <div class="col-2">
                <a href="#" type="button" class="btn waves-effect waves-light btn-info btn-outline-info btn-sm detail"
                  onclick="Detail()">Detail</a>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">No. PR</label>
              <div class="col-sm-8">
                <input class="form-control" type="text" name="no_pr" id="no_pr" readonly />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Nama Barang</label>
              <div class="col-sm-8">
                <input class="form-control" type="text" name="nama_barang" id="keterangan" required />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">S/N</label>
              <div class="col-sm-8">
                <input class="form-control" type="text" name="sn" />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Jumlah Barang</label>
              <div class="col-sm-8">
                <input class="form-control" type="number" name="jum_barang" required />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Golongan</label>
              <div class="col-sm-8">
                <select class="form-control" name="golongan" id="">
                  <option value="0.25">GOL I</option>
                  <option value="0.125">GOL II</option>
                  <option value="0.0625">GOL III</option>
                  <option value="0.05">GOL IV</option>
                  <option value="0">GOL V</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Group Asset</label>
              <div class="col-sm-8">
                <?php
                if (isset($group)) {
                  foreach ($group->result() as $value) {
                    $grup[$value->id] = $value->namagrup;
                  }

                  echo isset($edit) ? form_dropdown('grup', $grup, $grupid, "class=form-control") : form_dropdown('grup', $grup, '', "class=form-control");
                }
                ?>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Upload Faktur (<font color="red">*PDF
                </font>)</label>
              <div class="col-sm-8">
                <input type="file" name="file" id="file" class="form-control" />
              </div>
            </div>
          </div>
          <br><br>
          <center>
            <a href="#" class="btn btn-default btn-sm" id="periode">Ganti Periode</a>
            <?= form_submit('submit', 'Simpan', 'class="btn btn-success btn-sm"'); ?>
            <?= form_close(); ?>
          </center>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->load->view('assets_new/modal_detail_purchase_asset'); ?>

<script>
  $(document).ready(function () {

    $('div#search').show();
    $('div#form').hide();
    $("a.detail").hide();

    $('#from').change(function () {
      $('#to').change(function () {
        $('button.cari').removeAttr('disabled')
      });
    });

    $('#to').change(function () {
      $('#from').change(function () {
        $('button.cari').removeAttr('disabled')
      });
    });

    $('#refresh_voucher').click(function () {
      var data_table = document.getElementById("data_table").value;

      $.ajax({
        url: "<?= base_url('assets_2/get_assets'); ?>",
        method: "POST",
        data: {
          data_table: data_table,
        },
        async: true,
        dataType: "json",
        success: function (data) {
          var html = "<option value=''>Pilih Voucher</option>";
          var i;

          if (data_table == 1) {
            for (i = 0; i < data.length; i++) {
              html += '<option value=' + data[i].no_voucher + data[i]
                .nourut + '>' + data[i].no_voucher + ' - ' + data[i]
                .nourut + '</option>';
            }
          } else {
            for (i = 0; i < data.length; i++) {
              html += '<option value=' + data[i].nojurnal + data[i]
                .nourut + '>' + data[i].nojurnal + ' - ' + data[i]
                .nourut + '</option>';
            }
          }

          $('#voucher').html(html);
        }
      });
    });

    $("select#voucher").change(function () {
      var voucher = document.getElementById("voucher").value;
      var data_table = document.getElementById("data_table").value;
      // $('div.user_request').show()
      $.ajax({
        type: "POST",
        url: "<?= base_url() . 'assets_2/get_data'; ?>",
        data: {
          id: voucher
        },
        dataType: "json",
        success: function (response) {
          // console.log(response.get_assets_kas)
          if (data_table == 1) {

            var debet = response.get_assets_kas.debet
            var kredit = response.get_assets_kas.kredit

            if (debet > kredit) {
              var nilai_perolehan = debet
            } else {
              var nilai_perolehan = kredit
            }

            $('input#no_voucher').val(response.get_assets_kas.no_voucher)
            $('input#nick_voucher').val(response.get_assets_kas.nick_voucher)
            $('input#nourut').val(response.get_assets_kas.nourut)
            $('input#tgl_entry').val(response.get_assets_kas.tgl_entry.substr(0, 10))
            $('input#cos_description').val(response.get_assets_kas.cos_description)
            $('input#nilai_perolehan').val(nilai_perolehan)
            $('input#keterangan').val(response.get_assets_kas.keterangan)
              .change()

          } else {

            var debet = response.get_assets_jurnal.debet
            var kredit = response.get_assets_jurnal.kredit

            if (debet > kredit) {
              var nilai_perolehan = debet
            } else {
              var nilai_perolehan = kredit
            }

            $('input#no_voucher').val(response.get_assets_jurnal.nojurnal)
            $('input#nick_voucher').val(response.get_assets_jurnal.nick_voucher)
            $('input#nourut').val(response.get_assets_jurnal.nourut)
            $('input#tgl_entry').val(response.get_assets_jurnal.tgl_entry.substr(0, 10))
            $('input#cos_description').val(response.get_assets_jurnal.description)
            $('input#nilai_perolehan').val(nilai_perolehan)
            $('input#keterangan').val(response.get_assets_jurnal.keterangan)
              .change()
          }
        }
      });
    });

    $('#periode').click(function () {
      $('div#search').show();
      $('div#form').hide();


      $('input#no_voucher').val('')
      $('input#nick_voucher').val('')
      $('input#nourut').val('')
      $('input#tgl_entry').val('')
      $('input#cos_description').val('')
      $('input#nilai_perolehan').val('')
      $('input#keterangan').val('')
        .change()
    });
  });

  function search() {
    $('div#form').show();
    $('div#search').hide();
    $.ajax({
      url: "<?= base_url('assets_2/get_assets_sds'); ?>",
      method: "POST",
      data: {
        from: document.getElementById("from").value,
        to: document.getElementById("to").value,
        data_table: document.getElementById("data_table").value,
      },
      dataType: "json"
    });

    var data_table = document.getElementById("data_table").value;

    $.ajax({
      url: "<?= base_url('assets_2/get_assets'); ?>",
      method: "POST",
      data: {
        data_table: data_table,
      },
      async: true,
      dataType: "json",
      success: function (data) {
        var html = "<option value=''>Pilih Voucher</option>";
        var i;

        if (data_table == 1) {
          for (i = 0; i < data.length; i++) {
            html += '<option value=' + data[i].no_voucher + data[i]
              .nourut + '>' + data[i].no_voucher + ' - ' + data[i]
              .nourut + '</option>';
          }
        } else {
          for (i = 0; i < data.length; i++) {
            html += '<option value=' + data[i].nojurnal + data[i]
              .nourut + '>' + data[i].nojurnal + ' - ' + data[i]
              .nourut + '</option>';
          }
        }

        $('#voucher').html(html);
      }
    });


    $("select#input_no_po").change(function () {
            var no_po = document.getElementById("input_no_po").value;
            if (no_po == '0') {
                $("a.detail").hide();
            } else {
                $("a.detail").attr('onclick', 'Detail("' + no_po + '")').show();
            }
            $.ajax({
                type: "GET",
                url: "<?= base_url().'assets_new/get_data';?>",
                data: {
                    id: no_po
                },
                dataType: "json",
                success: function (response) {
                    $("input#no_pr").val(response.get_pr[0].no_pr)
                        .change()
                }
            });
        });
  }

  function Detail(param) {
        $('#detail_purchase_asset').modal();
        $('div#barang').remove();
        $.ajax({
            type: "GET",
            url: "<?= base_url().'assets_new/get_data';?>",
            data: {
                id: param
            },
            dataType: "JSON",
            success: function (response) {
                const d = new Date(response.get_pr[0].tgl_po);
                const tahun = d.getFullYear();
                const bulan = d.getMonth();
                const tgl = d.getDate();
                const tanggal = tahun + '-' + bulan + '-' + tgl;
                $('input#no_po').val(response.get_pr[0].no_po).attr('readonly', true);
                $('input#no_pr').val(response.get_pr[0].no_pr).attr('readonly', true);
                $('input#username_request').val(response.get_pr[0].username).attr('readonly', true);
                $('input#tanggal').val(tanggal).attr('readonly', true);
                $('input#nama_toko').val(response.get_pr[0].nama_toko).attr('readonly', true);
                $('textarea#alamat').val(response.get_pr[0].alamat).attr('readonly', true);
                $('input#no_telp').val(response.get_pr[0].telp).attr('readonly', true);
                $('input#fax').val(response.get_pr[0].fax).attr('readonly', true);
                $('input#attn').val(response.get_pr[0].attn).attr('readonly', true);

                for (let index = 0; index < response.get_pr.length; index++) {
                    var i = (+index + 1);
                    $('div.barang').append('<div id="barang"><label for="barang">Barang ' + i +
                        '</label><input type="text" name="barang[]" id="barang' + index +
                        '" class="form-control"></div>');
                    $('input#barang' + index).val(response.get_pr[index].nama_barang + ' ' + response
                        .get_pr[index].tipe).attr("readonly", true);
                }
            }
        });
    }
</script>