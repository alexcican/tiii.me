<!doctype html>
	<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
	<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
	<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="favicon.png" />
	<title>Add TV Shows | TimeWasted</title>
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
		if ('standalone' in navigator && !navigator.standalone && (/iphone|ipod|ipad/gi).test(navigator.platform) && (/Safari/i).test(navigator.appVersion)) {
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

<div id="container">
	<header>
		<div class="title_left">
			<h1>time<b>wasted</b></h1>
			<h2>How much time you&#8217;ve wasted watching TV shows</h2>
			<div class="social_buttons">

			</div>
		</div>
		<div id="add_shows">	
			<input type="search" id="add_input" name="show" placeholder="I&#8217;ve been watching... (type a TV show)" onblur="fill();" autocorrect="off" autocapitalize="off" autocomplete="off" />
			<span>for</span>
			<select name="seasons" id="season_input">
				<option value="0" class="default">How many seasons?</option>
			</select>
			<button id="submit_button"><span>Add this</span></button>
			<div class="suggestionsbox" id="suggestions">
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		</div>
	</header>
	<div id="main" role="main">
		<div id="wrapper">
			<ul class="small_list">

			</ul>
		</div>
	</div>
	<footer>
	<form method="post" action="" id="calculate_form">
		<input type="hidden" value="0" id="nr_shows" name="nr_shows" />
		<input type="submit" value="calculate" id="calculate_button" />
	</form>

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

	</footer>
</div> <!--! end of #container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<script type="text/javascript">
	var webkit = navigator.userAgent.match(/AppleWebKit/i) != null;
	if ("standalone" in navigator && !navigator.standalone && (/iphone|ipod|ipad/gi).test(navigator.platform) && (/Safari/i).test(navigator.appVersion)) {
		//don't do anything (mobile devices)
	} else {
		//load the social buttons (desktop only)
		$.get("socialnetwork.php", function(data) {
			$(".social_buttons").html(data);
		});

		//fix typography in Webkit browsers
		if (webkit) {
			$("h2").css({letterSpacing:'0.1em'});
			$("select").css({letterSpacing:'0.07em'});
			$("h3").css({letterSpacing:'0.01em'});
			$("p").css({letterSpacing:'0.1em'});
			$("li").css({letterSpacing:'0.1em'});
			$("span").css({letterSpacing:'0.1em'});
			$(".footer_info").css({letterSpacing:'0.04em'}); 
			$("#add_input").css({letterSpacing:'0.04em'});
			$("#submit_button span").css({letterSpacing:'0.06em'});
			$(".left>div").css({letterSpacing:'0.15em'});
		}
	}

	//different footer distance for iOS4 and iOS5
	var iOS5 = navigator.userAgent.match(/OS 5_/i) != null;
	if (iOS5) {
		$("footer").css('bottom','0');
		$("body").css('height','541'); //91.5%
		$(".small_list").attr('id', 'create_padding_bottom');
	} else {
		document.write("<script src='js/iscroll.js'><\/script>");
	}

	//delay the database results after finished typing
	var typingTimer; //timer identifier
	var doneTypingInterval = 500; //time is 0.5 seconds
	var nr_show = 0;
	//on keyup, start the countdown
	$('#add_input').keyup(function(){
		typingTimer = setTimeout(doneTyping, doneTypingInterval);
	});
	//on keydown, clear the countdown
	$('#add_input').keydown(function(){
		clearTimeout(typingTimer);
	});
	//after finished typing, do something
	function doneTyping () {
		lookup($('#add_input').val());
	}


	//look that value in db
	function lookup(add_input) {
		if(add_input.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide(); return false;
		} else {
			$.post("shows.php", {queryString: ""+add_input+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				} else {
					$('#suggestions').show();
					$('#autoSuggestionsList').html('<li class="not_found"><aside>No TV Show with that name was found</aside></li>');
				}
			});
		}
	}
	
	//populate the field with that value
	function fill(thisValue) {
		if((thisValue)== "This TV show cannot be found...") { 
			setTimeout(function() {$('#add_input').val(thisValue);}, 500);
			setTimeout(function() {$('#add_input').val('')}, 3000 );
		} else {

			$('#add_input').val(thisValue);

			//Check if value already exists in the list
			$("li").each(function() {
				var elem = $(this);
				var text = elem.find("h3").text();
				if ((!$("#add_input").val() == '') && ($("#add_input").val() == text)) {
					alert("Aren't you forgetting something? You already added this TV show, remember? Try a different one");
					$('#add_input').val('');
					clear_seasons();
				}
			});
		}
		setTimeout("$('#suggestions').hide();", 200);
	}

	//get the options for season selection
	function seasons(thisValue) {
		var optionList = document.getElementById('season_input');
		if (optionList.options.length > 1) {
			optionList.options.length=1;
		}

		var text = 0;
		for (i=1;i<=thisValue;i++) {
        		var opt = document.createElement('option');
			optionList.options.add(opt);
			if (i == "1"){text = " Season";} else {text= " Seasons";}
			opt.text = i+text;
			opt.value = i;
		}

		//If TV show is empty, focus on the add input; don't focus on the seasons
		if ($('#add_input').val() == '') {
			$('#add_input').focus();
		} else {
			$('#season_input').focus();
		}
	}

	//on submit button, check for empty form (TV Show and Season) and add to li the movie
	$('#submit_button').click(function() {
		if ($("#add_input").val() == 0) { $('#add_input').addClass("error"); clear_seasons();} else {
			if ($("#season_input option[value='0']").attr('selected')) { $('#season_input').addClass("error");} else {

				window.scrollTo(0, 1);

				//add to field
				var input = $('#add_input').val();
				var input_stripped = $('#add_input').val().replace(/ /g,'');

  				var season_input = $('#season_input').find(':selected').val();

				var li = document.createElement('li');
				li.innerHTML = '<h3>' + input + '</h3>' + '<p>' + season_input + '&nbsp;seasons </p><a href="#" class="delete">&times;</a>';
				$(".small_list").prepend($(li).hide().fadeIn(1000));

				var input_shows = document.createElement('input');
				var input_seasons = document.createElement('input');

				input_shows.setAttribute("name", "name");
				input_seasons.setAttribute("name", "season");

				input_shows.setAttribute("type","hidden");
				input_seasons.setAttribute("type","hidden");
				input_shows.setAttribute("class",input_stripped);
				input_seasons.setAttribute("class", input_stripped);
				input_shows.value = input;
				input_seasons.value = season_input;
				$("#calculate_form").prepend($(input_shows));
				$("#calculate_form").prepend($(input_seasons));

				//clear previously inputed fields for new addition
				$('#add_input').val('');
				clear_seasons();
				$('#submit_button_ok').attr("id","submit_button");

				//add to the total number of shows
				nr_show ++;
				$("#nr_shows").val(nr_show);

				//delete button
				$('.delete').click(function() {
					//first remove the hidden input associated, decrease show counter and then remove li
					var name_show = $(this).parent().find("h3").text().replace(/ /g,'');
					if ($("input").hasClass(name_show)){
						$('.'+input_stripped).remove();
						$(this).parent().remove();
						nr_show --;
						$("#nr_shows").val(nr_show);
					}
					return false;
				});
				
				//refresh the scroller
				myScroll.refresh();
			}
		}
	});

	//sets the correct numbers for [name] and [season] of input fields
	function set() {
		//var i = 0;
		$('.small_list h3').each(function(i){
			$('input[name^="name"]').each(function(i){
				$(this).attr("name", "name_"+i);
			});

			$('input[name^="season"]').each(function(i){
				$(this).attr("name", "season_"+i);
			});

		});
					
	}

	//Check if add TV show is empty
	$("#add_input").change(function() {
		if ($("#add_input").val() == 0) {
			$('#add_input').addClass("error");
			clear_seasons();
		} else {
			$('#add_input').removeClass("error");
		}
	});

	//Check if season input is empty
	$("#season_input").change(function() {
		if ($("#season_input option[value='0']").attr('selected')) {
			$('#season_input').addClass("error");
		} else {
			$('#season_input').removeClass("error");
			$('#submit_button').attr("id","submit_button_ok");
		}
	});

	//Check if there are elements in list
	$("#calculate_button").click(function() {
		var elem = $(".small_list");

		if (elem.children().length == 0) {
			$('#add_input').addClass("error");
			$('#add_input').val('');
			clear_seasons();
			$("#calculate_button").attr("href", "#");
			alert("I can't calculate an empty list! Please type in a TV show and then add it to the list");
			return false;
    		} else {
			set();
			$("#calculate_form").attr("action", "result.php");
			return true;
		}
	});

	//if more options in seasons, remove all but the default
	function clear_seasons() {
		var optionList = document.getElementById('season_input');
		if (optionList.options.length > 1) {
			optionList.options.length=1;
		}
	}

	//fade in the footer
	$("footer").hide();
	$("#add_input").bind('click', function() {
       		$(this).unbind('click');
		$("footer").fadeIn(3000);
	});
</script>

<script src="js/plugins.js"></script>
<!-- end scripts-->

</body>
</html>