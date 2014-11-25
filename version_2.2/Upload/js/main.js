// JavaScript Document
 var account = 0;
 var pass = 0;
 var strength_pass = 0;
 var email = 0;

$("#user").keyup(function (e) {
	var username = $(this).val();
	$.post('test.php', {'username': username}, function(data){
		$("#check_user").html(data);
	});
});

 $(document).ready(function() {
  $("#password2").keyup(validate);
  $('#password').keyup(function()
	{
		$('#password_strength').html(checkStrength($('#password').val()))
	})	
	
	
	
});

 document.getElementById("uploadBtn").onchange = function () {
    document.getElementById("uploadFile").value = this.value;
};

  $(document).ready(
  /* This is the function that will get executed after the DOM is fully loaded */
  function () {
    $( "#datepicker" ).datepicker({
      changeMonth: true,//this option for allowing user to select month
      changeYear: true //this option for allowing user to select from year range
    });
  } 
);

$(document).ready(function(){
			 
            $("#username").change(function(){
                 $("#message").html("checking...");
			
			             
            var username=$("#username").val();
			account = 0;
 
              $.ajax({
				  	async: false,
                    type:"post",
                    url:"process/check.php",
                    data:"username="+username,
                        success:function(data){
                        if(data==0){
							account = 1;
                            $("#message").html("<span id='create_account_check_button_success'><img src='css/images/user_av.png'></img></span>");
							
                        }
                        else{
							account = 0;
							$("#message").html("<span id='create_account_check_button_fail'>Username already taken</span>");
							
                        }
						
                    }
                 });
 
            });
			
 });	
	
	
	function checkStrength(password)
	{
		//initial strength
		var strength = 0;
		strength_pass = 0;
		
		console.log(account);
		
		//if the password length is less than 6, return message.
		if (password.length < 6) { 
			$('#password_strength').removeClass();
			$('#password_strength').addClass('short');
			strength_pass = 0;
			return '<span id="create_account_check_button_fail">Must be atleast 6 characters</span>' 
		}
		
		//length is ok, lets continue.
		
		//if length is 8 characters or more, increase strength value
		if (password.length > 7) strength += 1;
		
		//if password contains both lower and uppercase characters, increase strength value
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;
		
		//if it has numbers and characters, increase strength value
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1 ;
		
		//if it has one special character, increase strength value
		if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;
		
		//if it has two special characters, increase strength value
		if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
		
		//now we have calculated strength value, we can return messages
		
		//if value is less than 2
		if (strength < 2 )
		{
			$('#password_strength').removeClass();
			$('#password_strength').addClass('weak');
			strength_pass = 0;
			return '<span id="create_account_check_button_fail">Weak';			
		}
		else if (strength == 2 )
		{
			$('#password_strength').removeClass();
			$('#password_strength').addClass('good');
			strength_pass = 1;
			return '<span id="create_account_check_good_pass">Good'	;	
		}
		else
		{
			$('#password_strength').removeClass();
			$('#password_strength').addClass('strong');
			strength_pass = 1;
			return "<span id='create_account_check_button_success'><img src='css/images/user_av.png'></img></span>";
		} 
	}

  $(document).ready(
  
  /* This is the function that will get executed after the DOM is fully loaded */
  function () {
    $( "#datepicker" ).datepicker({
      changeMonth: true,//this option for allowing user to select month
      changeYear: true //this option for allowing user to select from year range
    });
  } 
);
function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(460)
                        .height(287);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

function validate() {
  var password1 = $("#password").val();
  var password2 = $("#password2").val();

    if(password1 == password2) {
       $("#validate-status").html("<span id='create_account_check_button_success'><img src='css/images/user_av.png'></img></span>");
		pass = 1;   
    }
    else {
        $("#validate-status").html("<span id='create_account_check_button_fail'>Password Doesn't Match!</span>");  
		pass = 0;
    }    
}


$(document).ready(function(){
			 
            $("#email").change(function(){
                 $("#message_email").html("checking...");
			
			             
            var email=$("#email").val();
			account = 0;
 
              $.ajax({
				    async: false,
                    type:"post",
                    url:"process/check1.php",
                    data:"email="+email,
                        success:function(data){
                        if(data==0){
							storeEmail(1)
                            $("#message_email").html("<span id='create_account_check_button_success'><img src='css/images/user_av.png'></img></span>");
							
                        }
                        else{
							storeEmail(0)
							$("#message_email").html("<span id='create_account_check_button_fail'>Email already taken</span>");
							
                        }
						
						
                    }
                 });
 
            });
			
});
