	
$(document).ready(function() 
{    
   	Metronic.init(); // init metronic core componets
   	Layout.init(); // init layout
   	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
   	Index.init();   
   	Index.initDashboardDaterange();
   	Index.initJQVMAP(); // init index page's custom scripts
   	Index.initCalendar(); // init index page's custom scripts
   	Index.initCharts(); // init index page's custom scripts
   	Index.initChat();
   	Index.initMiniCharts();
   	Tasks.initDashboardWidget();
   	
   	var currentUrl = document.URL;
   	var arrUrl = currentUrl.split('/admin/');
	var page = arrUrl[1];
   	if( page != '' )
   	{
   		if( page == 'dashboard' )
   		{
   			$('#listDashboard').addClass('start active open');
   			$('#listDashboard a span:last-child').addClass('selected');
   		}
   		else if( page == 'manageCustomers' || page == 'manageConsumers' )
   		{
   			$('#listUser').addClass('start active open');
   			$('#listUser a span:nth-child(3)').addClass('selected');
   			$('#listUser a span:last-child').addClass('open');
   		}
   		else if( page == 'manageSalons' )
   		{
   			$('#listSalon').addClass('start active open');
   			$('#listSalon a span:last-child').addClass('selected');
   		}
   		else if( page == 'managePayments' )
   		{
   			$('#listPayment').addClass('start active open');
   			$('#listPayment a span:last-child').addClass('selected');
   		}
   		else if( page == 'manageAppointments' )
   		{
   			$('#listAppointment').addClass('start active open');
   			$('#listAppointment a span:last-child').addClass('selected');
   		}
   		else if( page == 'manageAdvertisements' )
   		{
   			$('#listAdvertisement').addClass('start active open');
   			$('#listAdvertisement a span:last-child').addClass('selected');
   		}
   		else if( page == 'managePaymentGateways' )
   		{
   			$('#listPaymentGateway').addClass('start active open');
   			$('#listPaymentGateway a span:last-child').addClass('selected');
   		}
   		
   	}
});





function changeStatus(id, currentStatus)
{
	var arrId = id.split('-');
	id = arrId[1];
	$.ajax({
   		 url: '{{ path("salon_solution_admin_changeStatus") }}',
   		 type: 'POST',
   		 data: {id:id, currentStatus:currentStatus, objectType:'User'},
   		 success:function(updatedStatus)
   		 {
			$("#divStatus-"+id).html(updatedStatus);
   		 }
   	});
}

function updateProfile()
	{
		var formData = $('#frmEditProfile').serialize();

		$.ajax({
	   		 url: '{{ path("salon_solution_admin_updateProfile") }}',
	   		 type: 'POST',
	   		 data: formData,
	   		 success:function(result)
	   		 {
				location.reload();
	   		 }
	   	});
	}

	function changePassword()
	{
		var formData = $('#frmChangePassword').serialize();
		$.ajax({
	   		 url: '{{ path("salon_solution_admin_changePassword") }}',
	   		 type: 'POST',
	   		 data: formData,
	   		 success:function(result)
	   		 {
				if( result == 'OLD_MISMATCH' )
				{
					$("#divFlashMessage").removeClass('msgSuccess');
					$("#divFlashMessage").addClass('msgError');
					$("#divFlashMessage").html("The Current Password entered by you is wrong.");
					$("#oldPassword").focus();
					return false;
				}
				else if( result == 'NEW_MISMATCH' )
				{
					$("#divFlashMessage").removeClass('msgSuccess');
					$("#divFlashMessage").addClass('msgError');
					$("#divFlashMessage").html("The password does not match.");
					$("#repeatPassword").focus();
					return false;
				}
				else
				{
					$("#divFlashMessage").removeClass('msgError');
					$("#divFlashMessage").addClass('msgSuccess');
					$("#divFlashMessage").html("Your password has been changed successfully.");
					location.reload();
				}
	   		 }
	   	});
	}



function cancel()
{
    $("#divDelete").hide();
}

function showDeleteBox(url)
{
    $("#divDelete").show();
    $("#btnDel").attr('href', url);
}


