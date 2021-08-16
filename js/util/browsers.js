/**
 * Check if the current browser is IE by looking for "trident" within the user-agent string
 *
 * @returns {boolean}
 */
export function isIE() {
	return window.navigator.userAgent.toLowerCase().indexOf('trident') !== -1;
}
