export default function header() {
	const header = document.querySelector('.headerJS');
	if (header) {
		const open = header.querySelector('.headerOpen');
		const close = header.querySelector('.headerClose');

		open.addEventListener('click', () => {
			header.classList.add('menu-open');
			document.querySelector('body').classList.add('no-scroll');
		});
		close.addEventListener('click', () => {
			header.classList.remove('menu-open');
			document.querySelector('body').classList.remove('no-scroll');
		});
	}
}
