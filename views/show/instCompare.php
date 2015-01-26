
<? use Studip\LinkButton; ?>


<div id="sidebar">

	<div>
		<a tabindex="0" href="javascript:void(0);" onclick="compareInstitutes()" class="button">Aktualisieren</a>
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
            text: 'Lizenzen nach Einrichtungen'
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
 
$('#container_vergleich_pro').highcharts({
         chart: {
            type: 'column'
        },

        title: {
            text: 'Lizenzen prozentual nach Einrichtungen'
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

$('#container_vergleich_grouped').highcharts({
         chart: {
            type: 'column'
        },

        title: {
            text: 'Lizenzen nach Einrichtungen und Lizenzgruppen'
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
			foreach ($compared_institutes as $cp){
				if ($institute_results_grouped[$cp['id']][$prot[$i]]){
					echo $institute_results_grouped[$cp['id']][$prot[$i]] . ",";
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
            text: 'Lizenzen prozentual nach Einrichtungen und Lizenzgruppen'
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
			foreach ($compared_institutes as $cp){
				if ($institute_results_grouped[$cp['id']][$prot[$i]]){
					echo $institute_results_grouped[$cp['id']][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		

	?>
           ]
    });

	document.getElementById('container_vergleich_pro_grouped').style.display = "none";
	document.getElementById('container_vergleich_grouped').style.display = "none";

	 

});



</script>

