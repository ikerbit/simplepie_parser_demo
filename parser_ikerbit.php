<?php
require("simplepie.inc");

$urlfeed = "http://ikerbit.com/feed/";
if ($_POST["urlfeed"] != "") {
	$urlfeed = $_POST["urlfeed"];
}
$feedno = "2";
if ($_POST["urlfeed"] != "") {
	$feedno = $_POST["feedno"];
}


$feed = new SimplePie();
$feed->set_feed_url($urlfeed);
$feed->set_cache_location("cache");
$feed->init();
$feed->handle_content_type();

function returnImage ($text) {
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $pattern = "/<img[^>]+\>/i";
    preg_match($pattern, $text, $matches);
    $text = $matches[0];
    return $text;
}


    //This function will filter out image url which we got from previous returnImage() function
    function scrapeImage($text) {
        $pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/';
        preg_match($pattern, $text, $link);
        $link = $link[1];
        $link = urldecode($link);
        return $link;
    }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RSS parser demo using SimplePie</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="main">



	<div id="content">
		<H1>RSS item description parser</H1>
		<H4>Get the description of posts from RSS's like WordPress to insert them in a static Website</H4>
		
		<div id="form">
			<form name="feed" action="parser_ikerbit.php" method="post">
			<label for="urlfeed">Feed URL:</label>
			<input name="urlfeed" type="text" value="<?php echo $urlfeed; ?>" size="60" >
			<br>
			<br>
			<label for="feednum">Number of items to show description:</label>
			<input name="feedno" type="text" value="<?php echo $feedno; ?>" size="34" >
			<br>
			<br>			
			<input type="submit" name="load" value="Load">
			</form>
		</div>
	
		<?php
		$itemQty = $feed->get_item_quantity();
		echo "<p>Total items (posts) found: ".$itemQty."</p>";
		echo "<hr>";
		?>

		
		<!--  STATIC Show 2 items from position 0  -->
		<?php //foreach ($feed->get_items(0,2) as $item) { ?>
		
		
		<!--  Custom foreach. Choose how many items to show from position 0  -->		
		<?php foreach ($feed->get_items(0,$feedno) as $item) { ?>
		<div id="item">

			
			<?php
			$feedDescription = $item->get_content();
			$image = returnImage($feedDescription);
			$image = scrapeImage($image);
			$image_url= $item->get_permalink();
			$description = $item->get_description();
			?>
			
			<div class="item">
            <h4><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a><?php echo "  ".$item->get_date(); ?></h4>
			<div class="image-box"><?php echo '<a href="' . $image_url . '"><img src="' . $image . '" height="100" width="auto" style="padding:10px;" align="left" /></a>'."\n";?></div>
            <p><?php echo $description ?></p>
            <p><a href="<?php echo $item->get_permalink(); ?>">Continue Reading</a></p>   
			</div>
			
		</div>
		<?php } ?>
	</div>


	<div id="footer">By <a href="http://ikerbit.com/">ikerbit.com/</a></div>
</div>
</body>
</html>
