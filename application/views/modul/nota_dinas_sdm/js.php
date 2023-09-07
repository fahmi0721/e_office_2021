<script>

$(document).ready(function(){
    
    StopLoad();
    <?php if(empty($this->uri->segment(2)) || $this->uri->segment(2) == "index"){ ?>
        LoadData();
    <?php } ?>
    <?php if(!empty($this->uri->segment(2)) || $this->uri->segment(2) != "index"){ ?>
    SearchForm(); 
    <?php if($this->uri->segment(2) == "tambah"){ ?>
        getNomorSurat();
    <?php } ?>
    <?php if($this->uri->segment(2) == "view_disposisi"){ ?>
        // $("[data-toggle='tooltip']").tooltip();
    <?php } ?>
    $("[data-toggle='tooltip']").tooltip();
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

    $('.select-dari').select2({
		allowClear: true,
		ballowClear: true,
		theme: "bootstrap",
		placeholder: 'Pilih Dari',
    });
}

function getNomorSurat(){
    var Tahun = $("#Tahun").val() != undefined ? $("#Tahun").val() : "";
    var Dari = $("#Dari").val() != undefined ? $("#Dari").val() : "";
    $.ajax({
        type : "POST",
        url : "<?= base_url('nota_dinas/get_nomor_surat_sdm') ?>",
        data : "Tahun="+Tahun+"&Dari="+Dari,
        success : function(r){
            console.log(r);
            $("#NoSurat").val(r)
        },
        error : function(er){
            console.log(er);
        }
    })
}


/** BASIC JS */
$("#TglSurat,#Tgl").datepicker({ "dateFormat": "yy-mm-dd", "autoclose": true, });

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

function Validasi(){
    var iForm = ["TglSurat","Tahun","Kepada","Dari","NoSurat","Perihal","File"];
    var iKet = ["Tanggal Surat belum lengkap!","Tahun Belum dipilih","Kepada Yth belum dipilih", "Dari belum lengkap", "No Surat Dari belum lengkap", "Perihal belum lengkap", "File belum dipilih"];
    for(i=0; i < iForm.length; i++){
        if($("#"+iForm[i]).val() == ""){
            Customerror("NT-DNS", "001", iKet[i],"proses");
            scrolltop();
            $("#"+iForm[i]).focus();
            return false;
        }
    }
}


function Clear(){
    $(".FormInput").val("");
    $(".FormInput").val("");
    $('.select').trigger("change");
}

function SubmitData(){
    if(Validasi() != false){
        var iData = new FormData($("#FormData")[0]);
        $.ajax({
            type : "POST",
            url : "<?= base_url('nota_dinas/save_sdm/') ?>",
            processData : false,
			contentType : false,
			chace : false,
            data : iData,
            success: function(res){
                var r = JSON.parse(res);
                if(r['status'] == "sukses"){
                    Customsukses("NT-DNS", "001", r['pesan'], "proses");
                    scrolltop();
                    Clear();
                    getNomorSurat();
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

function ClearModal(){
    $("#close_modal").trigger("click");
    $(".modal-title").html("");
    $("#ModalDetail").html("");
    $(".modal-footer").hide()
}


</script>