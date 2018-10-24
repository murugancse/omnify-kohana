<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="col-md-12">
			<br /><br /><br /><br /><br /><br />
			<a href="<?php echo url::base(); ?>" class="Pull-right">Go to Login</a>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
					  <tr>
						<th>Summary</th>
						<th>Link</th>
						<th>Updated</th>
					  </tr>
					</thead>
					<tbody>
					  <?php 
					  if(count($data)>0){
						foreach($data as $event){
							/* print_r($event); */
							/* echo $event['summary']; */
							echo "<tr>
								<td>".$event['summary']."</td>
								<td><a target='_blank' href=".$event['link'].">View in Calendar</a></td>
								<td>".$event['up_datetime']."</td>
							</tr> ";

							}
					  }
					  ?>
					</tbody>
				  </table>
			  </div>
		</div>	
	</div>
</body>
</html>