'use strict';


// let colorOption,
// 		colorOption2,
// 		root = document.documentElement,
// 		queryString = window.location.search;

// if (queryString) {
// 	let urlParams = new URLSearchParams(queryString)
// 	colorOption = urlParams.get('bg')
// 	colorOption2 = urlParams.get('color')
// } 

// root.style.setProperty('--bg-color', '#' + colorOption);
// root.style.setProperty('--color', '#' + colorOption2);


// Loading

window.addEventListener('load', function(event) {
	const loading = document.querySelectorAll('.loading');
	loading.forEach(load => {
		load.classList.remove('loading');
		load.classList.add('transition');
		setTimeout(function(){
			load.classList.remove('transition');
			load.classList.add('animating');
		}, 1400);
	});
});



// SCROLL STUFF

// let lastPos = null,
// 	timer = 0,
// 	newPos;

// const el = document.querySelectorAll('.svg-border');
// function checkScrollSpeed(){
// 	function clear() {
// 		lastPos = null;
// 		//el.removeAttr('style');
// 	}

// 	newPos = window.scrollY;

// 	if (lastPos != null){ // && newPos < maxScroll 
// 		var delta = Math.abs(((newPos - lastPos) / 100)) + 1;

// 		el.forEach(item => {
// 			//item.classList.remove('loading');
// 			item.style.transform = "scale(1, " + delta + ")";
// 		});

// 	}
// 	lastPos = newPos;
// 	timer && clearTimeout(timer);
// 	timer = setTimeout(clear, 30);
// }

// window.addEventListener('scroll', function(event) {
// 	//checkScrollSpeed();
// });



const sections = document.querySelectorAll('.is-visible');
const options = {
	threshold: 0.3
}
let observer = new IntersectionObserver((entries) => {
	entries.forEach(entry => {
		if (entry.intersectionRatio > .3) {

			if (entry.target.nodeName == 'VIDEO') {
				entry.target.play();
			}

			if (entry.target.classList.contains('counter')) {
				const counters = document.querySelectorAll('.count');
				const speed = 1200;

				const test = entry.target.querySelector('.count');
				
				const updateCount = () => {
					const target = parseInt(test.getAttribute('data-target'));
					const count = parseInt(test.innerText);
					const increment = 1;

					if (test.classList.contains('down')) {
						if (count > target) {
							test.innerText = count - increment;
							setTimeout(updateCount, 1);
						} else {
							test.innerText = target;
						}
					} else {
						if (count < target) {
							test.innerText = count + increment;
							setTimeout(updateCount, 50);
						} else {
							test.innerText = target;
						}
					}
				}
				updateCount();
			}

			if (entry.target.classList.contains('reveal')) {				
				entry.target.classList.add('active');
			}	

		} else {

			if (entry.target.nodeName == 'VIDEO') {
				entry.target.pause();
			}

			// if (entry.target.classList.contains('reveal')) {				
			// 	entry.target.classList.remove('active');
			// }

		}	
	});
}, options);
sections.forEach(section => {
	observer.observe(section);
})


// Play audio
document.querySelectorAll('[data-play-audio]').forEach(
	audioBtn => audioBtn.addEventListener('click', function() { 
		const 	audioBlock = audioBtn.parentNode.closest('.tpa-audio-script'),
				audioPart = audioBtn.previousElementSibling,
				spns = audioBtn.nextElementSibling.getElementsByTagName('span');
		
		if (audioBlock.classList.contains('is-playing')) {
			audioBlock.classList.remove('is-playing')
			audioPart.pause()
		} else {
			audioBlock.classList.add('is-playing')
			audioPart.play();
		}

		audioPart.addEventListener('timeupdate', f1);	

		function f1() {
			var i;  
			for (i = 0 ; i < spns.length ; i++){
				var time = spns[i].dataset.time;
				if (time < audioPart.currentTime) {
					if (i > 0) spns[i-1].style.opacity = '';
					spns[i].style.opacity = 1;
				}
			}
			if (audioPart.currentTime === audioPart.duration) {
				spns[spns.length - 1].style.opacity = '';
				audioBlock.classList.remove('is-playing')
			}


		}
		
	})
)



document.querySelectorAll('[data-play-video]').forEach(
	videoBtn => videoBtn.addEventListener('click', function() { 

		// Kill audio
		let audioPlayers = Array.from(document.querySelectorAll('audio'));
		audioPlayers.forEach(function(audio) {
		  audio.pause();
		});		

		if (deviceType() == 'mobile') {

			let video = document.createElement('video');
			video.src = videoBtn.dataset.src;
			video.autoplay = true;

		} else {
		
			const videoBlock = videoBtn.parentNode.closest('.tpa-video-player')

			let video = document.createElement('video'),
				wrapper = document.createElement('div'),
				button = document.createElement('button');
			
			video.src = videoBtn.dataset.src;
			button.innerText = 'X';
			wrapper.id = 'player-overlay';
			wrapper.appendChild(button);
			wrapper.appendChild(video);		
			document.body.appendChild(wrapper);

			video.controls = true;
			video.autoplay = true;
			
			setTimeout(function(){
				wrapper.classList.add('active');
			}, 200);

			button.addEventListener('click', function(e) {
				wrapper.remove();
			});
			wrapper.addEventListener('click', function(e) {
				if (e.target.id == 'player-overlay') {
					wrapper.remove();
				}
			});
		}
	})
)


let audios = Array.from(document.querySelectorAll('audio'));

audios.forEach(function(audio) {
  audio.addEventListener('play', pauseOtherAudioPlaying);
});

function pauseOtherAudioPlaying(event) {
  var audiosToPause = audios.filter(function(audio) {
    return !audio.paused && audio != event.target;
  });
  audiosToPause.forEach(function(audio) { 
  	audio.pause();
  });
}


function deviceType() {
    const ua = navigator.userAgent;
    if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
        return "tablet";
    }
    else if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
        return "mobile";
    }
    return "desktop";
}
