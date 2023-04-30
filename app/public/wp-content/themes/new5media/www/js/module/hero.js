export default function hero() {
	const hero = document.querySelector('.heroJS');
	if (hero) {
		const image = hero.querySelector('.heroImg');
		const minW = image.offsetWidth;
		const minH = image.offsetHeight;

		const wrapper = hero.querySelector('.heroWrapper');
		wrapper.style.height =
			wrapper.offsetHeight + (window.innerHeight - minH) + 100 + 'px';

		let speed = 0.08;
		const offset = {
			W: 0,
			H: 0,
		};

		function expand() {
			offset.H += (window.pageYOffset - offset.H) * speed;
			offset.W += (window.pageYOffset - offset.W) * speed;

			if (offset.H <= window.innerHeight - minH) {
				image.style.width = minW + offset.W + 'px';
				image.style.height = minH + offset.H + 'px';
			} else {
				offset.H = window.innerHeight - minH;
				offset.W = window.innerWidth - minW;
			}

			const animate = requestAnimationFrame(expand);
		}
		expand();
	}
}
