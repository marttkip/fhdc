<?php echo form_open("nurse/submit-doctors-notes", array("class" => "form-horizontal"));?>

  <div id="doctor_notes"></div>
                        
<?php echo form_close();?>




<script type="text/javascript">

  $(document).ready(function(){
      doctor_notes(<?php echo $visit_id;?>);
      
  });


function save_all_soap(visit_id){

     

      // start of saving rx
        var config_url = $('#config_url').val();
        var data_url = config_url+"/nurse/save_rx/"+visit_id;
        //window.alert(data_url);
         var doctor_notes_rx = $('#rx').val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url,
        data:{notes: doctor_notes_rx},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
          // window.alert("Rx Done notes saved successfully");
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
      // end of saving rx

      // start of saving plan
       
        var data_url_plan = config_url+"/nurse/save_plan/"+visit_id;
        //window.alert(data_url_plan);
        var doctor_notes_plan = $('#plan').val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url_plan,
        data:{notes: doctor_notes_plan},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
          // window.alert("Plan saved successfully");
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
      // end of saving plan

      // start of saving findings
       
        var data_url_findings = config_url+"/nurse/save_findings/"+visit_id;
        //window.alert(data_url_findings);
        var doctor_notes_findings = $('#findings').val();
        //document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url_findings,
        data:{notes: doctor_notes_findings},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
          // window.alert("Findings saved successfully");
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
      // end of saving findings

      // start of saving inestigations
        
        var data_url_investigations = config_url+"/nurse/save_investigation/"+visit_id;
        //window.alert(data_url_investigations);
         var doctor_notes_investigations = $('#investigations').val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url_investigations,
        data:{notes: doctor_notes_investigations},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
          // window.alert("Investigations notes saved successfully");
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
      // end of saving investgations

      // start of saving oc
        
        var data_url_oc = config_url+"/nurse/save_oc/"+visit_id;
        //window.alert(data_url_oc);
        var var_filled = $('#filled').val();//document.getElementById("vital"+vital_id).value;
        var var_missing = $('#missing').val();
        var var_decayed = $('#decayed').val();
        var var_soft_tissue = $('#soft_tissue').val();
        $.ajax({
        type:'POST',
        url: data_url_oc,
        data:{filled: var_filled,missing : var_missing, decayed : var_decayed, soft_tissue : var_soft_tissue},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
          // window.alert("O/C notes saved successfully");
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });

      // end of saving oc

      // start of saving histories
      
        var data_url_hist = config_url+"/nurse/save_histories/"+visit_id;
        //window.alert(data_url_hist);
         var doctor_notes_medical = $('#past_medical_hx').val();//document.getElementById("vital"+vital_id).value;
         var dental_notes = $('#past_dental_hx').val();
        $.ajax({
        type:'POST',
        url: data_url_hist,
        data:{notes: doctor_notes_medical, notes2: dental_notes },
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
          // window.alert("histories notes saved successfully");
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
      // end of saving histories

      // start of saving hpco
      
        var data_url_hpco = config_url+"/nurse/save_hpco/"+visit_id;
        //window.alert(data_url_hpco);
         var doctor_notes_hpco = $('#hpco').val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url_hpco,
        data:{notes: doctor_notes_hpco},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
          // window.alert("HP C/O notes saved successfully");
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
      // end of saving hpco

      // start of saving tca
      
        var data_url_tca = config_url+"/nurse/save_tca/"+visit_id;
        //window.alert(data_url_tca);
         var doctor_notes_tca = $('#tca').val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url_tca,
        data:{notes: doctor_notes_tca},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
           
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
      // end of saving tca

      window.alert("Notes saved successfully");
}






function doctor_notes(visit_id){
    var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  var config_url = $('#config_url').val();
  var url = config_url+"/nurse/doctor_notes/"+visit_id;
  
  if(XMLHttpRequestObject) {
    
    var obj = document.getElementById("doctor_notes");
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
        // window.alert("Dotors notes are saved");
        
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

</script>