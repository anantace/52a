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
		<a tabindex="0" href="javascript:void(0);" onclick="comparePerms()" class="button">Aktualisieren</a>
		<br><br>
	</div>


	<? include $this->plugin->getPluginPath() . '/includes/sidebar-blank.html';  ?>
	

</div>
			

	
<div id="content">	

    <div id="select">
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show')?>'" class="button">Lizenzarten</a>
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show/uploads/')?>'" class="button">Uploads und Meldungen</a>
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show/semCompare/')?>'" class="button">Semestervergleich</a>
	<input type="checkbox" id="group_licenses">Lizenzen gruppieren<br>


    </div>
	
    <div id="charts_alone">
	<div id="container_vergleich_pro" style="min-width: 410px; height: 410px; max-width: 1800px; margin: 0 auto"></div>
	<div id="container_vergleich" style="min-width: 410px; height: 410px; max-width: 1800px; margin: 0 auto"></div>
	<div id="container_vergleich_pro_grouped" style="min-width: 410px; height: 410px; max-width: 1800px; margin: 0 auto"></div>
	<div id="container_vergleich_grouped" style="min-width: 410px; height: 410px; max-width: 1800px; margin: 0 auto"></div>


    </div>

    

			

<script>

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





$(function () {

	 var group = document.getElementById('group_licenses');
    group.onchange = function() {
	if(this.checked){
		
		document.getElementById('container_vergleich_pro').style.display = "none";
		document.getElementById('container_vergleich').style.display = "none";
		document.getElementById('container_vergleich_pro_grouped').style.display = "block";
		document.getElementById('container_vergleich_grouped').style.display = "block";

	} else{
		
		document.getElementById('container_vergleich_pro').style.display = "block";
		document.getElementById('container_vergleich').style.display = "block";
		document.getElementById('container_vergleich_pro_grouped').style.display = "none";
		document.getElementById('container_vergleich_grouped').style.display = "none";

	}
    }
 

    $('#container_vergleich').highcharts({
         chart: {
            type: 'column'
        },

        title: {
            text: 'Vergleich: Lizenzen nach Rechtestufe'
        },

        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($compared_perms as $cp){
				echo "'" . $cp . "',";
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
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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

			echo "{ name: '". $plugin->get_license_shortened($prot[$i]) . "', data: [";
			foreach ($compared_perms as $cp){
				if ($perms_results[$cp][$prot[$i]]){
					echo $perms_results[$cp][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		

	?>
           ]
    });
 
 $('#container_vergleich_pro').highcharts({
         chart: {
            type: 'column'
        },

        title: {
            text: 'Vergleich: Lizenzen prozentual nach Rechtestufe'
        },

        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($compared_perms as $cp){
				echo "'" . $cp . "',";
			}

			echo "]"
              ?>   

        },

        yAxis: {
            min: 0,
            title: {
                text: 'Anteil der Lizenzen an hochgeladenen Dokumente'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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
                stacking: 'percent'
            }
        },
        series: [

	 <? 
		$prot = array_keys($plugin->get_licenses());

		for($i=0; $i < count($prot); $i++){

			echo "{ name: '". $plugin->get_license_shortened($prot[$i]) . "', data: [";
			foreach ($compared_perms as $cp){
				if ($perms_results[$cp][$prot[$i]]){
					echo $perms_results[$cp][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		

	?>
           ]
    });

$('#container_vergleich_grouped').highcharts({
         chart: {
            type: 'column'
        },

        title: {
            text: 'Lizenzen nach Rechtestufe und Lizenzgruppen'
        },

        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($compared_perms as $cp){
				echo "'" . $cp . "',";
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
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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
		$prot = $plugin->get_license_group_ids();

		for($i=0; $i < count($prot); $i++){

			echo "{ name: '". $plugin->get_license_group($prot[$i], 1) . "', data: [";
			foreach ($compared_perms as $cp){
				if ($perms_results_grouped[$cp][$prot[$i]]){
					echo $perms_results_grouped[$cp][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		

	?>
           ]
    });
 
 $('#container_vergleich_pro_grouped').highcharts({
         chart: {
            type: 'column'
        },

        title: {
            text: 'Lizenzen prozentual nach Rechtestufe und Lizenzgruppen'
        },

        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($compared_perms as $cp){
				echo "'" . $cp . "',";
			}

			echo "]"
              ?>   

        },

        yAxis: {
            min: 0,
            title: {
                text: 'Anteil der Lizenzen an hochgeladenen Dokumente'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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
                stacking: 'percent'
            }
        },
        series: [

	 <? 
		$prot = $plugin->get_license_group_ids();

		for($i=0; $i < count($prot); $i++){

			echo "{ name: '". $plugin->get_license_group($prot[$i], 1) . "', data: [";
			foreach ($compared_perms as $cp){
				if ($perms_results_grouped[$cp][$prot[$i]]){
					echo $perms_results_grouped[$cp][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		

	?>
           ]
    });




	$('#container_vergleich_pie').highcharts({

        title: {
            text: 'Vergleich: Lizenzen nach Rechtestufe'
        },

        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($compared_perms as $cp){
				echo "'" . $cp . "',";
			}

			echo "]"
              ?>   

        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        
        series: [

	 <? 
		$prot = array_keys($plugin->get_licenses());
		$count_pie = 1;

		foreach ($compared_perms as $cp){
			echo "{ type: 'pie', name: '". $cp . "', data: [";

			for($i=0; $i < count($prot); $i++){

				echo "['". $plugin->get_license_shortened($prot[$i]) . "', ";
					if ($perms_results[$cp][$prot[$i]]){
						echo $perms_results[$cp][$prot[$i]] . "],";
					} else echo "0],";
			}
			
			
			echo "], center: [";
			echo($count_pie*400-100);
			echo " , 100], size: 200, showInLegend: true, dataLabels: { enabled: false }";
			echo "},";
			$count_pie ++;
		}


	?>
           ]
    });

	document.getElementById('container_vergleich_pro_grouped').style.display = "none";
	document.getElementById('container_vergleich_grouped').style.display = "none";

});



</script>

