<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="/crowd_funding/index.php">CrowdFunding</a>
			</div>
			<div class="collapse navbar-collapse" id="?">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/crowd_funding/project/browse.php">Browse</a></li>
					<li><a href="/crowd_funding/project/create_project.php">Create a Project</a></li>
					<li><a href="#">Gallery</a></li>
					<li><a href="/crowd_funding/project/search.php">Search</a></li>
					<?php if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) { ?>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" type="text">Welcome <?php echo $_SESSION['username'] ?> <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#"></a></li>
							<li><a href="/crowd_funding/project/view_project.php">My Projects</a></li>
							<li><a href="/crowd_funding/project/view_funded_project.php">Funded Projects</a></li>
							<li><a href="/crowd_funding/project/view_bookmarked_project.php">Bookmarked Projects</a></li>
							<li><a href="/crowd_funding/project/view_transaction.php">Transactions</a></li>
							<li><a href="/crowd_funding/module/edit_profile.php">Edit Profile</a></li>
							<li><a href="/crowd_funding/module/logout.php">Log Out</a></li>
							<?php } ?>
							<?php if (!(isset($_SESSION['username']) && isset($_SESSION['user_id']))) { ?>
							<li><a href="/crowd_funding/module/sign_up.php">Sign Up</a></li>

							<div>
								<button style="position:absolute;margin: 0;height: 3.5em" type="button" class="btn btn-success" data-toggle="modal" data-target="#loginPopUpWindow">Sign In</button>
							</div>
							<li>
								<div class="modal fade" id="loginPopUpWindow">
									<div class="modal-dialog">
										<div class="modal-content">

											<!-- header -->
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h3 class="modal-title">Log In</h3>
											</div>

											<!-- body (form) -->
											<div class="modal-body">
												<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-signin">
													<label for="input_email" class="sr-only">Email address</label>
													<input type="email" id="input_email" class="form-control" placeholder="Email address" name="email" required autofocus>
													<label for="input_Password" class="sr-only">Password</label>
													<input type="password" id="input_password" class="form-control" placeholder="Password" name ="password" required>
													<div class="checkbox">
														<label>
															<input type="checkbox" value="remember_me" name="remember_me">Remember me
														</label>
													</div>
													<button class="btn btn-lg btn-primary btn-block" type="submit" name="login_submit" value="login_submit">Sign in</button>
												</form>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</li>

						</ul>
					</div>
				</div>
			</nav>