<script type="text/javascript">
   var sel = document.getElementById('sel');
   sel.onchange = function() {
      var show = document.getElementById('show');
      show.innerHTML = this.value;
   }
</script>

<? use Studip\LinkButton; ?>


<div id="sidebar">

	<div>
		<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Aktualisieren</a>
	</div>


	<? if (count($institutes)) { ?>
		Heimateinrichtungen

		<div id="institutes" >	
			<? foreach ($institutes as $entry) { ?>
			 	<input type="checkbox" class="institutes" value="<?= $entry['id'] ?>"> <?= $entry['name'] ?><br>
			<? } ?>
		</div><br><br>


	<? } ?>

	
		Rechtestufe

		<div id="perms" >	
			<input type="checkbox" class="perms" value="dozent"> dozent<br>
			<input type="checkbox" class="perms" value="tutor"> tutor<br>
			<input type="checkbox" class="perms" value="autor"> autor<br>
		</div><br><br>

	


	<? if (count($seminar_classes)) { ?>
		Veranstaltungsart

		<div id="seminar_classes" >	
			<? foreach ($seminar_classes as $entry) { ?>
			 	<input type="checkbox" class="seminar_classes" value="<?= $entry['id'] ?>"> <?= $entry['name'] ?><br>

			<? } ?>
		</div><br><br>


	<? } ?>

</div>
			

	
<div id="content">	

    <div id="select">
	Hier können verschiedene Abfragen ausgewählt werden..

    </div>
	
    <div id="charts">
	<div id="container_anteil_gesamt" style="min-width: 410px; height: 400px; max-width: 1200px; margin: 0 auto"></div>
	<div id="container_anteil_bekannt" style="min-width: 410px; height: 400px; max-width: 1200px; margin: 0 auto"></div>
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
		<td> <?= $document_sum_known_licenses ?></td>
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

			
		alert(arr_inst + "/" + arr_perms + "/" + arr_sem_classes);
		window.location = "<?= $controller->url_for('/show/index/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    
	}


$(function () {


    $('#container_anteil_gesamt').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: 'Anteil der Lizenzen insgesamt (eingestellte Dokumente seit 10.10.2014)'
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
            text: 'Anteil der geklärten Lizenzen (eingestellte Dokumente seit 10.10.2014)'
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

	 
	 

});



</script>

