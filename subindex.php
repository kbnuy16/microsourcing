<?php
	// FILTER SEARCHED VALUES
	$url = "http://devel2.ordermate.online/wp-json/wp/v2/posts";
	$json = file_get_contents($url);
	$post = json_decode($json);

	if(!empty($_POST['val'])){
		$post = array_filter($post, function($obj){
			$var = $_POST['cat'];

			if($_POST['cat'] == "archive"){
				return(strripos($obj->date, date("Y-m", strtotime($_POST['val'])))) !== false;
			} elseif($_POST['cat'] != "date"){
				return(strripos($obj->$var->rendered, $_POST['val'])) !== false;
			} else{
				return(strripos($obj->date, date("Y-m-d", strtotime($_POST['val'])))) !== false;
			}
		});
	}

	// APPLICATION OF SORTING ON SEARCH RESULTS
	$sort = array();
	$sortby = $_POST['sortby'];
	$order = ($_POST['order'] == "asc") ? SORT_ASC : SORT_DESC;

	foreach($post AS $key => $data):
		$sort[$key] = $data->{$sortby};
	endforeach;

	$sorted = array_multisort($sort, $order, $post);

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