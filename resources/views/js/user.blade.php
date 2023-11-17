<script>
    $('#form_add_user').validate({
        rules: {
            'email' : 
            { 
                required : true, 
                email : true,
            },
            'password' : 
			{ 
				minlength: 8, 
				pwcheck: true 
			},
            'cpassword' : 
			{
				equalTo: "#password"
			},
            'role' : 
			{ 
				required : true, 
				selected : true
			}
        },
        messages:
		{
			email : 
			{ 
				required : "This field is required", 
				email : "Please enter valid email address",
			},
			password : 
			{  
				minlength: "Please enter minimum 8 character", 
				pwcheck: "Password harus terdiri dari kombinasi huruf(alfabet), angka(numeric), dan / atau karakter unik (special character)", 
			},
			cpassword : 
			{
				equalTo: "Please enter same password" 
			},
            role : 
			{ 
				required : "This field is required",
				selected : "Please select atleast one option" 
			}			
		},
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        }
    });

    $('#form_update_user').validate({
        rules: {
            'email' : 
            { 
                required : true, 
                email : true,
            },
            'password' : 
			{ 
				minlength: 8, 
				pwcheck: true 
			},
            'cpassword' : 
			{
				equalTo: "#password"
			},
            'role' : 
			{ 
				required : true, 
				selected : true
			}
        },
        messages:
		{
			email : 
			{ 
				required : "This field is required", 
				email : "Please enter valid email address",
			},
			password : 
			{  
				minlength: "Please enter minimum 8 character", 
				pwcheck: "Password harus terdiri dari kombinasi huruf(alfabet), angka(numeric), dan / atau karakter unik (special character)", 
			},
			cpassword : 
			{
				equalTo: "Please enter same password" 
			},
            role : 
			{ 
				required : "This field is required",
				selected : "Please select atleast one option" 
			}			
		},
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        }
    });

	$('#form_change_password').validate({
        rules: {
            'newPassword' : 
			{ 
				minlength: 8, 
				pwcheck: true 
			},
            'cNewPassword' : 
			{
				equalTo: "#password"
			}
        },
        messages:
		{
			newPassword : 
			{  
				minlength: "Please enter minimum 8 character", 
				pwcheck: "Password harus terdiri dari kombinasi huruf(alfabet), angka(numeric), dan / atau karakter unik (special character)", 
			},
			cNewPassword : 
			{
				equalTo: "Please enter same password" 
			},	
		},
        highlight: function (input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-line').append(error);
        }
    });

	$('#form_reset_password').validate({
        rules: {
            'password' : 
			{ 
				minlength: 8, 
				pwcheck: true 
			},
            'confirm_password' : 
			{
				equalTo: "#password"
			}
        },
        messages:
		{
			password : 
			{  
				minlength: "Please enter minimum 8 character", 
				pwcheck: "Password harus terdiri dari kombinasi huruf(alfabet), angka(numeric), dan / atau karakter unik (special character)", 
			},
			confirm_password : 
			{
				equalTo: "Please enter same password" 
			},	
		},
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        }
    });

    // ================================ Custom Validations ================================ //
    //Password Check
    $.validator.addMethod("pwcheck", function(value, element) {
        return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
        && /[a-z]/.test(value) // has a lowercase letter
        && /\d/.test(value) // has a digit
    }, 
        'Password harus terdiri dari kombinasi huruf(alfabet), angka(numeric), dan / atau karakter unik (special character)'
    );
    // ================================ Custom Validations ================================ //
</script>