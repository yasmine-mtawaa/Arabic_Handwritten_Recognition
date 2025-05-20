<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<style>
  body{
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    display: flex;
    position: relative;
    overflow: hidden;
    font-family: sans-serif;
    background-image: url(2.jpg);}
  
.container{
  background: rgba(255, 255, 255, 0.1); /* fond transparent */
  backdrop-filter: blur(5px); /* flou de l'arrière-plan */
  -webkit-backdrop-filter: blur(10px); /* support Safari */
    /*background:rgb(255, 218, 236);*/
    width: 550px;
    padding:2.5rem;
    margin:125px auto;
    border-radius:10px;
    box-shadow:0 20px 35px rgba(0,0,1,0.9);
}
.btn{
    font-size:1.1rem;
    padding:8px 0;
    border-radius:5px;
    outline:none;
    border:none;
    width:100%;
    background:rgb(0, 0, 0);
    color:white;
    cursor:pointer;
    transition:0.9s;
}
.btn:hover{
    background:rgb(88, 27, 55);
}
.icons i{
    color:rgb(40, 0, 0);
    padding:0.8rem 1.5rem;
    border-radius:10px;
    font-size:1.5rem;
    cursor:pointer;
    border:2px solid rgb(0, 0, 0);
    margin:0 15px;
    transition:1s;
}
.icons i:hover{
    background:rgb(88, 27, 55);
    font-size:1.6rem;
    border:2px solid rgb(88, 27, 55);
}
button{
    color:rgb(40, 0, 0);
    border:none;
    background-color:transparent;
    font-size:1rem;
    font-weight:bold;
}
button:hover{
    text-decoration:underline;
    color:rgb(40, 0, 0);
}
label{
    color:rgb(40, 0, 0);
    position:relative;
    left:1.8em;
    top:-1.3em;
    cursor:auto;
    transition:0.3s ease all;
}
.recover a{
    text-decoration:none;
    color:rgb(40, 0, 0);
    font-weight:bold;
}
.recover a:hover{
    color:rgb(88, 27, 55);
    text-decoration:underline;
}
.form-title{
    font-size:3.5rem;
    font-weight:bold;
    text-align:center;
    padding:1.3rem;
    margin-bottom:0.4rem;
}
</style>
<body>
    <div class="container" id="signup" style="display:none;">
      <h1 class="form-title">Register</h1>
      <form method="post" action="/web/register.php">
        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="fName" id="fName" placeholder="First Name" required>
           <label for="fname">First Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="lName" id="lName" placeholder="Last Name" required>
            <label for="lName">Last Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="password">Password</label>
        </div>
       <input type="submit" class="btn" value="Sign Up" name="signUp">
      </form>
      <p class="or">
        ----------Or----------
      </p>
      <div class="icons">
        <a href="https://mail.google.com" title="Sign in with Google"><i class="fab fa-google"></i></a>
        <a href="https://www.facebook.com" title="Sign in with Facebook"><i class="fab fa-facebook"></i></a>
      </div>
      <div class="links">
        <p>Already Have Account ?</p>
        <?php if (!empty($error)) : ?>
            <p style="color:red; text-align:center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <button id="signInButton">Sign In</button>
        
      </div>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="/web/register.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="loginEmail" placeholder="Email" required>
              <label for="loginEmail">Email</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="loginPassword" placeholder="Password" required>
              <label for="loginPassword">Password</label>
          </div>
          <p class="recover">
            <a href="#" id="recoverPassword">Recover Password</a>
          </p>
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <p class="or">
          ----------Or----------
        </p>
        <div class="icons">
          <a href="https://mail.google.com" title="Sign in with Google"><i class="fab fa-google"></i></a>
          <a href="https://www.facebook.com" title="Sign in with Facebook"><i class="fab fa-facebook"></i></a>
        </div>
        <div class="links">
          <p>Don't have account yet?</p>
          <button id="signUpButton">Sign Up</button><br>
        </div>
        <?php if (!empty($error)) : ?>
            <p style="color: red; font-weight: bold; text-align: center;"><?php echo $error; ?></p>
          <?php endif; ?>
      </div>
      <script src="script.js"></script>
</body>
</html>