// DETECT IF WINDOWS FOR STYLING
if (navigator.userAgent.indexOf('Windows NT 6.1') > 0)
  $('body').addClass('windows');





// LOCALSTORAGE FUNCTIONS

// check to see if supports LocalStorage
function supportsLocalStorage() {
  try {
    return 'localStorage' in window && window['localStorage'] !== null;
  } catch (e) {
    return false;
  }
}



// save the state in LocalStorage
function save(time) {
  if (!supportsLocalStorage()) {return false;}
  localStorage['time'] = time;

  setTimeout(function() {
    localStorage['shows'] = $('.container__list-of-shows').html();
  }, 500);
}



// load the text from LocalStorage
function load() {
  // load minutes
  if (localStorage['time']) {
    totalTimeWastedInMinutes = parseFloat(localStorage['time']);
    convertMinutes(parseFloat(localStorage['time']));
  }


  // load shows
  if (localStorage['shows'])
    $('.container__list-of-shows').html(localStorage['shows']);


  // if TV list is not empty, show top container with time
  if (($('.container__list-of-shows li').length) >= 1)
    // show results (time wasted) container
    if ($('.result-container').is('.visuallyhidden')) {
      $('.result-container').removeClass('visuallyhidden');
      $('.result-container').parent().removeClass('hiding');
    }
}



// resets all saved data
function reset() {
  localStorage.removeItem('time');
  localStorage.removeItem('shows');
  document.location.reload(true);
}



// loads shows from localstorage
setTimeout(function() {
  load();
}, 400);





// show witty console message
console.log('Yo!\nThis app was created by Alex Cican: http://alexcican.com\nQuestions? alex@alexcican.com');
