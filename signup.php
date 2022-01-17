<!DOCTYPE HTML>
    <html>
        <head>
            <title>Create your goodies account</title>
            <header class = "">
        <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
         <meta name="viewport" content="width=device-width, initial-scale=1">
      <nav class = "navbar navbar-expand-lg navbar-light bg-gray">
       <a class="logo-image" href="index.php">
        <img src="header-logo.png" alt="Company Logo" id="logo">
       </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <script>
            let x = document.getElementById("logo").height = 40;
        </script>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a> 
            </li>
            <li class="navbar-item dropdown">
                <a class="nav-link active dropdown-toggle" href="#" id="dropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Service</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="ai.php">Artificial Intelligence</a>
                    <a class="dropdown-item" href="ml.php">Machine Learning</a>
                    <a class="dropdown-item" href="android.php">Mobile Android</a>
                    <a class="dropdown-item" href="web.php">Web Development</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="goodies.php">Goodies Onboarding</a>
                    <!-- the above anchor tags page have not been created, take note -->
                </div>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="contact.php">Contact US</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>&nbsp;&nbsp;
                <!-- the button tag above needs to be routed to normal google search -->
                <a class="btn btn-outline-success my-2 my-sm-0" href="login.php">Log in</a>&nbsp;
            <!--    <a class="btn btn-outline-success my-2 my-sm-0" href="signup.php">Sign Up</a> -->
        </form>
    </div>
      </nav>       
    </header>
        </head>
        <hr />
        <body>
            
     <?php
	require "db.php";
	include "footer.php";
	
		$firstname = $lastname = $mail = $username = $password = "";
		
		$errors = array('firstname' =>'','lastname' =>'','mail' =>'','username' =>'','password' =>'');
		
		if(isset($_POST['signup-submit'])){
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$mail = $_POST['mail'];
			$username = $_POST['username'];
			$password = $_POST['pwd'];
			
			//REGEX VALIDATION FOR FIRSTNAME
			if(empty($firstname)){
			$errors['firstname'] = "First Name is required";
			} else{
				if(!preg_match('/^[a-zA-Z]+$/', $firstname)){
					$errors['firstname'] = "First name has special character";
				}
			}
			
			//REGEX VALIDATION FOR LASTNAME
			if(empty($lastname)){
				$errors['lastname'] = "Last name is required";
			} else{
				if(!preg_match('/^[a-zA-Z]+$/', $lastname)){
					$errors['lastname'] = "Last name has special character";
				}
			}
		
			//REGEX VALIDATION FOR E-MAIL
			if(empty($mail)){
				$errors['mail'] = "E-mail is required";
			} else{
				if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
					$errors['mail'] = "Invalid e-mail";
				}
			}
			
			//REGEX VALIDATION FOR USERNAME
			if(empty($username)){
				$errors['username'] = "Username is required";
			} else{
				if(strlen($username) < 5){
					$errors['username'] = "Username must be at least 5 characters";
				} else{
					if(!preg_match('#.*^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).#', $username)){
						$errors['username'] = "Username must contain at least a lowercase, UPPERCASE and a number";
					}
				}
			} 
			
			//REGEX VALIDATION FOR PASSWORD
			if(empty($password)){
				$errors['password'] = "Password is required";
			} else{
				if(strlen($password) < 6){
					$errors['password'] = "Password must not be less than 6 characters";
				} else{
					if(!preg_match('#.*^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).#', $password)){
						$errors['password'] = "Password must contain at least a number, lowercase and UPPERCASE";
					}
					
					//INCLUDING SQL QUERY FOR CHECKING TAKEN USERNAME (initializing the statement)
					else{
						$sql = "SELECT * FROM users WHERE username = ?";
						$stmt = mysqli_stmt_init($connection);
						if(!mysqli_stmt_prepare($stmt, $sql)){
							$errors['username'] = "SQL error";
						}
						
						//binding the initialized statement for username taken regex
						else{
							mysqli_stmt_bind_param($stmt, "s", $username);
							mysqli_stmt_execute($stmt);
							mysqli_stmt_store_result($stmt);
							$resultCheck = mysqli_stmt_num_rows($stmt);
							if($resultCheck > 0){
								$errors['username'] = "Username has been taken";
							}
						}
					}
				}
			}
			
			//HASH PASSWORD USING BYCRYPT
			
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			
			//INSERTING INTO THE DATABASE
			
			if(!array_filter($errors)){
				$query = "INSERT INTO users (firstname, lastname, mail, username, pasword) VALUES ('$firstname', '$lastname', '$mail', '$username', '$hashedPassword')";
				$result = $connection->query($query);
				header("Location: login.php");
			}
			
			//SANITIZING THE WEBSITE
			function sanitizeString($var){
				$var = strip_tags($var);
				$var = htmlentities($var);
				$var = stripslashes($var);
				return $var;
			}
			
			//CLOSING DATABASE CONNECTION
			$connection->close();
		}
		?>
            <center>
    <main>
        <div class="wrapper-main">
            <section class="section-default">
        <h1 style="color: purple">Create your Goodies Account</h1>
        <form class="form-signup" action="signup.php" method="post">
            <input type="text" name="firstname" placeholder="First Name" style="border-radius:5px" value="<?php echo htmlspecialchars($firstname); ?>"><br/>
                <p style="color:red"><?php echo $errors['firstname']; ?></p>
               
            <input type="text" name="lastname" placeholder="Last Name" style="border-radius:5px" value="<?php echo htmlspecialchars($lastname); ?>"><br/>
                <p style="color: red"><?php echo $errors['lastname']; ?></p>
                
            <input type="email" name="mail" placeholder="E-mail" style="border-radius:5px" value="<?php echo htmlspecialchars($mail); ?>"><br/>
                <p style="color: red"><?php echo $errors['mail']; ?></p>
                
            <input type="text" name="username" placeholder="Username" style="border-radius:5px" value="<?php echo htmlspecialchars($username); ?>"><br/>
                <p style="color: red"><?php echo $errors['username']; ?></p>
               
            <input type="password" name="pwd" placeholder="Password" style="border-radius:5px" value="<?php echo htmlspecialchars($password); ?>"><br/>
                <p style="color: red"><?php echo $errors['password']; ?></p>
                
         <button type="submit" name="signup-submit" class=" btn btn-success" style="color: white">Sign Up</button>   
        </form>
            </section>
        </div>
    </main>
    </center>
            <br /> <br />
            <div style="height:200px">
<footer class="jumbotron bg-info">
                    <p class="text text-center">Copyright &copy; Kingsley Amoke
                    <?php
                    function longdate($timestamp)
                    {
                    return date("Y", $timestamp);}
                echo date("Y");
                    ?>
                    </div>
        </body>
    </html>