<?php
//if (!isset($_POST['nr_shows'])){
//header( 'Location: http://timewasted.phpfogapp.com/index.php' ) ;
//}

$total_shows = $_POST['nr_shows'];	
$db = new mysqli( 'tunnel.pagodabox.com',  'frieda', '5bs4PZFO', 'shows', 3306);
$total = 0;

if(!$db) {
	// Show error if we cannot connect.
	echo 'ERROR: Could not connect to the database.';
} else {
	for ($i=0; $i < $total_shows; $i++) {
		$tv_show = $_POST['name_'.$i];
		$seasons = $_POST['season_'.$i];
		$episodes = 0;
		$runtime = 0;
		$query = $db->query("SELECT * FROM shows WHERE name LIKE '$tv_show'");
		if($query) {
			while ($result = $query->fetch_object()) {
				$episodes = $result->episodes;
				$runtime = $result->runtime;
			}
		}
		$total = $total + ($seasons * $episodes * $runtime);
	}
	$minutes = $total;
	$d = floor ($minutes / 1440);
	$h = floor (($minutes - $d * 1440) / 60);
	$m = $minutes - ($d * 1440) - ($h * 60);
	//echo "{$minutes}min converts to {$d}d {$h}h {$m}m";
}
?>

<!doctype html>
	<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
	<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
	<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="favicon.png" />
	<title><?php echo $d?> days, <?php echo $h?> hours and <?php echo $m?> minutes wasted | TimeWasted</title>
	<meta name="description" content="How much time you've wasted watching TV shows">
	<meta name="author" content="http://SicanStudios.com">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0">
	<link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114-precomposed.png" />

	<link rel="stylesheet" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700' rel='stylesheet' type='text/css'>
	<script type="text/javascript">
		window.onresize = function(){ load(); }
		function load(){if (screen.width <= 1024) {setTimeout(function() { window.scrollTo(0, 1) }, 100);}}
	</script>
	<script src="js/libs/modernizr-2.0.6.min.js"></script>
	<script src="js/script.js"></script>
	<script type="text/javascript">
		if ("standalone" in navigator && !navigator.standalone && (/iphone|ipod|ipad/gi).test(navigator.platform) && (/Safari/i).test(navigator.appVersion)) {
		var addToHomeConfig = {
			animationIn: 'bubble',
			animationOut: 'fade',
			lifespan:10000,
			expire:525600,
			touchIcon:true,
			message:'Quick access to <strong>timeWasted</strong><br/>Tap `%icon` below and then <strong>"Add to Home Screen"</strong>'
		};
		document.write('<link rel="stylesheet" href="css\/add2home.css">');
		document.write('<script type="application\/javascript" src="js\/add2home.js" charset="utf-8"><\/s' + 'cript>');
		}
	</script>
	<noscript><p class="noscript">Please enable JavaScript in order to use this web app!</p></noscript>
</head>
<body onload="load();">

<div id="container_result">
	<header>

<div class="menu"><a href="#"><span class="click_button">Click for menu</span></a>

<ul>
<li><a href="https://twitter.com/share" rel="nofollow" class="twitter-share-button" data-text="I&#8217;ve wasted <?php echo $d?> days, <?php echo $h?> hours and <?php echo $m?> minutes of my life watching TV shows. Find out how much you&#8217;ve wasted:" data-url="http://timewasted.co.uk" data-count="none" data-related="sican">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script> &nbsp;your results</li>
<li><a href="add_shows.php" title="Start Over">Start over</a></li>
<li><a href="http://twitter.com/?status=@sican For http://timewasted.co.uk you're missing this TV show:&nbsp;" title="Request a TV Show" target="_blank" rel="nofollow">Request a TV show</a></li>
<li><a href="#" title="About TimeWasted" class="about">About TimeWasted</a>
	<span class="popup_outer">
		<span class="popup">
			<span class="close">Close <span>&times;</span></span><br/>
			This wonderful web app uses HTML5 and CSS 3. It was optimised for the iPhone and the iPad.
			Crafted by Alex Cican for <a href="http://sicanstudios.com" target="_blank">SicanStudios &raquo;</a><br/><br/>
			More apps are coming. Follow me to get notified:
			<a href="https://twitter.com/sican" class="twitter-follow-button" data-button="grey" data-text-color="#FFFFFF" data-link-color="#f7ad35">Follow @sican</a><script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
		</span>
	</span>
</li>
</ul>
</div>

		<h1><a href="index.php" title="Back to Homepage">time<b>wasted</b></a></h1>
		<h2>How much time you&#8217;ve wasted watching TV shows</h2>
			<div class="social_buttons">

			</div>

		<p class="subtitle">watching:</p>
	</header>
	<div id="main" role="main">
		<div id="wrapper">
			<ul class="large_list">
			<?php 
			$total_shows = $_POST['nr_shows'];
			for ($i=0; $i < $total_shows; $i++) {
				$tv_show = $_POST['name_'.$i];?>
				<li><div class="inner"><p><span><?php echo $_POST['season_'.$i]; ?></span>
					<?php if ($_POST['season_'.$i] == 1) { echo 'Season';} else {echo 'Seasons';}?> of</p>
					<h3><?php echo $tv_show;?></h3></div>
					<p class="img"><img src="img/shows/<?php echo str_replace(' ', '', $tv_show);?>.jpg" alt="<?php echo $tv_show;?>" />
					</p>
				</li>
			<?php }?>
			</ul>
		</div>
	</div>
	<footer>
		<?php 
			if ($d < 0) $d = 00;
			if ($d < 10) $d = "0".$d;
			if ($h < 0) $h = 00;
			if ($h < 10) $h = "0".$h;
			if ($m < 0) $m = 00;
			if ($m < 10) $m = "0".$m;
		?>
		<p><span>Days</span><?php echo $d?></p><p><span>Hours</span><?php echo $h?></p><p class="last_nr"><span>Minutes</span><?php echo $m?></p>
		<div class="result_large">
			<article>You&#8217;ve wasted from your life, watching TV shows</article>

<div class="twitter_large"><a href="https://twitter.com/share" rel="nofollow" class="twitter-share-button" data-text="I&#8217;ve wasted <?php echo $d?> days, <?php echo $h?> hours and <?php echo $m?> minutes of my life watching TV shows. Find out how much you&#8217;ve wasted:" data-url="http://timewasted.co.uk" data-count="none" data-related="sican">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script> &nbsp;your results</div>
		</div>
	</footer>

	<div class="footer_info">
		<div class="left">
			This wonderful web app uses HTML5 and CSS 3. It was optimised for the iPhone and the iPad.
			Crafted by Alex Cican for <a href="http://sicanstudios.com" target="_blank">SicanStudios &raquo;</a><br/>
			<div>
			<a href="add_shows.php" title="Start Over">Start over</a> &#8226; <a href="http://twitter.com/?status=@sican For http://timewasted.co.uk you're missing this TV show:&nbsp;" title="Request a TV Show" target="_blank" rel="nofollow">Request a TV show</a> &#8226; <a href="http://twitter.com/?status=@sican For http://timewasted.co.uk I've found this error:&nbsp;" title="Submit an error" target="_blank" rel="nofollow">Submit an Error</a>
			</div>
		</div>
		<div class="right">
			More apps are coming. Follow me to get notified:
			<a href="https://twitter.com/sican" class="twitter-follow-button" data-button="grey" data-text-color="#FFFFFF" data-link-color="#f7ad35">Follow @sican</a><script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
		</div>
	</div>
</div> <!-- end of #container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<script type="text/javascript">
	var webkit = navigator.userAgent.match(/AppleWebKit/i) != null;
	if ("standalone" in navigator && !navigator.standalone && (/iphone|ipod|ipad/gi).test(navigator.platform) && (/Safari/i).test(navigator.appVersion)) {
		//iphone specific to make dropdown work
		$("#main").click(function() {
			$(".menu ul").css("display", "none");
		});
		$(".menu").click(function() {
			$(".menu ul").css("display", "block");
		});

		//fix typography for long tv shows names
		$("h3").each(function() {
			var name_show = $(this).text().replace(/ /g,'').length;
			if (name_show == "12") {
				$(this).css("font-size","3.3em");
			}
			if (name_show == "15") {
				$(this).css("font-size","3em");
			}
		});
	} else {
		//fix typography in Webkit browsers
		if (webkit) {
			$("h2").css({letterSpacing:'0.1em'});
			$("h3").css({letterSpacing:'0.01em'});
			$("p").css({letterSpacing:'0.1em'});
			$("li").css({letterSpacing:'0.1em'});
			$("span").css({letterSpacing:'0.1em'});
			$(".left>div").css({letterSpacing:'0.15em'});
		}

		//get the window size
		var winW = 0;
		if (window.innerWidth && window.innerHeight) {
			winW = window.innerWidth;
		}

		if (winW > 1024) {
			//load the social buttons (desktop only)
			$.get("socialnetwork.php", function(data) {
				$(".social_buttons").html(data);
			});

			//slideDown the twitter button
			setTimeout(function() {
				$(".twitter_large").css("display", "block");
				$(".twitter_large").hide().slideDown(500).show();
			}, 10000);

			//nudge every 25sec
			setInterval(function() {
				$(".twitter_large").animate({marginRight:'5px'}, 100);
				$(".twitter_large").animate({marginRight:'0px'}, 100);
				$(".twitter_large").animate({marginRight:'5px'}, 100);
				$(".twitter_large").animate({marginRight:'0px'}, 100);
			}, 30000);

			//css value is here to update left position as user resizes text
			$("#container_result footer").css('left:', '16.8%');

			//resize the red box and timer
			setTimeout(function() {
				$("#container_result footer").animate({width:'420px', left:'51.5%', height:'8.5em'}, 1500);
				$("#container_result footer p").animate({fontSize:'4em'}, 1500);
				$("#container_result footer p span").animate({marginTop:'5px', fontSize:'0.3em'}, 1500);
				$(".result_large article").animate({fontSize:'1.3em'}, 1500);
			}, 8000);
		}
	}

	//different footer distance for iOS4 and iOS5
	var iOS5 = navigator.userAgent.match(/OS 5_/i) != null;
	if (iOS5) {
		$("footer").css('bottom','0');
		$(".large_list").attr('id', 'create_padding_bottom'); //add ID for CSS to create padding at bottom
	} else {
		document.write("<script src='js/iscroll.js'><\/script>");
	}

	$('.popup_outer').click(function() {
		$(".popup_outer").css('display', 'none');
	});

	$('.about').click(function() {
		$(".popup_outer").css('display', 'block');
	});

	$('.menu').hover(function() {
		$(".menu ul").css('display', 'block');
			}, function() {
		$(".menu ul").css('display', 'none');
	});
</script>

<script src="js/plugins.js"></script>
<!-- end scripts-->
</body>
</html>