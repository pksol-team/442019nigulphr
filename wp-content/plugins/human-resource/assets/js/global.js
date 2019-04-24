window.addEventListener("load", function (evt) {

	if (!window.jQuery) {
		var script = document.createElement("SCRIPT");
		script.src = "https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";
		script.type = "text/javascript";
		script.onload = function () {
			var $ = window.jQuery;
			front_script($);
		};
		document.getElementsByTagName("head")[0].appendChild(script);
	} else {
		front_script(jQuery);
	}

});


function front_script($) {

	$.getScript("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js")
		.done(function (script, textStatus) {


			var tables = $("#table-data").DataTable({
				searching: true,
				ordering: false,
				"lengthChange": false,
			});

			$(".s003").show();
			$(".loadingss").hide();

			$(".search-submit-form").submit(function (e) {

				e.preventDefault();

				var person_name = $("[name=person-name]").val() || "";
				var training_name = $("[name=training-name]").val() || "";
				var location = $("[name=location]").val() || "";

				if (person_name == "" && training_name == "" && location == "") {
					alert("Please enter some value");
				} else {

					$("#talbe_data").show();

					tables
						.column(1).search(person_name)
						.column(2).search(location)
						.column(6).search(training_name)
						.draw();

				}

			});
		});

}