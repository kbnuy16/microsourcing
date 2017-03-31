<?php
	// FETCH SELECTED BLOG DATA
	$url = "http://devel2.ordermate.online/wp-json/wp/v2/posts/" . $_POST['id'];
	$json = file_get_contents($url);
	$post = json_decode($json);

	$author_url = $post->_links->author[0]->href;
	$author_json = file_get_contents($author_url);
	$author_decode = json_decode($author_json);
?>

<a href="#" class="btn btn-danger btn-back"><i class="fa fa-angle-left"></i> Back to List</a>

<br /><br />

<img src="assets/images/<?php echo $post->id; ?>.jpg" width="100%">

<h2><?php echo $post->title->rendered; ?></h2>

<i class="fa fa-pencil push-right"></i> <strong><?php echo $author_decode->name; ?></strong>
<br />
<i class="fa fa-calendar push-right"></i> <?php echo date("F d, Y", strtotime($post->date)); ?>
<br />
<i class="fa fa-clock-o push-right"></i> <?php echo date("H:i:s A", strtotime($post->date)); ?>
<br /><br />
<div class="blogcontent"><?php echo $post->content->rendered; ?></div>

<script>
	$(function(){
		// BACK TO LIST BUTTON FUNCTION
		$('.btn-back').click(function(){
			$('.blog-content').slideUp(300);
			$('.content-grid').slideDown(300);
			$('.btn-reset').hide();
			$('.data-fetch').slideUp(300);
		});
	});
</script>