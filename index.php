<?php
	// FETCH JSON DATA FROM PROVIDED URL
	$url = "http://devel2.ordermate.online/wp-json/wp/v2/posts";
	$json = file_get_contents($url);
	$post = json_decode($json);
?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>OMO Travel</title>

	<link rel="shortcut icon" href="assets/images/icon.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/fontawesome/fontawesome.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/datepicker.css">
	<!-- CSS ADDON -->
	<link rel="stylesheet" type="text/css" href="assets/css/addon/addon-chips.css">
	<link rel="stylesheet" type="text/css" href="assets/css/addon/addon-cards.css">
	<link rel="stylesheet" type="text/css" href="assets/css/addon/addon-label-float.css">
	<link rel="stylesheet" type="text/css" href="assets/css/addon/addon-navbar-dark.css">

	<!-- JQUERY -->
	<script src="assets/js/jquery.js"></script>

	<!-- JQUERY UI -->
	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.css">
	<script src="assets/js/jquery-ui.js"></script>

	<!-- BOOTSTRAP JS -->
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<!-- JS ADDON -->
	<script src="assets/js/addon/addon-label-float.js"></script>

</head>

<body>
	<nav class="navbar navbar-dark">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="/<?php echo basename($_SERVER['PHP_SELF']); ?>"><img src="assets/images/logo.png" class="brand-img"></a>

				<form class="navbar-form navbar-left">
					<div class="form-group">
						<div class="float-group float-primary" style="float: left;">
							<input type="hidden" id="category" value="title">
							<input type="hidden" id="sortby" value="slug">
							<input type="hidden" id="order" value="asc">
							<input type="text" class="form-control" id="search">
							<label class="lbl-float">Search</label>
						</div>

						<a href="#" class="btn btn-circle btn-success btn-search"><i class="fa fa-search"></i></a>
						<a href="#" class="btn btn-circle btn-danger btn-reset" style="display: none;"><i class="fa fa-refresh"></i></a>
					</div>

					<div class="form-group">
						<div class="dropdown">
							<button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Title
								<span class="caret"></span>
							</button>

							<ul class="dropdown-menu">
								<li><a href="#" id="title">Title</a></li>
								<li><a href="#" id="content">Context</a></li>
								<li><a href="#" id="date">Published Date</a></li>
							</ul>
						</div>

						<div class="btn-group" id="sortby">
							<a href="#" class="btn btn-success" id="slug"><i class="fa fa-font"></i></a>
							<a href="#" class="btn btn-default" id="date"><i class="fa fa-calendar-o"></i></a>
						</div>

						<div class="btn-group" id="order">
							<a href="#" class="btn btn-success" id="asc"><i class="fa fa-angle-double-up"></i></a>
							<a href="#" class="btn btn-default" id="desc"><i class="fa fa-angle-double-down"></i></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</nav>

	<div class="container-fluid">
		<div class="col-lg-9">
			<div class="content-grid">
				<?php
					foreach($post AS $key => $data):
						// FETCH AUTHOR DATA FOR EACH RECORD
						$author_url = $data->_links->author[0]->href;
						$author_json = file_get_contents($author_url);
						$author_decode = json_decode($author_json);
				?>

					<div class="col-lg-6 no-padding">
						<div class="cards">
							<div class="cards-img" style="background-image: url('assets/images/<?php echo $data->id; ?>.jpg');"></div>

							<div class="cards-content">
								<!-- TITLE -->
								<div class="cards-title"><?php echo $data->title->rendered; ?></div>

								<a href="#" class="btn btn-circle btn-success cards-btn content-view" id="<?php echo $data->id; ?>"><i class="fa fa-paper-plane"></i></a>

								<!-- AUTHOR NAME -->
								<i class="fa fa-pencil push-right"></i> <strong><?php echo $author_decode->name; ?></strong><br />
								<!-- DATE POSTED -->
								<i class="fa fa-calendar push-right"></i> <?php echo date("F d, Y", strtotime($data->date)); ?><br />
								<!-- TIME POSTED -->
								<i class="fa fa-clock-o push-right"></i> <?php echo date("H:i:s A", strtotime($data->date)); ?><br />
								<!-- BLOG EXCERPT -->
								<?php echo substr($data->content->rendered, 0, 250) . "..."; // CUT DOWN CONTENT CHARACTER COUNT TO 200 ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- AJAX SEARCH RESULT CONTAINER -->
			<div class="search-content" style="display: none;">&nbsp;</div>
			<div class="blog-content" style="display: none;">&nbsp;</div>
			<div class="data-fetch" style="display: none;">
				<p align="center" style="padding: 3em 0;">
					<i class="fa fa-circle-o-notch fa-fw fa-spin fa-4x"></i>
					<br />
					FETCHING DATA
				</p>
			</div>
		</div>

		<div class="col-sm-3">
			<!-- RECENT POSTS PANEL -->
			<!-- MOST RECENT RECORDS LIMIT TO 5 -->
			<div class="panel panel-success">
				<div class="panel-heading">Recent Posts</div>

				<div class="panel-body">
					<?php
						$i = 0;
						foreach($post AS $key => $data):
							if($i > 0)
								echo "<br />";

							echo '<a href="#" class="content-view" id="' . $data->id . '">' . $data->title->rendered . '</a>';
							$i++;

							if($i == 5)
								break;
						endforeach;
					?>
				</div>
			</div>

			<!-- MOST RECENT BLOG ARCHIVES -->
			<div class="panel panel-success">
				<div class="panel-heading">Archives</div>

				<div class="panel-body">
					<?php
						$archive = array_unique(
									array_map(function($obj){
										return date("F Y", strtotime($obj->date));
									}, $post)
								);

						$ctr = 0;
						foreach($archive AS $row):
							if($ctr > 0)
								echo "<br />";

							echo "<a href='#' class='archive' id='" . date("Y-m", strtotime($row)) . "'>" . $row . "</a>";
							$ctr++;
						endforeach;
					?>
				</div>
			</div>
		</div>

		<!-- PAGE-TO-TOP BUTTON -->
		<a href="#" class="btn btn-circle btn-dark btn-totop" style="display: none;"><i class="fa fa-chevron-up"></i></a>
	</div>

	<script>
		// CUSTOM JS FUNCTIONALITIES
		$(function(){
			// SHOW/HIDE PAGE-TO-TOP BUTTON ON-WINDOW SCROLL
			$(window).on("scroll", function(){
				var $loc = $(window).scrollTop();

				if($loc >= 250){
					$('.btn-totop').show();
				} else{
					$('.btn-totop').hide();
				}
			});

			// PAGE-TO-TOP BUTTON
			$('.btn-totop').click(function(){
				$('html, body').animate({
					scrollTop: 0
				}, 300, "swing");
			});

			// VIEW BLOG PAGE
			$(document).on('click', '.content-view', function(){
				var $id = $(this).prop('id');

				$('.content-grid').slideUp(300);
				$('.search-content').slideUp(300);
				$('.data-fetch').slideDown(300);

				$.ajax({
					url: "blog.php",
					data: {
						id: $id
					},
					cache: false,
					type: "POST",
					success: function(data){
						$('.data-fetch').slideUp(300);
						$('.blog-content').slideDown(300);
						$('.blog-content').html(data);
					}
				});
			});

			$('.navbar .dropdown-menu li a').click(function(){
				$('#search').val('');
				$('#search').datepicker('remove');
				$(this).closest('.navbar-form').find('.lbl-float').removeClass('active');
				$('.navbar .dropdown button').html($(this).text() + " <span class='caret'></span>");
				$('input[type="hidden"]#category').val($(this).prop('id'));

				if($(this).prop('id') == "date"){
					$('#search').datepicker({
						format: "MM dd, yyyy",
						autoclose: true
					}).change(function(){
						if($(this).val() != ""){
							$(this).closest('.float-group').find('.lbl-float').addClass('active');
						}
					});
				}
			});

			// SUBMIT SEARCH ON-CLICK SUBMIT BUTTON
			$('.btn-search').click(function(){
				ajaxsearch();
			});

			// SUBMIT SEARCH ON-ENTER
			$('#search').keyup(function(e){
				if(e.keyCode == 13){
					ajaxsearch();
				}
			});

			// SEARCH RESET BUTTON
			$('.btn-reset').click(function(){
				$('.search-content').slideUp(300);
				$('.content-grid').slideDown(300);
				$('#search').val('');
				$('#category').val('title');
				$('.navbar .dropdown button').html('Title <span class="caret"></span>');
				$(this).hide();
				$('.data-fetch').slideUp(300);
			});

			$('.btn-group .btn').click(function(){
				$(this).closest('.btn-group').find('.btn').prop('class', 'btn btn-default');
				$(this).prop('class', 'btn btn-success');
				$('input#' + $(this).closest('.btn-group').prop('id')).val($(this).prop('id'));
			});

			// ARCHIVE FILTER FUNCTION
			$('.archive').click(function(){
				var $val = $(this).prop('id');
				$('.data-fetch').slideDown(300);
				$('.content-grid, .search-content').slideUp(300);

				$.ajax({
					url: "subindex.php",
					data: {
						val: $val,
						cat: "archive",
						sortby: "date",
						order: "asc"
					},
					cache: false,
					type: "POST",
					success: function(data){
						$('.data-fetch').slideUp(300);
						$('.btn-reset').show();
						$('.search-content').html(data);
						$('.search-content').slideDown(300);
					}
				});
			});
		});

		// AJAX SEARCH FUNCTION
		function ajaxsearch(){
			$('.content-grid, .search-content').slideUp(300);
			$('.data-fetch').slideDown(300);

			$.ajax({
				url: "subindex.php",
				data: {
					val: $('input#search').val(),
					cat: $('input#category').val(),
					sortby: $('input#sortby').val(),
					order: $('input#order').val()
				},
				cache: false,
				type: "POST",
				success: function(data){
					$('.data-fetch').slideUp(300);
					$('.btn-reset').show();
					$('.search-content').html(data);
					$('.search-content').slideDown(300);
				}
			});
		}
	</script>

</body>
</html>
