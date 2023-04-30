export default function scrollTrigger() {
	let sections = document.querySelectorAll('.scrollTrigger');
	const start = window.innerHeight / 3;
	sections.forEach((el) => {
		let animate = true;
		function underline() {
			let lines = el.querySelectorAll('.align-underline');
			if (animate) {
				lines.forEach((line, i) => {
					setTimeout(() => {
						line.classList.add('active');
					}, i * 150);
				});
			}
			!animate;
		}
		let check = el.getBoundingClientRect().top;
		if (check <= start) {
			el.classList.add('active');
			underline();
		}
		window.addEventListener('scroll', () => {
			let pos = el.getBoundingClientRect().top;
			if (pos <= start) {
				el.classList.add('active');
				underline();
			}
		});
	});
}
