<script>

$(document).ready(function(){
    StopLoad();
    <?php if(in_array($this->uri->segment(2),array("index",""))){ ?>
        LoadData();
    <?php }  ?>
    <?php if(in_array($this->uri->segment(2),array("tambah","edit","tambah_disposisi"))){ ?>
        SearchForm();
        <?php if(in_array($this->uri->segment(2),array("tambah","edit"))){ ?>
        LoadTempDari();
        <?php }  ?>
    <?php } ?>
    
});

function SearchForm() {
	$('.select-tahun').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Tahun',
    });

    $('.select-kepada').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Kepada',
    });
}

function LoadTempDari(){
    $("#Dari").autocomplete({
		source: "<?= base_url('surat_masuk/get_temp_dari') ?>",
		select: function (event, ui) {
			$("#Dari").val(ui.item.label);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label +  "</div>").appendTo(ul); };
}

function formatRupiah(angka, prefix){
  var number_string = angka.toString().replace(/[^,\d]/g, ''),
  
  split       = number_string.split(','),
  sisa        = split[0].length % 3,
  rupiah      = split[0].substr(0, sisa),
  ribuan      = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function pagination(page_num, total_page) {
	page_num = parseInt(page_num);
	total_page = parseInt(total_page);
	var paging = "<ul class='pagination btn-sm'>";
	if (page_num > 1) {
		var prev = page_num - 1;
		paging += "<li><a href='javascript:void(0);' onclick='LoadData(" + prev + ")'>Prev</a></li>";
	} else {
		paging += "<li class='disabled'><a>Prev</a></li>";
	}
	var show_page = 0;
	for (var page = 1; page <= total_page; page++) {
		if (((page >= page_num - 3) && (page <= page_num + 3)) || (page == 1) || page == total_page) {
			if ((show_page == 1) && (page != 2)) {
				paging += "<li class='disabled'><a>...</a></li>";
			}
			if ((show_page != (total_page - 1)) && (page == total_page)) {
				paging += "<li class='disabled'><a>...</a></li>";
			}

			if (page == page_num) {
				var aktif = formatRupiah(page);
				paging += "<li class='active'><a>" + aktif + "</a></li>";
			} else {
				var aktif = formatRupiah(page);
				paging += "<li class='javascript:void(0)'><a onclick='LoadData(" + page + ")'>" + aktif + "</a></li>";
			}
			show_page = page;
		}
	}

	if (page_num < total_page) {
		var next = page_num + 1;
		paging += "<li><a href='javascript:void(0)' onclick='LoadData(" + next + ")'>Next</a></li>";
	} else {
		paging += "<li class='disabled'><a>Next</a></li>";
	}
	$("#Paging").html(paging);
}

function LoadData(page){
    page = page != undefined ? page : 1;
    
    var iData = $("#Filter").serialize();
    $.ajax({
        type : "POST",
        url : "<?= base_url('surat_masuk/show_data/') ?>",
        data : "Page="+page+"&"+iData,
        beforeSend: function(){
            StartLoad();
        },
        success: function(r){
            StopLoad();
            var res = JSON.parse(r);
            console.log(res);
            if(parseInt(res['jumlah_data']) > 0){
                var No = parseInt(res['NoAwal']);
                var html = "";
                for(var i=0; i < res['data'].length; i++){
                    var iData = res['data'][i];
                    html += "<tr>";
                        html += "<td class='text-center'>"+No+"</td>";
                        html += "<td>"+iData['NoSurat']+"<br><small>No Dokumen : <b>"+iData['NoDokumen']+"</b></small><br><small>Baju Surat : <b>"+iData['BajuSurat']+"</b></small></td>";
                        html += "<td>";
                            html += iData['Perihal']+"<br><small>Dari <b>: "+iData['Dari']+"</b></small><br><small>Kepada <b>: "+iData['Kepada']+"</b></small>";
                        html += "</td>";
                        html += "<td>";
                            html += iData['TglSurat']+"<br><small>Tangal Masuk <b>: "+iData['TglMasukSurat']+"</b></small>";
                        html += "</td>";
                        html += "<td>"+iData['Authorss']+"</td>";
                        html += "<td>";
                            html += "<a target='_blank' href='<?= base_url() ?>load_data/surat_masuk/"+iData['Id']+"' data-toggle='tooltip' title='Lihat Surat' class='btn btn-flat btn-xs btn-success'><i class='fa fa-eye'></i></a>";
                        html += "</td>";
                        html += "<td class='text-center'>";
                            html += "<div class='btn-group'>";
                            <?php if($this->session->userdata('KodeLevel') != "2"){ ?>
                            html += "<a href='<?= base_url() ?>surat_masuk/tambah_disposisi/"+iData['Id']+"' class='btn btn-info btn-xs' data-toggle='tooltip' title='tambah disposisi'><i class='fa fa-plus'></i></a>";
                            <?php } ?>
                            html += "<a href='<?= base_url() ?>surat_masuk/view_disposisi/"+iData['Id']+"' class='btn btn-warning btn-xs' data-toggle='tooltip' title='lihat disposisi'><i class='fa fa-eye'></i></a>";
                            <?php if($this->session->userdata('KodeLevel') != "2"){ ?>
                            html += "<a href='<?= base_url() ?>surat_masuk/edit/"+iData['Id']+"' class='btn btn-primary btn-xs' data-toggle='tooltip' title='ubah data'><i class='fa fa-edit'></i></a>";
                            if(iData['Disposisi'] == "" || iData['Disposisi'] == null){
                                html += "<a href='javascript:void(0)' onclick=\"HapusData('"+iData['Id']+"')\"  class='btn btn-danger btn-xs' data-toggle='tooltip' title='hapus data'><i class='fa fa-trash-o'></i></a></div>";
                            }
                            <?php } ?>
                        html += "</td>";
                    html += "</tr>";
                    No++;
                }
                $("#ShowData").html(html);
                $("[data-toggle='tooltip']").tooltip();
                pagination(page, res['jumlah_page']);
                StopLoad();
            }else{
                $("#ShowData").html("<tr><td class='text-center' colspan='7'>no data availible in table</td></tr>");
                StopLoad();
            }
        },
        error : function(er){
            console.log(er);
        }
    })
}

function HapusData(Id){
    ClearModal();
    jQuery("#modal").modal('show', {backdrop: 'static'});
    $(".modal-title").html("Konfirmasi Delete");
    $("#ModalDetail").html("<form id='FormDelete'><input type='hidden' name='Id' value='"+Id+"'></form><div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
    $(".modal-footer").show()
}

/** BASIC JS */
$("#TglSurat,#TglMasukSurat,#Tgl").datepicker({ "dateFormat": "yy-mm-dd", "autoclose": true, });

function StopLoad(){
    $(".LoadingState").hide();
}

function StartLoad(){
    $(".LoadingState").show();
}

// function sprintf(format) {
//   var args = Array.prototype.slice.call(arguments, 1);
//   var i = 0;
//   return format.replace(/%s/g, function () {
//     return args[i++];
//   });
// }

function Customerror(kode_modul, no, catatan,id) {
  $("#"+id).html("<div class='alert alert-warning'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> ER-"+kode_modul+"."+no+" : "+catatan+"</div>");
}

function Customsukses(kode_modul, no, catatan, id) {
  $("#" + id).html("<div class='alert alert-success'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> SCS-" + kode_modul + "." + no + " : " + catatan + "</div>");
}

function scrolltop(){
  $("html, body").animate({
          scrollTop: 0
      }, 600);
      return false;
}

$("#FormData").submit(function(e){
    e.preventDefault();
    SubmitData();
})

$("#FormDataUpdate").submit(function(e){
    e.preventDefault();
    SubmitDataUpdate();
})

$("#FormDataDisposisiTambah").submit(function(e){
    e.preventDefault();
    SubmitDisposisiTambah();
})

$("#FormDataDisposisiUpdate").submit(function(e){
    e.preventDefault();
    SubmitDisposisiUpdate();
})

function SubmitDisposisiTambah(){
    if(ValidasiDisposisiTambah() != false){
        var iData = new FormData($("#FormDataDisposisiTambah")[0]);
        $.ajax({
            type : "POST",
            url : "<?= base_url('surat_masuk/save_disposisi/') ?>",
            processData : false,
			contentType : false,
			chace : false,
            data : iData,
            success: function(res){
                var r = JSON.parse(res);
                console.log(r);
                if(r['status'] == "sukses"){
                    Customsukses("NT-DNS", "001", r['pesan'], "proses");
                    ClearDisposisi();
                    setTimeout(function(){
                        $("#proses").html("");
                        
                    },3000);
                    scrolltop();
                    
                }else{
                    Customerror("NT-DNS", "001", r['pesan'], "proses");
                    scrolltop();
                }

            },
            error: function(er){
                console.log(er);
            }
        })
    }
}

function SubmitDisposisiUpdate(){
    if(ValidasiDisposisiUpdate() != false){
        var iData = new FormData($("#FormDataDisposisiUpdate")[0]);
        $.ajax({
            type : "POST",
            url : "<?= base_url('surat_masuk/update_disposisi/') ?>",
            processData : false,
			contentType : false,
			chace : false,
            data : iData,
            success: function(res){
                var r = JSON.parse(res);
                console.log(r);
                if(r['status'] == "sukses"){
                    Customsukses("SRT-MSK", "001", r['pesan'], "proses");
                    setTimeout(function(){
                        $("#proses").html("");
                        location.reload();
                    },3000);
                    scrolltop();
                    
                    
                }else{
                    Customerror("SRT-MSK", "001", r['pesan'], "proses");
                    scrolltop();
                }

            },
            error: function(er){
                console.log(er);
            }
        })
    }
}

function ValidasiDisposisiTambah(){
    var JumlahKepada = $(".Kepada").filter(':checked').length;
    var JumlahDisposisi = $(".Disposisi").filter(':checked').length;
    if(JumlahKepada <= 0){
        Customerror("NT-DNS", "001", "Tujuan  belum dipilih","proses");
        scrolltop();
        $("#"+iForm[i]).focus();
        return false;
    }

    if(JumlahDisposisi <= 0){
        Customerror("SRT-MSK", "001", "Disposisi belum dipilih","proses");
        scrolltop();
        $("#"+iForm[i]).focus();
        return false;
    }

    var iForm = ["File","Dari","Tgl"];
    var iKet = ["File belum dipilih","Disposisi Dari belum dipilih","Tanggal Disposisi belum lengkap"];
    for(i=0; i < iForm.length; i++){
        if($("#"+iForm[i]).val() == ""){
            Customerror("SRT-MSK", "001", iKet[i],"proses");
            scrolltop();
            $("#"+iForm[i]).focus();
            return false;
        }
    }
    
}
function ValidasiDisposisiUpdate(){
    var JumlahKepada = $(".Kepada").filter(':checked').length;
    var JumlahDisposisi = $(".Disposisi").filter(':checked').length;
    if(JumlahKepada <= 0){
        Customerror("SRT-MSK", "001", "Tujuan  belum dipilih","proses");
        scrolltop();
        $("#"+iForm[i]).focus();
        return false;
    }

    if(JumlahDisposisi <= 0){
        Customerror("SRT-MSK", "001", "Disposisi belum dipilih","proses");
        scrolltop();
        $("#"+iForm[i]).focus();
        return false;
    }

    var iForm = ["Dari","Tgl"];
    var iKet = ["Disposisi Dari belum dipilih","Tanggal Disposisi belum lengkap"];
    for(i=0; i < iForm.length; i++){
        if($("#"+iForm[i]).val() == ""){
            Customerror("SRT-MSK", "001", iKet[i],"proses");
            scrolltop();
            $("#"+iForm[i]).focus();
            return false;
        }
    }
    
}
function Validasi(){
    var iForm = ["TglSurat","TglMasukSurat","Kepada","Dari","NoSurat","Perihal","File"];
    var iKet = ["Tanggal Surat belum lengkap!","Tanggal Masuk Surat Belum dipilih","Kepada Yth belum dipilih","Dari belum lengkap", "No Surat belum lengkap", "Perihal belum lengkap", "File belum dipilih"];
    for(i=0; i < iForm.length; i++){
        if($("#"+iForm[i]).val() == ""){
            Customerror("SRT-MSK", "001", iKet[i],"proses");
            scrolltop();
            $("#"+iForm[i]).focus();
            return false;
        }
    }
}

function ValidasiUpdate(){
    var iForm = ["TglSurat","TglMasukSurat","Kepada","Dari","NoSurat","Perihal"];
    var iKet = ["Tanggal Surat belum lengkap!","Tanggal Masuk Surat Belum dipilih","Kepada Yth belum dipilih","Dari belum ;engkap", "No Surat belum lengkap", "Perihal belum lengkap"];
    for(i=0; i < iForm.length; i++){
        if($("#"+iForm[i]).val() == ""){
            Customerror("SRT-MSK", "001", iKet[i],"proses");
            scrolltop();
            $("#"+iForm[i]).focus();
            return false;
        }
    }
}

function Clear(){
    $(".FormInput").val("");
    $('.select').trigger("change");
}

function ClearDisposisi(){
    $(".FormInputDisposisi").val("");
    $(".FormInputDisposisi").val("");
    $(".select-kepada").trigger("change");
    $(".Kepada").prop("checked",false);
    $(".Disposisi").prop("checked",false);
}

function SubmitDataUpdate(){
    if(ValidasiUpdate() != false){
        var iData = new FormData($("#FormDataUpdate")[0]);
        $.ajax({
            type : "POST",
            url : "<?= base_url('surat_masuk/update/') ?>",
            processData : false,
			contentType : false,
			chace : false,
            data : iData,
            success: function(res){
                var r = JSON.parse(res);
                console.log(r);
                if(r['status'] == "sukses"){
                    Customsukses("SRT-MSK", "001", r['pesan'], "proses");
                    setTimeout(function(){
                        $("#proses").html("");
                    },3000);
                    scrolltop();
                    
                }else{
                    Customerror("SRT-MSK", "001", r['pesan'], "proses");
                    scrolltop();
                }

            },
            error: function(er){
                console.log(er);
            }
        })
    }
}

function SubmitData(){
    if(Validasi() != false){
        var iData = new FormData($("#FormData")[0]);
        $.ajax({
            type : "POST",
            url : "<?= base_url('surat_masuk/save/') ?>",
            processData : false,
			contentType : false,
			chace : false,
            data : iData,
            success: function(res){
                var r = JSON.parse(res);
                if(r['status'] == "sukses"){
                    Customsukses("SRT-MSK", "001", r['pesan'], "proses");
                    scrolltop();
                    Clear();
                    getNomorSurat();
                }else{
                    Customerror("SRT-MSK", "001", r['pesan'], "proses");
                    scrolltop();
                }

            },
            error: function(er){
                console.log(er);
            }
        })
    }
}

function ClearModal(){
    $("#close_modal").trigger("click");
    $(".modal-title").html("");
    $("#ModalDetail").html("");
    $(".modal-footer").hide()
}

function SubmitDelete(){
    var iData = $("#FormDelete").serialize();
    $.ajax({
        type : "POST",
        url : "<?= base_url('surat_masuk/delete/') ?>",
        data : iData,
        success: function(res){
            var r = JSON.parse(res);
            console.log(r);
            if(r['status'] == "sukses"){
                Customsukses("SRT-MSK", "001", r['pesan'], "proses");
                LoadData();
                scrolltop();
                ClearModal();
            }else{
                Customerror("SRT-MSK", "001", r['pesan'], "proses");
                scrolltop();
                ClearModal();
            }
        },
        error : function(er){
            console.log(er);
        }
    })
}

</script>