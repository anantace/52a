
<? use Studip\LinkButton; ?>


<div id="sidebar">
<div>
		<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Aktualisieren</a>
		<br><br>
	</div>


	<? include $this->plugin->getPluginPath() . '/includes/sidebar-blank.html';  ?>

</div>


<div id="content">	

    <div id="select">
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show')?>'" class="button">Lizenzarten</a>
	<a tabindex="0" href="javascript:void(0);" onclick="reloadData()" class="button">Meldungen</a>
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show/semCompare/')?>'" class="button">Semestervergleich</a>
	<input type="checkbox" id="group_licenses">Lizenzen gruppieren</input>
	<input type="checkbox" id="week">wöchentlich</input>

    </div>
	
    <div id="charts">
	<div id="container" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>
	<div id="container_grouped" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>
	<div id="container_week" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>
	<div id="container_grouped_week" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>

	<div id="container-meldungen" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>
	<div id="container-meldungen-week" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>



    </div>
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

		window.location = "<?= $controller->url_for('/show/uploads/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    
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
		if(document.getElementById('week').checked){

			document.getElementById('container_grouped_week').style.display = "block";
			document.getElementById('container_week').style.display = "none";
		} else {
			document.getElementById('container_grouped').style.display = "block";
			document.getElementById('container').style.display = "none";

		}

	} else{
		if(document.getElementById('week').checked){
			document.getElementById('container_grouped_week').style.display = "none";
			document.getElementById('container_week').style.display = "block";
		} else {
			document.getElementById('container_grouped').style.display = "none";
			document.getElementById('container').style.display = "block";
		}

	}
    }

    var week = document.getElementById('week');
    week.onchange = function() {
	if(this.checked){
		if(document.getElementById('group_licenses').checked){
			document.getElementById('container_grouped_week').style.display = "block";
			document.getElementById('container_grouped').style.display = "none";
			document.getElementById('container-meldungen-week').style.display = "block";
			document.getElementById('container-meldungen').style.display = "none";

		} else {
			document.getElementById('container').style.display = "none";
			document.getElementById('container_week').style.display = "block";
			document.getElementById('container-meldungen-week').style.display = "block";
			document.getElementById('container-meldungen').style.display = "none";
		}

	} else{
		if(document.getElementById('group_licenses').checked){
			document.getElementById('container_grouped_week').style.display = "none";
			document.getElementById('container_grouped').style.display = "block";
			document.getElementById('container-meldungen-week').style.display = "none";
			document.getElementById('container-meldungen').style.display = "block";
		} else {
			document.getElementById('container').style.display = "block";
			document.getElementById('container_week').style.display = "none";
			document.getElementById('container-meldungen-week').style.display = "none";
			document.getElementById('container-meldungen').style.display = "block";
		}
	}
    }



    $('#container').highcharts({
	 chart: {
	     zoomType: 'x'
	 },
        title: {
            text: 'Uploads nach Lizenztypen'
        },
	 legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($months as $m){
				echo "'" . $m['name'] . "',";
			}

			echo "]"
              ?>   


        },
        labels: {
            items: [{
                html: '',
                style: {
                    left: '50px',
                    top: '25px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                }
            }]
        },
        series: [
		<?
		$prot = array_keys($plugin->get_licenses());

		for($i=0; $i < count($prot); $i++){
			echo "{ type: 'column',";
			echo " name: '". $plugin->get_license_shortened($prot[$i]) . "', data: [";
			foreach ($months as $m){
				if ($uploads[$m['id']][$prot[$i]]){
					echo $uploads[$m['id']][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		?>



	 {
            type: 'spline',
            name: 'Uploads gesamt',
            data: [ 

			
		<? foreach ($uploads_total as $ut){
			echo $ut . ",";	
			}
		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }]
    });

    $('#container_week').highcharts({
	 chart: {
	     zoomType: 'x'
	 },
        title: {
            text: 'Wöchentliche Uploads nach Lizenztypen'
        },
	 legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($weeks as $w){
				echo "'KW " . $w['id'] . "',";
			}

			echo "]"
              ?>   


        },
        labels: {
            items: [{
                html: '',
                style: {
                    left: '50px',
                    top: '25px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                }
            }]
        },
        series: [
		<?
		$prot = array_keys($plugin->get_licenses());

		for($i=0; $i < count($prot); $i++){
			echo "{ type: 'column',";
			echo " name: '". $plugin->get_license_shortened($prot[$i]) . "', data: [";
			foreach ($weeks as $w){
				if ($uploads_week[$w['id']][$prot[$i]]){
					echo $uploads_week[$w['id']][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		?>



	 {
            type: 'spline',
            name: 'Uploads gesamt',
            data: [ 

			
		<? foreach ($uploads_total_week as $ut){
			echo $ut . ",";	
			}
		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }]
    });


    $('#container_grouped').highcharts({
	 chart: {
	     zoomType: 'x'
	 },
        title: {
            text: 'Uploads nach Lizenzgruppen'
        },
	 legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($months as $m){
				echo "'" . $m['name'] . "',";
			}

			echo "]"
              ?>   


        },
        labels: {
            items: [{
                html: '',
                style: {
                    left: '50px',
                    top: '25px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                }
            }]
        },
        series: [
		<?
		$prot = $plugin->get_license_group_ids();

		for($i=0; $i < count($prot); $i++){
			echo "{ type: 'column',";
			echo " name: '". $plugin->get_license_group($prot[$i], 1) . "', data: [";
			foreach ($months as $m){
				if ($uploads_grouped[$m['id']][$prot[$i]]){
					echo $uploads_grouped[$m['id']][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		?>



	 {
            type: 'spline',
            name: 'Uploads gesamt',
            data: [ 

			
		<? foreach ($uploads_total as $ut){
			echo $ut . ",";	
			}
		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }]
    });

 $('#container_grouped_week').highcharts({
	 chart: {
	     zoomType: 'x'
	 },
        title: {
            text: 'Wöchentliche Uploads nach Lizenzgruppen'
        },
	 legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($weeks as $w){
				echo "'KW " . $w['id'] . "',";
			}

			echo "]"
              ?>   


        },
        labels: {
            items: [{
                html: '',
                style: {
                    left: '50px',
                    top: '25px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                }
            }]
        },
        series: [
		<?
		$prot = $plugin->get_license_group_ids();

		for($i=0; $i < count($prot); $i++){
			echo "{ type: 'column',";
			echo " name: '". $plugin->get_license_group($prot[$i], 1) . "', data: [";
			foreach ($weeks as $w){
				if ($uploads_grouped_week[$w['id']][$prot[$i]]){
					echo $uploads_grouped_week[$w['id']][$prot[$i]] . ",";
				} else echo "0,";
			}
			echo "], },";
		}
		?>



	 {
            type: 'spline',
            name: 'Uploads gesamt',
            data: [ 

			
		<? foreach ($uploads_total_week as $ut){
			echo $ut . ",";	
			}
		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }]
    });


       document.getElementById('container_grouped').style.display = "none";
	document.getElementById('container_grouped_week').style.display = "none";
	document.getElementById('container_week').style.display = "none";

});




$(function () {
    $('#container-meldungen').highcharts({
        title: {
            text: 'Uploads und Meldungen von Text nach §52a'
        },
	 legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($months as $m){
				echo "'" . $m['name'] . "',";
			}

			echo "]"
              ?>   


        },
        labels: {
            items: [{
                html: '',
                style: {
                    left: '50px',
                    top: '18px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                }
            }]
        },
        series: [
		<?
		


			echo "{ type: 'column',";
			echo " name: '". $plugin->get_license_shortened('6') . "', data: [";
			foreach ($months as $m){
				if ($uploads[$m['id']]['6']){
					echo $uploads[$m['id']]['6'] . ",";
				} else echo "0,";
			}
			echo "], },";

			
		
		?>



	 {
            type: 'spline',
            name: 'Abgeschlossene Meldungen',
            data: [ 
		<? 
			foreach ($months as $m){
				if ($reports[$m['id']]['1']){
					echo $reports[$m['id']]['1'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }, 
	{
            type: 'spline',
            name: 'Nicht abgeschlossene Meldungen',
            data: [ 
		<? 
			foreach ($months as $m){
				if ($reports[$m['id']]['0']){
					echo $reports[$m['id']]['0'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[4],
                fillColor: 'white'
            }
        },
	{
            type: 'spline',
            name: 'Meldungen mit Status 2',
            data: [ 
		<? 
			foreach ($months as $m){
				if ($reports[$m['id']]['2']){
					echo $reports[$m['id']]['2'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[5],
                fillColor: 'white'
            }
        },
	{
            type: 'spline',
            name: 'Meldungen mit Status 3',
            data: [ 
		<? 
			foreach ($months as $m){
				if ($reports[$m['id']]['3']){
					echo $reports[$m['id']]['3'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[6],
                fillColor: 'white'
            }
        }]
    });

$('#container-meldungen-week').highcharts({
        title: {
            text: 'Wöchentliche Uploads und Meldungen von Text nach §52a'
        },
	 legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        xAxis: {
            categories: 
		<?
			echo "[";
			foreach ($weeks as $w){
				echo "'" . $w['id'] . "',";
			}

			echo "]"
              ?>   


        },
        labels: {
            items: [{
                html: '',
                style: {
                    left: '50px',
                    top: '18px',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                }
            }]
        },
        series: [
		<?
			echo "{ type: 'column',";
			echo " name: '". $plugin->get_license_shortened('6') . "', data: [";
			foreach ($weeks as $w){
				if ($uploads_week[$w['id']]['6']){
					echo $uploads_week[$w['id']]['6'] . ",";
				} else echo "0,";
			}
			echo "], },";
		
		?>



	 {
            type: 'spline',
            name: 'Abgeschlossene Meldungen',
            data: [ 
		<? 
			foreach ($weeks as $w){
				if ($reports_week[$w['id']]['1']){
					echo $reports_week[$w['id']]['1'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }, 
	{
            type: 'spline',
            name: 'Nicht abgeschlossene Meldungen',
            data: [ 
		<? 
			foreach ($weeks as $w){
				if ($reports[$w['id']]['0']){
					echo $reports[$w['id']]['0'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[4],
                fillColor: 'white'
            }
        },
	{
            type: 'spline',
            name: 'Meldungen mit Status 2',
            data: [ 
		<? 
			foreach ($weeks as $w){
				if ($reports[$w['id']]['2']){
					echo $reports[$w['id']]['2'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[5],
                fillColor: 'white'
            }
        },
	{
            type: 'spline',
            name: 'Meldungen mit Status 3',
            data: [ 
		<? 
			foreach ($weeks as $w){
				if ($reports[$w['id']]['3']){
					echo $reports[$w['id']]['3'] . ",";
				} else echo "0,";
			}


		    echo "],";
		?>

            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[6],
                fillColor: 'white'
            }
        }]
    });

	document.getElementById('container-meldungen-week').style.display = "none";


});




</script>