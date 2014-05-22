function progress(percent, $element) {
	var progressBarWidth = percent * $element.width() / 100;
	$element.find('div').animate({ width: progressBarWidth }, 500).html(percent + "%&nbsp;");
}

$(document).ready(function(){
	var reload = 0;
	$('#text').text('Mise à jour des données en cours...');
	interval = setInterval(function (){
		$.get("avancement_script.php", function(data) {
			data = parseInt(data);
			if (data != 100)
			{
				reload = 1;
				if (data != 0)
				{
					progress(data, $('#progressbar'));
				}
			}
			else
			{
				clearInterval(interval);
				if (reload == 1)
				{
					location.reload();
				}
			}
		});
	}, 1000);
});