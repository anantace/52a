
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
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show')?>'" class="button">Lizenzarten</a>
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show/uploads/')?>'" class="button">Meldungen</a>
	<a tabindex="0" onclick="window.location.href = '<?= $controller->url_for('/show/semCompare/')?>'" class="button">Semestervergleich</a>


    </div>
	
    <div id="charts">
	<div id="container" style="min-width: 1100px; height: 400px; max-width: 2200px; margin: 0 auto"></div>
	<div id="container_vergleich_table" style="min-width: 310px; max-width: 400px; margin: 0 auto">
		<? if (count($queryResult)) { ?>
   	<table class="default collapsable">
		<tr>
			<th>Anzahl der Uploads</th>
    			<th>Semester</th>    

		</tr>   
     
	<? foreach ($queryResult as $entry) { ?>
		<tr>
			<td> <?= $entry['count'] ?> </td>
			<td> <?= $entry['sem'] ?> </td>
			
		</tr>	

	<? } ?>


    	</table>
	<? } ?>

	</div>

    </div>
</div>

<script>

function comparePerms(){
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

function compareSemClasses(){
	alert("Noch nicht eingebaut");
}
function compareInstitutes(){
	alert("Noch nicht eingebaut");

}
function compareFaks(){
	alert("Noch nicht eingebaut");

}


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

		window.location = "<?= $controller->url_for('/show/semCompare/')?>" + arr_inst + "/" + arr_perms + "/" + arr_sem_classes;

    
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
			foreach ($uploads_sem as $us){
				echo "'" . $us['sem'] . "',";
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

			echo "{ type: 'column',";
			echo " name: 'Uploads pro Semester', data: [";
			foreach ($uploads_sem as $us){
				echo $us['count'].",";
			}
			echo "], },";
		
		?>



	]
    });


});


</script>