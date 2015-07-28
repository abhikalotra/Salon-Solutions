	$.validator.setDefaults({
		submitHandler: function() {
		  $( "#signupForm" ).submit();
		}
	});
	
	$().ready(function() {
		
		//alert("sda");
		// validate the comment form when it is submitted
	//	$("#commentForm").validate();

		// validate signup form on keyup and submit
		$("#signupForm").validate({
			rules: {
				firstName: "required",
				lastName: "required",
				username: {
					required: true,
					minlength: 5
				},
				password: {
					required: true,
					minlength: 6
				},
				confirm_password: {
					required: true,
					minlength: 6,
					equalTo: "#password"
				},
				email: {
					required: true,
					email: true
				},
				salonName: {
					required: true,
					minlength: 4
				},
				domain: {
					required: true,
					minlength: 4
				},
				country: "required",
				state: "required",
				address: "required",
				city: "required",	
				zip: {
					required: true,
					maxlength: 6
				},
				mobile: {
					required: true,
					maxlength: 10
				},		
				landline: "required",	
				photo: "required",
				logo: "required",				
				agree: "required"
			},
			
			messages: {
				firstName: "Please enter your firstName",
				lastName: "Please enter your lastName",
				username: {
					required: "Please enter a username",
					minlength: "Your username must consist of at least 5 characters"
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				},
				confirm_password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long",
					equalTo: "Please enter the same password as above"
				},
				email: "Please enter a valid email address",
				salonName: {
					required: "Please provide a Salon Name",
					minlength: "Your Salon Name must be at least 4 characters long"
				},
				domain: {
					required: "Please provide a Domain Name",
					minlength: "Your Domain Name must be at least 4 characters long"
				},
				country: "Please enter your Country",
				state: "Please enter your Province",
				address: "Please enter your Address",
				city: "Please enter your City",
				zip: {
					required: "Please provide a Zip ",
					minlength: "Your Zip Code Not more than 6 characters long"
				},	
				mobile: {
					required: "Please provide a Mobile No.",
					minlength: "Your Mobile Code Not more than 10 characters long"
				},	
				landline: "Please enter your Landline",			
				photo: "Please enter your Photo",
				logo: "Please enter your Logo",
				agree: "Please accept our policy"
			}
		});

		// propose username by combining first- and lastName
		$("#username").focus(function() {
			var firstName = $("#firstName").val();
			var lastName = $("#lastName").val();
			if (firstName && lastName && !this.value) {
				this.value = firstName + "." + lastName;
			}
		});

		//code to hide topic selection, disable for demo
		var newsletter = $("#newsletter");
		// newsletter topics are optional, hide at first
		var inital = newsletter.is(":checked");
		var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
		var topicInputs = topics.find("input").attr("disabled", !inital);
		
		
	});
