<script>
function Customerror(kode_modul, no, catatan,id) {
  $("#"+id).html("<div class='alert alert-warning'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> ER-"+kode_modul+"."+no+" : "+catatan+"</div>");
}

function Customsukses(kode_modul, no, catatan, id) {
  $("#" + id).html("<div class='alert alert-success'> <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> SCS-" + kode_modul + "." + no + " : " + catatan + "</div>");
}
/** BASIC JS */

$("#Dari,#Sampai").datepicker({ "dateFormat": "yy-mm-dd", "autoclose": true, "changeMonth" : true,"changeYear" : true });

function StopLoad(){
    $(".LoadingState").hide();
}

function StartLoad(){
    $(".LoadingState").show();
}

$("#FormData").submit(function(e){
    e.preventDefault();
    Kirim();
})

function ValidasiForm(){
    var IdForm = ["Dari","Sampai","aksi"];
    var Ket = ["Isian Form Dari Tanggal Masih Kosong!","Isian Form Sampai Tanggal Masih Kosong!","Isian Form Aksi Masih Kosong!"];
    for(var i=0; i < IdForm.length; i++){
        if($("#"+IdForm[i]).val() == ""){
            Customerror("Export Nota Dinas", "00"+(i+1), Ket[i], "proses");
            $("#"+IdForm[i]).focus();
            return false;
        }
    }
}

function Kirim(){
    if(ValidasiForm() != false){
        var iData = {};
        iData['Dari'] = $("#Dari").val();
        iData['Sampai'] = $("#Sampai").val();
        iData['aksi'] = $("#aksi").val();
        var dts = JSON.stringify(iData);
        var dt = btoa(dts);
        window.open("<?= base_url('export_surat_masuk/proses?data=') ?>"+dt, '_blank');
        
        // $.ajax({
        //     url : "<?= base_url('export_surat_masuk/proses') ?>",
        //     type : "POST",
        //     data : iData,
        //     beforeSend: function(e){
        //         StartLoad();
        //     },
        //     success: function(data){
        //         console.log(data);
        //     },
        //     error: function(e){
        //         console.log(e);
        //         console.log("ss");
        //     }
        // })
    }
}



</script>