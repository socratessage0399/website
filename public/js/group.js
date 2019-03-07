$(document).ready(function (){
	$("#account-settings button span").hide();
	$("#account-settings").on('submit', function (event) {
		event.preventDefault();
		$.ajax({
			url: $("#account-settings").attr("action"),
			type: "POST",
			data: new FormData(this),
			contentType: false,
			processData: false,
			beforeSend: function() {
				$('#account-settings button').prop('disabled', true);
				$("#account-settings button span").show();
			},
			success: function (response)
			{
				if (!response.errors)
				{
					if (response.done.image.length != 0) 
					{
						$("#account-settings .image-form input ").removeClass("is-invalid");
						$("#account-settings .image-form p").empty();
						$("#account-settings .supply-image img").hide();
						$("#account-settings .supply-image").html(response.done.image).fadeIn();
					}
					if (response.done.length != 0)
					{
						window.location.replace("/account");
					}
				} else {
					if (response.errors.image != 0)
					{
						$("#account-settings .image-form p").html(response.errors.image[0]).fadeIn();
						$("#account-settings .image-form input ").addClass("is-invalid");
					}
				}
				$("#account-settings")[0].reset();
				$("#account-settings button span").hide()
				$('#account-settings button').prop('disabled', false);
			},
			error: function (error)
			{
				conslog.log(error);
			}
		});
	});
});