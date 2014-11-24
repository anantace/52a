<script src="<?= $plugin->getPluginURL() ?>/highcharts/js/highcharts.js"></script>
<script src="<?= $plugin->getPluginURL() ?>/highcharts/js/modules/exporting.js"></script>




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
		<td> <?= $entry['name'] ?> </td>
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


<div id="container_anteil_gesamt" style="min-width: 610px; height: 400px; max-width: 1200px; margin: 0 auto"></div>
<div id="container_anteil_bekannt" style="min-width: 610px; height: 400px; max-width: 1200px; margin: 0 auto"></div>



<? } ?>		

<script>
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
                                echo "['".$i[name]."', ". round($i[count]/$document_sum * 100, 2)."],";
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
                                echo "['".$i[name]."', ". round($i[count]/$document_sum_known_licenses * 100, 2)."],";
			     }
                }
                ?>         
            ]
        }]
    });


	 

});
</script>

