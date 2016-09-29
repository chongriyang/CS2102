<nav class="navbar navbar-default">
		
				<ul class="nav navbar-nav navbar-right">
					
					
						
							
							
							<?php if (!(isset($_SESSION['username']) && isset($_SESSION['user_id']))) { ?>
							

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

						
					
			</nav>