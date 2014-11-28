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
		<br><br>
	</div>


	<? if (count($institutes)) { ?>
		Heimateinrichtungen

		<div id="institutes" >	
			<? foreach ($institutes as $entry) { ?>
			 	<input type="checkbox" class="institutes" value="<?= $entry['id'] ?>"> <?= $entry['name'] ?><br>
			<? } ?>
			<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Auswahl vergleichen</a>

		</div><br><br>


	<? } ?>

	
		Rechtestufe

		<div id="perms" >	
			<input type="checkbox" class="perms" value="dozent"> dozent<br>
			<input type="checkbox" class="perms" value="tutor"> tutor<br>
			<input type="checkbox" class="perms" value="autor"> autor<br>
			<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Auswahl vergleichen</a>

		</div><br><br>

	


	<? if (count($seminar_classes)) { ?>
		Veranstaltungsart

		<div id="seminar_classes" >	
			<? foreach ($seminar_classes as $entry) { ?>
			 	<input type="checkbox" class="seminar_classes" value="<?= $entry['id'] ?>"> <?= $entry['name'] ?><br>

			<? } ?>
			<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Auswahl vergleichen</a>

		</div><br><br>


	<? } ?>

</div>
			

	
<div id="content">	

    <div id="select">
	<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Lizenzarten</a>
	<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Meldungen</a>
	<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Semestervergleich</a>

    </div>
	
    <div id="charts_alone">
	<div id="container_vergleich" style="min-width: 410px; height: 450px; max-width: 1800px; margin: 0 auto"></div>

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

    $('#container_vergleich').highcharts({
         chart: {
            type: 'column'
        },

        title: {
            text: 'Vergleich: Lizenzen nach Einrichtungen'
        },

        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($compared_institutes as $ci){
				echo "'" . $ci[name] . "',";
			}

			echo "]"
              ?>   

        },

        yAxis: {
            min: 0,
            title: {
                text: 'Gesamtzahl hochgeladener Dokumente'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: 0,
            verticalAlign: 'bottom',
            y: 40,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
              }
        },
        series: [

	 <? 
		$prot = array_keys($plugin->get_licenses());

		for($i=0; $i < count($prot); $i++){

			echo "{ name: '". $prot[$i] . "', data: [";
			foreach ($compared_institutes as $ci){
				if ($institute_results[$ci[name]][$prot[$i]]){
					echo $institute_results[$ci[name]][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		

	?>
           ]
    });
 
	 

});



</script>

