export function isIE() {
	return window.navigator.userAgent.toLowerCase().indexOf('trident') !== -1;
}
