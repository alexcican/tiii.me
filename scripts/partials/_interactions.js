// WHAT HAPPENS WHEN YOU CLICK ON STUFF

// timewasted in minutes
var timeWastedInMinutes = 0,
    totalTimeWastedInMinutes = 0,
    episodesPerSeason = 0,
    seasons = 0;


    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width,
        height = (window.innerHeight > 0) ? window.innerHeight : screen.height;

    // $('.container--small').css('height', height);


// on submit button, adds a TV show to the list
$('.submit').on('click touchstart', function() {
  seasons = $('.seasons').val();

  // average how many episodes there are in a season
  episodesPerSeason = episodes / totalSeasons;

  // adds # of seasons to the text info of TV show
  $('.show-to-add').find('.container__list-of-shows__info__seasons').html(seasons);

  // for CSS animations (adds TV show in list)
  $('.show-to-add').removeClass('visuallyhidden').addClass('showing');
  setTimeout(function() {
    $('.show-to-add').removeClass('showing').addClass('show');
  }, 100);

  // avoids clash of classes .show-to-add
  setTimeout(function() {
    $('.show-to-add').removeClass();
  }, 500);



  // calculate time wasted watchin specific show in minutes
  timeWastedInMinutes = runtime * (episodesPerSeason * seasons);

  // add # of minutes to the show so that when user removes show
  // this number is subtacted from total
  $('.show-to-add').find('.container__list-of-shows__info__wasted-time').html(timeWastedInMinutes);

  // calculate total time wasted in minutes
  totalTimeWastedInMinutes = totalTimeWastedInMinutes + timeWastedInMinutes;

  // convert into days, hours, minutes
  convertMinutes(totalTimeWastedInMinutes);

  // save to localstorage
  save(totalTimeWastedInMinutes);



  // clean up everything
  $('input').val("");

  // for CSS animations (hiding submit button)
  $(this).removeClass('show').addClass('hiding');
  setTimeout(function() {
    $('.submit').removeClass('hiding').addClass('visuallyhidden');
  }, 100);

  // focus on input again
  $('.select2-input').focus();



  // show results (time wasted) container
  if ($('.result-container').is('.visuallyhidden')) {
    $('.result-container').removeClass('visuallyhidden');
    $('.result-container').parent().removeClass('hiding');
  } else {

    // calculates previous time of show (only if more than 1 show is in list)
    showTemporaryTimerOfPreviousShow(timeWastedInMinutes);
  }


  return false;
})




// shows temporary timer of previous show above global timer
function showTemporaryTimerOfPreviousShow(timeWasted, subtractShow) {
  var previousShowTime = convertMinutes(timeWasted, true);

  // if removing TV show, display "-" instead of "+"
  if (subtractShow) {
    $('.container__top__previous-show-time').html('- ' + previousShowTime);
  } else {
    $('.container__top__previous-show-time').html('+ ' + previousShowTime);
  }

  $('.container__top__previous-show-time').removeClass('visuallyhidden').addClass('show');

  // CSS animations for hiding the temporary counter number
  setTimeout(function() {
    $('.container__top__previous-show-time').removeClass('show').addClass('hiding');
    setTimeout(function() {
      $('.container__top__previous-show-time').removeClass('hiding').addClass('visuallyhidden');
    }, 400);
  }, 3000);
}



// converts minutes into days, hours, minutes
function convertMinutes(totalMinutes, dontUpdateClock) {
  var days = Math.floor(totalMinutes / 1440);
  var hours = Math.floor((totalMinutes - days * 1440) / 60);
  var minutes = Math.floor(totalMinutes - (days * 1440) - (hours * 60))

  // formats 8 into 08
  days = formatNumber(days, 2);
  hours = formatNumber(hours, 2);
  minutes = formatNumber(minutes, 2);

  // updates clock counter if no flag is set
  if (dontUpdateClock) {
    return (days + ' : ' + hours + ' : ' + minutes);

  } else {
    // updates clock counter
    updateClock(days, hours, minutes);
  }
}



// converts 5 into 05
function formatNumber(number, targetLength) {
  var output = number + '';
  while (output.length < targetLength) {
    output = '0' + output;
  }
  return output;
}



// updates the clock counter with new value
function updateClock(days, hours, minutes) {
  var $container = $('.container__top__hours-wasted'),
      $daysContainer = $container.find('.hours-wasted__days'),
      $hoursContainer = $container.find('.hours-wasted__hours'),
      $minutesContainer = $container.find('.hours-wasted__minutes');

  // replace content
  // $daysContainer.find('.numbers').text(days);
  // $hoursContainer.find('.numbers').text(hours);
  // $minutesContainer.find('.numbers').text(minutes);

  // countUp plugin update number
  var daysCount = new countUp("days", 00, days, 0, 2),
      hoursCount = new countUp("hours", 00, hours, 0, 4),
      minutesCount = new countUp("minutes", 00, minutes, 0, 6);
  daysCount.reset();
  hoursCount.reset();
  minutesCount.reset();
  daysCount.start();
  hoursCount.start();
  minutesCount.start();

  // check label "1 minute" not "1 minutes"
  (days == '01') ? $daysContainer.find('.description').text('day') : $daysContainer.find('.description').text('days');
  (hours == '01') ? $hoursContainer.find('.description').text('hour') : $hoursContainer.find('.description').text('hours');
  (minutes == '01') ? $minutesContainer.find('.description').text('minute') : $minutesContainer.find('.description').text('minutes');



  // tweet link text change
  if (days < 01) {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + hours + ' hours and ' + minutes + ' minutes of my life watching TV shows. Calculate your time:&url=tiii.me');
  } else if (days == 01) {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + days + ' day, ' + hours + ' hours, and ' + minutes + ' minutes of my life watching TV shows. Calculate your time:&url=tiii.me');
  } else if (hours < 01) {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + days + ' days and ' + minutes + ' minutes of my life watching TV shows. Calculate your time:&url=tiii.me');
  } else if (hours == 01) {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + days + ' days, ' + hours + ' hour, and ' + minutes + ' minutes of my life watching TV shows. Calculate your time:&url=tiii.me');
  } else if (minutes < 01) {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + days + ' days and ' + hours + ' hours of my life watching TV shows. Calculate your time:&url=tiii.me');
  } else if (minutes == 01) {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + days + ' days, ' + hours + ' hours, and ' + minutes + ' minute of my life watching TV shows. Calculate your time:&url=tiii.me');
  } else if ((hours < 01) && (minutes < 01)) {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + days + ' days of my life watching TV shows. Calculate your time:&url=tiii.me');
  } else {
    $('.sharing-link').attr('href', 'https://twitter.com/share?text=I’ve wasted ' + days + ' days, ' + hours + ' hours, and ' + minutes + ' minutes of my life watching TV shows. Calculate your time:&url=tiii.me');
  }
}





// on seasons focus check if there is TV show added and show submit button
$('.seasons').on('keyup change', function() {
  if ($('.select2-input').val().length !== 0)
    if (hasSelectedShow)
      if ($(this).val().length !== 0)
        showSubmitButton()
})





// shows submit button
function showSubmitButton() {
  var $this = $('.submit');
  if ($this.hasClass('show')) {

  } else {
    $this.removeClass('visuallyhidden').addClass('showing');

    setTimeout(function() {
      $this.removeClass('showing').addClass('show');
    }, 100);
  }
}





// shows modal window on click
$('.js-show-modal').on('click touchstart', function() {
  showModalWindow();
  return false;
})

// clicking anywhere inside navigation or heading won’t close main menu popover
$('.about__content__inner').on('click touchstart', function(e){
    e.stopPropagation();
})

// hides modal window on click
$('.about__content').on('click touchstart', function(e){
  hideModalWindow();
  return false;
})



// shows modal window
function showModalWindow() {
  $('.about__content').removeClass('hide').addClass('show');
  $('.js-hide-modal').removeClass('visuallyhidden');

  // keyboard navigation ordering (first popover links)
  $('.about__content a').attr('tabindex','');
  $('.about__content a.about__icon').attr('tabindex', '0');

  $('body').addClass('noScroll');
}

// hides modal window
function hideModalWindow() {
  $('.about__content').removeClass('show').addClass('hiding');
  setTimeout(function() {
    $('.about__content').addClass('hide').removeClass('hiding show');
    $('.js-hide-modal').addClass('visuallyhidden');
  }, 150);
  $('body').removeClass('noScroll');

  // keyboard navigation ordering (hides popover links)
  $('.about__content a').attr('tabindex','-1');
}





// resets all data button click calls function
$('.reset-local-storage').on('click touchstart', function() {
  reset();
  return false;
})
