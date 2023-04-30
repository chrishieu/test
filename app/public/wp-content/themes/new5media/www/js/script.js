//===== IMPORT MODULES =====//

import loader from './module/loader.js';
import header from './module/header.js';
import hero from './module/hero.js';
import hover from './module/hover.js';
import swiper from './module/swiper.js';
import scrollTrigger from './module/scrollTrigger.js';

//===== EXECUTE =====//

window.addEventListener('load', () => {
	try {
		header();
	} catch {
		console.error('Header scroll is not working!');
	}
	try {
		hero();
	} catch {
		console.error('Hero is not working!');
	}
	try {
		scrollTrigger();
	} catch {
		console.error('Scroll trigger is not working!');
	}
	try {
		hover();
	} catch {
		console.error('Hover is not working!');
	}
	try {
		swiper();
	} catch {
		console.error('Swiper is not working!');
	}
	try {
		loader();
	} catch {
		console.error('Loader is not working!');
	}
});
