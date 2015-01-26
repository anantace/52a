

<? use Studip\LinkButton; ?>

<div id="sidebar">

	<div>
		<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Aktualisieren</a>
		<br><br>
	</div>


	<? include $this->plugin->getPluginPath() . '/includes/sidebar.html';  ?>

</div>
			

	
<div id="content">	

    <div id="select">
	<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Lizenzarten</a>
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show/uploads/')?>'" class="button">Uploads und Meldungen</a>
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show/semCompare/')?>'" class="button">Semestervergleich</a>
	<input type="checkbox" id="group_licenses">Lizenzen gruppieren<br>

    </div>
	
    <div id="charts">
	<div id="container_anteil_gesamt" style="min-width: 410px; height: 400px; max-width: 1200px; margin: 0 auto"></div>
	<div id="container_anteil_bekannt" style="min-width: 410px; height: 400px; max-width: 1200px; margin: 0 auto"></div>
	<div id="container_anteil_gesamt_grouped" style="min-width: 410px; height: 400px; max-width: 1200px; margin: 0 auto"></div>
	<div id="container_anteil_bekannt_grouped" style="min-width: 410px; height: 400px; max-width: 1200px; margin: 0 auto"></div>

    </div>

    <div id="tables"">
	
    	<? if (count($licenses)) { ?>
   	<table class="default collapsable">
		<tr>
			<th>Anzahl</th>
    			<th>Lizenz</th>    
			<th>ID</th>
			<th>Anteil ges.</th>
			<th>Anteil geklärter Lizenzen</th>
		</tr>   
     
	<? foreach ($licenses as $entry) { ?>
		<tr>
			<td> <?= $entry['count'] ?> </td>
			<td> <?= $plugin->get_license_shortened($entry['prot']) ?> </td>
			<td> <?= $entry['prot'] ?> </td>
			<td> <?= round($entry['count']/$document_sum * 100, 2) ?>% </td>
			<? if($entry['prot'] != '2'){ ?>
		     		<td> <?= round($entry['count']/($document_sum_known_licenses) * 100, 2) ?>% </td>
			<? } else ?>
		   		<td></td>
		
		</tr>	

	<? } ?>

	<tr>
		<td> <?= $document_sum ?> </td>
		<td> Gesamt </td>
		<td>  </td>
		<td>  </td>
		<td>  </td>


	</tr>	

    	</table>
	<? } ?>

    	</div>


			

<script>



function reloadData() { 

    		var arr_inst='';
    		$('.institutes:checked').each(function() {
             		arr_inst+=$(this).val()+" "
    		}); 
        				
    		var arr_perms='';
    		$('.perms:checked').each(function() {
        		arr_perms+=$(this).val()+" "
    		}); 

    		var arr_sem_classes='';
    		$('.seminar_classes:checked').each(function() {
        		arr_sem_classes+=$(this).val()+" "
    		}); 

		if (arr_inst == ""){
			arr_inst = "all";	
		}
		if (arr_perms == ""){
			arr_perms = "all";	
		}
		if (arr_sem_classes == ""){
			arr_sem_classes = "all";	
		}

		window.location = "<?= $controller->url_for('/show/index/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    
	}

function compareInstitutes() { 

    		var arr_inst='';
    		$('.institutes:checked').each(function() {
             		arr_inst+=$(this).val()+" "
    		}); 
        				
    		var arr_perms='';
    		$('.perms:checked').each(function() {
        		arr_perms+=$(this).val()+" "
    		}); 

    		var arr_sem_classes='';
    		$('.seminar_classes:checked').each(function() {
        		arr_sem_classes+=$(this).val()+" "
    		}); 

		if (arr_inst == ""){
			arr_inst = "all";	
		}
		if (arr_perms == ""){
			arr_perms = "all";	
		}
		if (arr_sem_classes == ""){
			arr_sem_classes = "all";	
		}

		window.location = "<?= $controller->url_for('/show/instCompare/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    

    
	}

function compareFaks() { 

    		var arr_inst='';
    		$('.institutes:checked').each(function() {
             		arr_inst+=$(this).val()+" "
    		}); 
        				
    		var arr_perms='';
    		$('.perms:checked').each(function() {
        		arr_perms+=$(this).val()+" "
    		}); 

    		var arr_sem_classes='';
    		$('.seminar_classes:checked').each(function() {
        		arr_sem_classes+=$(this).val()+" "
    		}); 

		if (arr_inst == ""){
			arr_inst = "all";	
		}
		if (arr_perms == ""){
			arr_perms = "all";	
		}
		if (arr_sem_classes == ""){
			arr_sem_classes = "all";	
		}

		window.location = "<?= $controller->url_for('/show/fakCompare/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    

    
	}




function comparePerms() { 

    		var arr_inst='';
    		$('.institutes:checked').each(function() {
             		arr_inst+=$(this).val()+" "
    		}); 
        				
    		var arr_perms='';
    		$('.perms:checked').each(function() {
        		arr_perms+=$(this).val()+" "
    		}); 

    		var arr_sem_classes='';
    		$('.seminar_classes:checked').each(function() {
        		arr_sem_classes+=$(this).val()+" "
    		}); 

		if (arr_inst == ""){
			arr_inst = "all";	
		}
		if (arr_perms == ""){
			arr_perms = "all";	
		}
		if (arr_sem_classes == ""){
			arr_sem_classes = "all";	
		}
		
		window.location = "<?= $controller->url_for('/show/permsCompare/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    
	}


function compareSemClasses() { 

    		var arr_inst='';
    		$('.institutes:checked').each(function() {
             		arr_inst+=$(this).val()+" "
    		}); 
        				
    		var arr_perms='';
    		$('.perms:checked').each(function() {
        		arr_perms+=$(this).val()+" "
    		}); 

    		var arr_sem_classes='';
    		$('.seminar_classes:checked').each(function() {
        		arr_sem_classes+=$(this).val()+" "
    		}); 

		if (arr_inst == ""){
			arr_inst = "all";	
		}
		if (arr_perms == ""){
			arr_perms = "all";	
		}
		if (arr_sem_classes == ""){
			arr_sem_classes = "all";	
		}

		window.location = "<?= $controller->url_for('/show/semClassCompare/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    

    
	}

function hideinstOF(){
	var instOfFak = document.getElementsByClassName("institutesOfFak");
	for (var i = 0; i < instOfFak.length; i++) {
   	 	instOfFak[i].style.display = 'none';
	}
}


$(function () {

    var instOfFak = document.getElementsByClassName("institutesOF");
    for (var i = 0; i < instOfFak.length; i++) {
   	 	instOfFak[i].onclick = function() {
			var institutes = document.getElementsByClassName("institutesOfFak" + this.title);
 			for (var j = 0; j < institutes.length; j++) {
				if(institutes[j].style.display == 'none'){
					institutes[j].style.display = 'block';
				} else institutes[j].style.display = 'none';
			}
		}
	}


    var fakultaeten = document.getElementsByClassName("institutes");
    for (var i = 0; i < fakultaeten.length; i++) {
   	 	fakultaeten[i].onchange = function() {
			var institutes = document.getElementsByClassName("institutesOfFak" + this.value)[0].childNodes;
				for (var j = 0; j < institutes.length; j++) {
					
					if(this.checked){
						institutes[j].checked = true;
					} else institutes[j].checked = false;		
				}
		}			
	}


    var group = document.getElementById('group_licenses');
    group.onchange = function() {
	if(this.checked){
		
		document.getElementById('container_anteil_gesamt_grouped').style.display = "block";
		document.getElementById('container_anteil_gesamt').style.display = "none";
		document.getElementById('container_anteil_bekannt').style.display = "none";
		document.getElementById('container_anteil_bekannt_grouped').style.display = "block";

	} else{
		
		document.getElementById('container_anteil_gesamt_grouped').style.display = "none";
		document.getElementById('container_anteil_gesamt').style.display = "block";
		document.getElementById('container_anteil_bekannt').style.display = "block";
		document.getElementById('container_anteil_bekannt_grouped').style.display = "none";

	}
    }



    $('#container_anteil_gesamt').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: 'Anteil der Lizenzen insgesamt (seit 10.10.2014)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
	series: [{
            type: 'pie',
            name: 'Anteil gesamt',
            data: [
                <? 
		  foreach($licenses as $i){
                                echo "['".$plugin->get_license_shortened($i[prot])."', ". round($i[count]/$document_sum * 100, 2)."],";
		/**.$plugin->get_license_shortened(**/
                         }
                ?>         
            ]
        }]
    });


    $('#container_anteil_bekannt').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: 'Anteil der geklärten Lizenzen (seit 10.10.2014)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
	series: [{
            type: 'pie',
            name: 'Anteil',
            data: [
                <? 
		  foreach($licenses as $i){
			     if($i['prot'] != '2'){
                                echo "['".$plugin->get_license_shortened($i[prot])."', ". round($i[count]/$document_sum_known_licenses * 100, 2)."],";
			     }
                }
                ?>         
            ]
        }]
    });


    $('#container_anteil_gesamt_grouped').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: 'Anteil der Lizenzen insgesamt nach Lizenzgruppen (seit 10.10.2014)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
	series: [{
            type: 'pie',
            name: 'Anteil gesamt',
            data: [
                <? 
		 
		  foreach($licenses_grouped as $lg){
                                echo "['".$plugin->get_license_group($lg[prot], 1)."', ". round($lg[count]/$document_sum * 100, 2)."],";
                         }
                ?>         
            ]
        }]
    });

	$('#container_anteil_bekannt_grouped').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: 'Anteil der geklärten Lizenzen nach Lizenzgruppen (seit 10.10.2014)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
	series: [{
            type: 'pie',
            name: 'Anteil',
            data: [
                <? 
		  foreach($licenses_grouped as $lg){
			     if($lg['prot'] != '2'){
                                echo "['".$plugin->get_license_group($lg[prot], 1)."', ". round($lg[count]/$document_sum_known_licenses * 100, 2)."],";
			     }
                }
                ?>         
            ]
        }]
    });

    document.getElementById('container_anteil_gesamt_grouped').style.display = "none";
    document.getElementById('container_anteil_bekannt_grouped').style.display = "none";

    


});



</script>

