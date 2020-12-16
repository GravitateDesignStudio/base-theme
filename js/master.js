import App from './App';

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', () => {
		window.app = new App();
	});
} else {
	window.app = new App();
}
