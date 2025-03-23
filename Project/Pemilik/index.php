<?php
session_start();
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login | Pemilik</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="../assets/img/logoSwimmarlin.png" />
	<link rel="stylesheet" href="styles.css">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
	/* Your styles here */
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
		font-family: "Noto Sans", sans-serif;
	}

	body {
		display: flex;
		justify-content: center;
		align-items: center;
		min-height: 100vh;
		background: url('../assets/img/background/swimming-2323054_1920.jpg') no-repeat;
		background-size: cover;
		background-position: center;
	}

	.wrapper {
		width: 420px;
		background: transparent;
		border: 2px solid rgba(255, 255, 255, .2);
		backdrop-filter: blur(9px);
		color: #33ccff;
		border-radius: 12px;
		padding: 30px 40px;
		position: relative;
	}

	.wrapper .logo-container {
		text-align: center;

	}

	.wrapper .logo-container img {
		width: 170px;
	}

	.wrapper h1 {
		font-size: 30px;
		text-align: center;
		text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
	}

	.wrapper .input-box {
		position: relative;
		width: 100%;
		height: 50px;
		margin: 30px 0;
	}

	.input-box input {
		width: 100%;
		height: 100%;
		background: transparent;
		border: none;
		outline: none;
		border: 2px solid rgba(255, 255, 255, .2);
		border-radius: 40px;
		font-size: 16px;
		color: #fff;
		padding: 20px 20px 20px 45px;
	}

	.input-box input::placeholder {
		color: darkgray;
	}

	.input-box i {
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		font-size: 20px;
		color: #fff;
	}

	.input-box .icon-left {
		left: 20px;
	}

	.input-box .icon-right {
		right: 20px;
		cursor: pointer;
	}

	.wrapper .remember-forgot {
		display: flex;
		justify-content: space-between;
		font-size: 14.5px;
		margin: -15px 0 15px;
	}

	.remember-forgot label input {
		accent-color: #fff;
		margin-right: 3px;
	}

	.remember-forgot a {
		color: #fff;
		text-decoration: none;
	}

	.remember-forgot a:hover {
		text-decoration: underline;
	}

	.wrapper .btn {
		width: 100%;
		height: 50px;
		background: #33ccff;
		border: none;
		outline: none;
		border-radius: 40px;
		box-shadow: 0 0 10px rgba(0, 0, 0, .1);
		cursor: pointer;
		font-size: 16px;
		color: #fff;
		font-weight: 600;
		transition: background 0.3s, color 0.3s;
	}

	.wrapper .btn:hover {
		background: white;
		color: #33ccff;
	}

	.wrapper .register-link {
		font-size: 14.5px;
		text-align: center;
		margin: 20px 0 15px;
	}

	.register-link p a {
		color: #fff;
		text-decoration: none;
		font-weight: 600;
	}

	.register-link p a:hover {
		text-decoration: underline;
	}
</style>

<body>
	<div class="wrapper">
		<div class="logo-container">
			<img src="../assets/img/logoSwimmarlin.png" alt="logo swimming">
		</div>
		<h1>MARLIN SWIMMING CLUB</h1>
		<form method="post" action="">
			<div class="input-box">
				<i class='bx bxs-user icon-left'></i>
				<input type="email" name="username" placeholder="Username" autofocus required>
			</div>
			<div class="input-box">
				<i class='bx bxs-lock-alt icon-left'></i>
				<input type="password" name="password" placeholder="Password" required>
				<i class='bx bx-show icon-right' id="toggle-password"></i>
			</div>
			<button type="submit" class="btn">Login</button>
		</form>
	</div>

	<script>
		const togglePassword = document.getElementById('toggle-password');
		const password = document.querySelector('input[name="password"]');

		togglePassword.addEventListener('click', function() {
			const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
			password.setAttribute('type', type);
			this.classList.toggle('bx-show');
			this.classList.toggle('bx-hide');
		});
	</script>

	<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['username'];
		$password = $_POST['password'];

		$sqlCek = mysqli_query($con, "SELECT * FROM tb_pemilik WHERE email='$username'");
		$jml = mysqli_num_rows($sqlCek);
		$d = mysqli_fetch_array($sqlCek);

		if ($jml > 0 && password_verify($password, $d['password'])) {
			$_SESSION['pemilik'] = $d['id_pemilik'];

			echo "
            <script type='text/javascript'>
                Swal.fire({
                    title: 'Login berhasil',
                    icon: 'success',
                    buttons: {        			
                        confirm: {
                            className : 'btn btn-success'
                        }
                    },
                }).then(function () {
                    window.location.replace('./dashboard.php');
                });
            </script>";
		} else {
			echo "
            <script type='text/javascript'>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Username / Password Salah',
                    buttons: {        			
                        confirm: {
                            className : 'btn btn-danger'
                        }
                    },
                }).then(function () {
                    window.location.replace('index.php');
                });
            </script>";
		}
	}
	?>
</body>

</html>