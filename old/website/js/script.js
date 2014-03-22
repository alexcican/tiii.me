/* Author:

*/









		var myScroll; //load the myScroll
		window.onload = initial_orientation();
		function initial_orientation() {
			if ( orientation == 0 ) { //means when page loaded is portrait; load the scroller
				function loaded() {
					myScroll = new iScroll('wrapper');
				}
				window.addEventListener('load', loaded, false);
			}
			else if ((orientation == 90 ) || (orientation == -90) || (orientation == 180)) { //else it's landscape

			}
		}

		window.onorientationchange = function(){ shit(); }
		function shit() {
			if(typeof window.onorientationchange != 'undefined'){
				if ( orientation == 0 ) {
					myScroll = new iScroll('wrapper');
					window.addEventListener('load', shit, false);
				}
				else if ((orientation == 90 ) || (orientation == -90) || (orientation == 180)) {
					myScroll.destroy();
					myScroll = null;
					window.addEventListener('load', shit, false);
				}
			}
		}
