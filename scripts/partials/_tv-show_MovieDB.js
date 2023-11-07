// TV SHOW INPUT FUNCTIONS

// global variables for
var nrEpisodes = 0,
  nrSeasons = 0,
  runtime = 0,
  selectedTVshow;

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();



var API_KEY = "d56e51fb77b081a9cb5192eaaa7823ad";

function makeAjaxCall(type, url, callback) {
  return $.ajax({
    type: type,
    url: url,
    dataType: "jsonp",
    json: "callbackname",
    crossDomain : true,
    success: callback,
    error: function(data) {
      console.log('An error occurred.');
    }
  });
}

$(".tvshow").select2({
  placeholder: "Type in a TV show",
  multiple: true,
  maximumSelectionSize: 1,
  minimumInputLength: 2,
  maximumInputLength: 20,
  query: function(query) {
    delay(function(){
      var data = {results: []};
      var value = $(".select2-input").val();
      var showSearchUrl = 'https://api.themoviedb.org/3/search/tv?api_key=' + API_KEY + '&query=' + value;

      makeAjaxCall("GET", showSearchUrl, function(result) {
        $.each(result, function (i, shows) {
          $.each(shows, function(i, show) {
            if (i < 2) {
              var showDetailsUrl = 'https://api.themoviedb.org/3/tv/' + show.id + '?api_key=' + API_KEY;

              makeAjaxCall("GET", showDetailsUrl, function(tvShow) {
                var seasons = tvShow.number_of_seasons != null ? tvShow.number_of_seasons : 1;
                data.results.push({
                  id: tvShow.id,
                  text: tvShow.original_name,
                  runtime: tvShow.episode_run_time[0],
                  poster: tvShow.poster_path,
                  bg: tvShow.backdrop_path,
                  seasons: seasons,
                  episodes: tvShow.number_of_episodes
                });

                if (tvShow.episode_run_time[0] == null) {
                  var showEpisodesUrl = 'https://api.themoviedb.org/3/tv/' + show.id + '/season/1?api_key=' + API_KEY;

                  makeAjaxCall("GET", showEpisodesUrl, function(showEpisodes) {
                    var totalRuntime = 0, episodeCount = 0, averageRuntime = 0;

                    for (var i = 0; i < showEpisodes.episodes.length; i++) {
                      var episode = showEpisodes.episodes[i];
                      if (episode.hasOwnProperty("runtime")) {
                        totalRuntime += episode.runtime;
                        episodeCount++;
                      }
                    }

                    averageRuntime = episodeCount > 0 ? totalRuntime / episodeCount : 0;
                    averageRuntime = Math.floor(averageRuntime);

                    for (var j = 0; j < data.results.length; j++) {
                      if (data.results[j].id === tvShow.id) {
                        data.results[j].runtime = averageRuntime;
                        break;
                      }
                    }
                  })
                }

                query.callback(data);
              })
            }
          })
        })
      });

    }, 1000 );
  }
});


// flag that goes true once user has selected a show (used in showing the submit button)
var hasSelectedShow = false;
var selectedTVshow;

var totalSeasons = 0,
    episodes = 0,
    runtime = 0;

// on input change detect, read TV show selected, and add it to the list
$('.tvshow').change(function() {
  selectedTVshow = jQuery.parseJSON(JSON.stringify($('.tvshow').select2('data')));
  console.log(selectedTVshow);

  // removes TV shows that were appended (selected from dropdown) but eventually not added (submitted)
  $('.show-to-add').remove();

  if (selectedTVshow[0].seasons >= 0 && selectedTVshow[0].runtime >= 0) {
    // if selected TV show exists
    if (typeof(selectedTVshow[0]) != "undefined") {

      // checks if same show already exists
      // var TVshowAlreadyExists = false;
      // $('.container__list-of-shows li').each(function() {
        // var tvShowTitle = $(this).find('.container__list-of-shows__info__title').text();

        // if (tvShowTitle === selectedTVshow[0].text)
          // TVshowAlreadyExists = true;
      // })


      // if same show has already been added, displays message and doesn't allow addition of new show
      // if (TVshowAlreadyExists) {
        // var $listItem = $('.select2-results');
        // $listItem.parent().css("display", "block");
        // $('<li />').addClass('select2-no-results').html('You&rsquo;ve already added this TV show. Try a different one').appendTo($listItem);
        // hasSelectedShow = false;

        // removes default plugin tv show added in their format
        // $('.select2-search-choice.visuallyhidden').remove();

        // return false;


      // } else {
        // new show, add it
        // if background of TV show selected is different from current bg image replace it
        var backgroundSource = $('.bg').css('background-image'),
            TVshowBackground = 'url(https://image.tmdb.org/t/p/original/' + selectedTVshow[0].bg + ')';

        if (TVshowBackground != backgroundSource) {
          var image = new Image();
          image.src = 'https://image.tmdb.org/t/p/original/' + selectedTVshow[0].bg;

          // allow time to preload image before showing
          setTimeout(function(){
            $('.bg').css('background-image', TVshowBackground);
            image = null;
          }, 1400);
        }

        // change # seasons input for that specific TV show
        $('.seasons').attr('max', selectedTVshow[0].seasons);


        // save TV show's details
        totalSeasons = selectedTVshow[0].seasons;
        episodes = selectedTVshow[0].episodes;
        runtime = selectedTVshow[0].runtime;

        // if poster is empty, show default placeholder
        var poster = null;
        if (selectedTVshow[0].poster == null) {
          poster = '../images/126.42.jpg';
        } else {
          poster = 'https://image.tmdb.org/t/p/w342' + selectedTVshow[0].poster;
        }

        // prepend the <li> with TV show and hide it for now
        $('.container__list-of-shows').prepend('<li class="show-to-add  visuallyhidden"><a href="#" class="btn icon-close  js-remove-item" title="Remove this TV show"></a><img src="' + poster + '" alt="' + selectedTVshow[0].text + '" /><div class="container__list-of-shows__info"><span class="container__list-of-shows__info__title" title="TV show title">' + selectedTVshow[0].text +'</span><span class="container__list-of-shows__info__seasons" title="Nr. of seasons"></span><span class="container__list-of-shows__info__wasted-time  visuallyhidden"></span></div></li>');

        // adds value of TV show text to input
        $('input').val(selectedTVshow[0].text);

      // }

      } else {
        // hides background image
        $('.bg').addClass('hide');

        // removes background image
        setTimeout(function() {$('.bg').attr('src', '');}, 1000);
      }
  } else {
    var $listItem = $('.select2-results');
    $listItem.parent().css("display", "block");
    $('<li />').addClass('select2-no-results').html('This show has an error. Please select a different one.').appendTo($listItem);
    hasSelectedShow = false;

    // removes default plugin tv show added in their format
    $('.select2-search-choice.visuallyhidden').remove();

    return false;
  }

  // removes default plugin tv show added in their format
  $('.select2-search-choice.visuallyhidden').remove();



  // focuses on seasons input
  setTimeout(function() {
    $('.seasons').focus().val(selectedTVshow[0].seasons);
    showSubmitButton();
  }, 100);

  hasSelectedShow = true;
});





// so that event is only fired once (not everytime user types letter)
var flagScrollHidden = false;

// blurs the input on "closing" of autocomplete plugin
$('.tvshow').on("select2-close", function(e){
  $('input').blur();
  flagScrollHidden = false;
  removeNoScroll();
})

// on autosuggestions open, disables scrolling
$('.tvshow').on("select2-loaded", function() {
  if (flagScrollHidden == false)
    addNoScroll();
})



// disables scrolling of body
function addNoScroll() {
  if (!$('body').hasClass('noScroll')) {
    $('body').addClass('noScroll');
    flagScrollHidden = true;
  }


  // makes posters blurry so that dropdown is easier to read
  var items = $('.container__list-of-shows').children().size();
  if (items >= 1 && items < 4) {
    $('.container__list-of-shows > li:nth-child(1)').addClass('blur-and-reduce-opacity');
    $('.container__list-of-shows > li:nth-child(2)').addClass('blur-and-reduce-opacity');
  } else if (items >= 4 && items < 5) {
    $('.container__list-of-shows > li:nth-child(1)').addClass('blur-and-reduce-opacity');
    $('.container__list-of-shows > li:nth-child(2)').addClass('blur-and-reduce-opacity');
    $('.container__list-of-shows > li:nth-child(3)').addClass('blur-and-reduce-opacity');
  } else if (items >= 5) {
    $('.container__list-of-shows > li:nth-child(2)').addClass('blur-and-reduce-opacity');
    $('.container__list-of-shows > li:nth-child(3)').addClass('blur-and-reduce-opacity');
  }
}



// enables scrolling of body
function removeNoScroll() {
  if ($('body').hasClass('noScroll'))
    $('body').removeClass('noScroll');


  // removes posters blurry so that dropdown is easier to read
  $('.container__list-of-shows').children().removeClass('blur-and-reduce-opacity');
}
