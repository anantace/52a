
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


    </div>
	
    <div id="charts">
	<div id="container" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>

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

		window.location = "<?= $controller->url_for('/show/semComparePerms/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    
	}



$(function () {


    $('#container').highcharts({
	 chart: {
	     zoomType: 'x'

	 },
        title: {
            text: 'Uploads pro Semester'
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
			foreach ($semester as $s){
				echo "'" . $s[name] . "',";
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
		    foreach($compared_perms as $cp){

			echo "{ type: 'column',";
			echo " name: '" . $cp . "', data: [";
			foreach ($semester as $s){
			    if ($uploads_sem[$cp][$s[name]]){
					echo $uploads_sem[$cp][$s[name]].",";
			    } else echo "0,";
			}
			echo "], },";
		    }
		?>



	]
    });


});


</script>