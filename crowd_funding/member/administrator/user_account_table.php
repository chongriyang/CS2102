<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
?>	
<table class="table table-bordered table-striped">
			<h1>User Accounts</h1>
			<thead>
				<tr>
					<th class="">ID</th>
					<th class="">Name</th>
					<th class="">Password</th>
					<th class="">Email</th>
					<th class="">Birthday</th>
					<th class="">Join date</th>
					<th class="">Gender</th>
					<th class="">Priviledge</th>
					<th class="">Status</th>
					<th class="">No. Bookmarks</th>
					<th class=""></th>
				</tr>
			</thead>
			<tbody>
				<?php
				include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/open_connection.php';
				$query_all_users = "SELECT * FROM person ORDER BY name ASC";
				$result = pg_query($query_all_users) or die('Query failed: ' . pg_last_error());
				$user = null;
				$db_is_admin = "";
				$db_is_activated = "";
				$password_wildcard = "********";


				while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
					$user[$row['user_id']] =  $row;

					if ($row['is_admin'] == 't') {
						$db_is_admin = "admin";
					}	 else {
						$db_is_admin = "user";
					}

					if ($row['is_activated'] == 't') {
						$db_is_activated = "activated";
					}	 else {
						$db_is_activated = "de-activated";
					}

					echo'<tr class="'.$class.'">

                    <td class="xedit" id="'.$row['user_id'].'" key="id">'.$row['user_id'].'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="name">'.$row['name'].'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="password">'.$password_wildcard.'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="email">'.$row['email'].'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="birthday">'.$row['birthday'].'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="join_date">'.$row['join_date'].'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="gender">'.$row['gender'].'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="is_admin">'.$db_is_admin.'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="is_activated">'.$db_is_activated.'</td>
					<td class="xedit" id="'.$row['user_id'].'" key="bookmark">'.$row['bookmark'].'</td>
                            </tr>';
				}
				include_once $_SERVER['DOCUMENT_ROOT'] . '/crowd_funding/connection/close_connection.php';
				?>
			</tbody>
		</table>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content"></div>
			</div>
			<div class="modal-dialog">
				<div class="modal-content"></div>
			</div>
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true" class="">×   </span><span class="sr-only">Close</span>

						</button>
						<h3 class="modal-title" id="myModalLabel">Modal title</h3>
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

					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div>

<script type="text/javascript">
jQuery(document).ready(function() {  
        $.fn.editable.defaults.mode = 'popup';
        $('.xedit').editable();		
		$(document).on('click','.editable-submit',function(){
			var key = $(this).closest('.editable-container').prev().attr('key');

		var x = $(this).closest('.editable-container').prev().attr('id');
		var y = $('.input-sm').val();
		var z = $(this).closest('.editable-container').prev().text(y);

			$.ajax({
				url: "/crowd_funding/member/administrator/user_account_edit_process.php?id="+x+"&data="+y+'&key='+key,
				type: 'GET',
				success: function(s){
					if(s == 'status'){
					$(z).html(y);}
					if(s == 'error') {
					alert('Error Processing your Request!');}
				},
				error: function(e){
					alert('Error Processing your Request!!');
				}
			});
		});
});
</script>