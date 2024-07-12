import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        this.loadSound('clickSound', '/sound/click-sound.mp3');

        const clickSoundButtons = document.querySelectorAll('.make-sound');
        clickSoundButtons.forEach(elt => {
            elt.addEventListener('click', () => {
                this.playSound(this.clickSound);
            });
        });
    }

    async loadSound(name, url) {
        try {
            const response = await fetch(url);
            const arrayBuffer = await response.arrayBuffer();
            this[name] = await this.audioContext.decodeAudioData(arrayBuffer);
        } catch (error) {
            console.error(`Failed to load sound ${name} from ${url}:`, error);
        }
    }

    playSound(buffer) {
        if (!buffer) {
            console.error('Sound buffer is not loaded');
            return;
        }
        const source = this.audioContext.createBufferSource();
        source.buffer = buffer;
        source.connect(this.audioContext.destination);
        source.start(0);
    }
}
