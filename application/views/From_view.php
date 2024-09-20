<! DOCTYPE html>  
<html>  
<head>  
<title>  
Signup
</title>  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<head>  
<style>  
body {  
  font-family: 'Lato';  
  font-weight: 300;  
  font-size: 1.25rem;  
  }  
body {  
  font-family: "Poiret One", cursive;  
  background: #3494e6; /* fallback for old browsers */  
  background: -webkit-linear-gradient (  
    to right,  
    #ec6ead,  
    #3494e6  
  ); /* Chrome 10-25, Safari 5.1-6 */  
  background: linear-gradient (  
    to right,  
    #ec6ead,  
    #3494e6  
  ); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */  
}  
h4 {  
  font-weight: bold;  
  margin-bottom: 2.5rem;  
 font-family: 'Lato';  
  align: center;  
  font-size: 2rem;  
}  
form {  
margin-top: 20px;  
  margin-left: 100px;  
}  
p {  
  margin-top: 20px;  
  margin-left: 100px;  
}  
h4 {  
  margin-top: 20px;  
  margin-left: 100px;  
}  
p.note {  
  font-size: 1rem;  
  color: red;  
}  
input {  
  border-radius: 5px;  
  border: 1px solid #ccc;  
  padding: 4px;  
  font-family: 'Lato';  
  width: 300px;  
  margin-top: 10px;  
}  
label {  
  width: 300px;  
  font-weight: bold;  
  display: inline-block;  
  margin-top: 20px;  
}  
label span {  
  font-size: 1rem;  
}  
label.error {  
    color: red;  
    font-size: 1rem;  
    display: block;  
    margin-top: 5px;  
}  
input.error {  
    border: 1px dashed red;  
    font-weight: 300;  
    color: red;  
}  
[type="reset"], html [type="button"] {  
    margin-left: 0;  
    border-radius: 0;  
    background: black;  
    color: white;  
    border: none;  
    font-weight: 300;  
    padding: 10px 0;  
    line-height: 1;  
}  
button {   
margin-left: 0;  
    border-radius: 0;  
    background: black;  
    color: white;  
    border: none;  
    font-weight: 300;  
    padding: 10px 0;  
    line-height: 1;  
}  
[type="submit"] {  
    display: inline-block;  
    padding: 0.35em 1.2em;  
    border: 0.1em solid #3494e6;  
    margin: 0 0.3em 0.3em 0;  
    border-radius: 0.12em;  
    box-sizing: border-box;  
    text-decoration: none;  
    font-family: 'Roboto',sans-serif;  
    font-size: 1rem;  
    text-align: center;  
    transition: all 0.2s;  
    }  
[type="submit"]:hover {  
    color: #FFFFFF;  
    background-color: #3494e6;  
}  
</style>  
</head>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"> </script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"> </script>  
<script>  
$(document).ready (function () {  
  $("#basic-form").validate ();  
});  
</script>  
<h1>Signup</h1>
<form action="<?php echo site_url('user/signup'); ?>" method="post">
        <p>
            <label for="emailID">Email:</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>

            <input type="submit" value="Sign Up">
        </p>
    <?php echo form_close(); ?>
    <a href="<?php echo site_url('views\Login_view.php'); ?>">Login</a>
    <a href="<?php echo site_url('user/forgot_password'); ?>">Forgot Password?</a>
</body>
</html>