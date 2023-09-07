<script>
$(document).ready(function(){
    <?php if(empty($this->uri->segment(2)) || $this->uri->segment(2) == "index"){ ?>
        LoadData();
    <?php } ?>
    <?php if($this->uri->segment(2) == "tambah" OR $this->uri->segment(2) == "edit"){ ?>
        SearchForm(); 
    <?php } ?>
    
	
});



function GetNomorSurat(){
    var Kode = $("#KodeJenisSurat").val();
    var TglSurat = $("#TglSurat").val();
    console.log(Kode);
        $.ajax({
            type : "POST",
            url : "<?= base_url('surat_keluar/get_nomor_surat/') ?>",
            data : "Kode="+Kode+"&TglSurat="+TglSurat,
            success : function(res){
                var r = JSON.parse(res);
                if(r['status'] == "sukses"){
                    $("#NoSurat").val(r['pesan']);
                }else{
                    Customerror("REQ-SURAT", "001", $res['pesan'],"proses");
                }
            },
            error: function(er){
                console.log(er);
            }
        })
}

function SearchForm() {
	$('.select-jenis').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Jenis Surat',
    });
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

function get_tgl_indo(tgl){
    var bulan = ["",'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    var spl = tgl.split("-");
    return spl[2]+" "+bulan[parseInt(spl[1])]+" "+spl[0];
}

function LoadData(page){
    page = page != undefined ? page : 1;
    
    var iData = $("#Filter").serialize();
    $.ajax({
        type : "POST",
        url : "<?= base_url('surat_keluar/show_data/') ?>",
        data : "Page="+page+"&"+iData,
        beforeSend: function(){
            StartLoad();
        },
        success: function(r){
            var res = JSON.parse(r);
            if(parseInt(res['jumlah_data']) > 0){
                var No = parseInt(res['NoAwal']);
                var html = "";
                var NoSrt = "";
                for(var i=0; i < res['data'].length; i++){
                    var iData = res['data'][i];
                    
                    html += "<tr>";
                        html += "<td class='text-center'>"+No+"</td>";
                        html += "<td>";
                            html += iData['NoSurat']+"<br><small>No Dokumen : <b>"+iData['NoDokumen']+"</b></small><br><small>Kepada : <b>"+iData['Kepada']+"</b></small>";
                        html += "</td>";
                        html += "<td>";
                            html += get_tgl_indo(iData['TglSurat'])+"<br><small>Tanggal Approve : <b>"+get_tgl_indo(iData['TglCreateApp'])+"</b></small>";
                        html += "</td>";
                       
                        html += "<td>";
                            html += iData['Perihal']+"<br><small>Keterangan : <b>"+iData['Keterangan']+"</b></small>";
                        html += "</td>";
                        html += "<td>";
                            html += iData['Perihal']+"<br><small>Request By : <b>"+iData['Authorss']+"</b></small><br><small>Approve By : <b>"+iData['AuthorssApp']+"</b></small>";
                        html += "</td>";
                        html += "<td>";
                            if(iData['Status'] == "0"){
                                html += "<label class='label label-warning'>Waiting List</label>";
                            }else{
                                html += "<label class='label label-success'>Terarsip</label>";
                            }
                        html += "</td>";
                        html += "<td class='text-center'><div class='btn-group'>";
                                if(iData['Status'] != "0"){
                                    html += "<a target='_blank' href='<?= base_url() ?>load_data/surat_keluar/"+iData['Id']+"' class='btn btn-success btn-xs' data-toggle='tooltip' title='lihat surat'><i class='fa fa-file'></i></a>";
                                }
                                <?php if($this->session->userdata('KodeLevel') != "2"){ ?>
                                    html += "<a href='<?= base_url() ?>surat_keluar/edit/"+iData['Id']+"' class='btn btn-primary btn-xs' data-toggle='tooltip' title='ubah data'><i class='fa fa-edit'></i></a><a href='javascript:void(0)' onclick=\"HapusData('"+iData['Id']+"')\"  class='btn btn-danger btn-xs' data-toggle='tooltip' title='hapus data'><i class='fa fa-trash-o'></i></a>";
                                <?php } ?>
                        html += "</div></td>";
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
$("#TglSurat").datepicker({ dateFormat: "yy-mm-dd", "autoclose": true });
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

function Validasi(){
    var iForm = ["Kepada","Perihal","TglSurat","KodeJenisSurat","File"];
    var iKet = ["Kepada belum lengkap!","Perihal belum lengkap","Tanggal surat belum dipilih","Kode jenis belum dipilih","File belum dipilih"];
    for(i=0; i < iForm.length; i++){
        if($("#"+iForm[i]).val() == ""){
            Customerror("SRT-KLR", "001", iKet[i],"proses");
            scrolltop();
            $("#"+iForm[i]).focus();
            return false;
        }
    }
}

function ValidasiUpdate(){
    var iForm = ["Kepada","Perihal","TglSurat","KodeJenisSurat"];
    var iKet = ["Kepada belum lengkap!","Perihal belum lengkap","Tanggal surat belum dipilih","Kode jenis belum dipilih"];
    for(i=0; i < iForm.length; i++){
        if($("#"+iForm[i]).val() == ""){
            Customerror("SRT-KLR", "001", iKet[i],"proses");
            scrolltop();
            $("#"+iForm[i]).focus();
            return false;
        }
    }
}

function Clear(){
    $(".FormInput").val("");
    $("#KodeJenisSurat").val(null);
    $(".select-jenis").trigger("change");

}

function SubmitDataUpdate(){
    if(ValidasiUpdate() != false){
        var iData = new FormData($("#FormDataUpdate")[0]);
        $.ajax({
            type : "POST",
            url : "<?= base_url('surat_keluar/update/') ?>",
            processData : false,
			contentType : false,
			chace : false,
            data : iData,
            success: function(res){
                var r = JSON.parse(res);
                if(r['status'] == "sukses"){
                    Customsukses("SRT-KLR", "001", r['pesan'], "proses");
                    setTimeout(function(){
                        $("#proses").html("");
                        location.reload();
                    },3000);
                    scrolltop();
                    
                }else{
                    Customerror("SRT-KLR", "001", r['pesan'], "proses");
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
            url : "<?= base_url('surat_keluar/save/') ?>",
            processData : false,
			contentType : false,
			chace : false,
            data : iData,
            success: function(res){
                var r = JSON.parse(res);
                console.log(r);
                if(r['status'] == "sukses"){
                    Customsukses("SRT-KLR", "001", r['pesan'], "proses");
                    scrolltop();
                    Clear();
                }else{
                    Customerror("SRT-KLR", "001", r['pesan'], "proses");
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
        url : "<?= base_url('surat_keluar/delete/') ?>",
        data : iData,
        success: function(res){
            var r = JSON.parse(res);
            console.log(r);
            if(r['status'] == "sukses"){
                Customsukses("SRT-KLR", "001", r['pesan'], "proses");
                LoadData();
                scrolltop();
                ClearModal();
            }else{
                Customerror("SRT-KLR", "001", r['pesan'], "proses");
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