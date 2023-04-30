export default function hover() {
	const hover = document.querySelectorAll('.align-hover');
	const cursor = {
		X: 0,
		Y: 0,
	};
	const pos = {
		top: 0,
		left: 0,
	};
	const speed = 0.08;
	hover.forEach((el) => {
		el.addEventListener('mousemove', (e) => {
			cursor.X = e.offsetX;
			cursor.Y = e.offsetY;
			el.classList.add('is-hover');
		});
		el.addEventListener('mouseleave', () => {
			el.classList.remove('is-hover');
		});
	});

	function follow() {
		pos.top += (cursor.Y - pos.top) * speed;
		pos.left += (cursor.X - pos.left) * speed;

		document.documentElement.style.setProperty('--hoverTop', `${pos.top}px`);
		document.documentElement.style.setProperty('--hoverLeft', `${pos.left}px`);

		const animate = requestAnimationFrame(follow);
	}
	follow();
}
