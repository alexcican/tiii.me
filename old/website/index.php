<!doctype html>
	<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
	<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
	<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="favicon.png" />
	<title>TimeWasted | How much time you've wasted watching TV shows</title>
	<meta name="description" content="How much time you've wasted watching TV shows">
	<meta name="author" content="http://SicanStudios.com">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0">
	<link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114-precomposed.png" />

	<link rel="stylesheet" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'>
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

<div id="container_home">
	<header>
		<h1>time<b>wasted</b></h1>
		<h2>How much time you&#8217;ve wasted watching TV shows</h2>
		<p class="subtitle">To begin, tap the &#8220;&#43;&#8221; icon</p>
	</header>
	<div id="main" role="main">

	</div>
	<footer>
		<p><a href="add_shows.php" class="add_button">&#43;</a></p>
	</footer>

</div><!-- end of #container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<script type="text/javascript">
	//fix typography in Webkit browsers
	var webkit = navigator.userAgent.match(/AppleWebKit/i) != null;
	if ("standalone" in navigator && !navigator.standalone && (/iphone|ipod|ipad/gi).test(navigator.platform) && (/Safari/i).test(navigator.appVersion)) {
		//don't fix for mobile devices
	} else {
		if (webkit) {
			$("h2").css({letterSpacing:'0.1em'});
			$("h3").css({letterSpacing:'0.01em'});
			$("p").css({letterSpacing:'0.1em'});
			$("li").css({letterSpacing:'0.1em'});
			$("span").css({letterSpacing:'0.1em'}); 
		}
	}

	//different footer distance for iOS4 and iOS5
	var iOS5 = navigator.userAgent.match(/OS 5_/i) != null;
	if (iOS5) {
		$("#container_home footer").css('bottom','75px');
	}
//alert(what);
</script>

<script src="js/plugins.js"></script>
<!-- end scripts-->

</body>
</html>