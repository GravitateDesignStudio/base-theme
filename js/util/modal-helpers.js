import Modal from '../components/modal';
import { getVideoEmbedURL } from './video-url';

export function showVideoModal(videoUrl) {
	if (!videoUrl) {
		return;
	}

	const embedUrl = getVideoEmbedURL(videoUrl);

	Modal.show('', {
		modalClass: 'modal modal-video'
	});

	const isDirectURL = embedUrl.toLowerCase().indexOf('.mp4') !== -1;

	const templateMarkup = `
		<div class="modal__video-container">
			${isDirectURL ? `
				<video class="modal__video-embed" src="${embedUrl}" controls autoplay></video>
			` : `
				<iframe class="modal__video-embed"
					src="${embedUrl}"
					width="640"
					height="360"
					frameborder="0"
					allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen>
				</iframe>
			`}
		</div>
	`;

	// set the content to begin the iframe load but do not remove the loading animation
	Modal.setContent(templateMarkup, false);

	const iframeEl = document.querySelector('.modal__video-embed');

	// wait for the 'load' event to fire before removing the loading animation
	iframeEl.addEventListener(isDirectURL ? 'loadeddata' : 'load', () => {
		Modal.setLoading(false);
	});
}
