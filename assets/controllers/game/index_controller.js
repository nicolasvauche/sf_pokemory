import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["card"];

    connect() {
        this.flippedCards = [];
        this.foundPairs = [];
        this.tries = 0;
        this.gameId = this.element.dataset.gameId;
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();

        this.loadSound('flipSound', '/sound/flip-sound.mp3');
        this.loadSound('successSound', '/sound/success-sound.mp3');
        this.loadSound('failedSound', '/sound/failed-sound.mp3');
        this.loadSound('foundSound', '/sound/found-sound.mp3');
    }

    loadSound(name, url) {
        fetch(url)
            .then(response => response.arrayBuffer())
            .then(arrayBuffer => {
                return this.audioContext.decodeAudioData(arrayBuffer);
            })
            .then(audioBuffer => {
                this[name] = audioBuffer;
            });
    }

    playSound(buffer) {
        const source = this.audioContext.createBufferSource();
        source.buffer = buffer;
        source.connect(this.audioContext.destination);
        source.start(0);
    }

    flipCard(event) {
        const card = event.target.closest('.app-card');
        if (card && !card.classList.contains('flipped') && !this.isCardInFoundPairs(card) && this.flippedCards.length < 2) {
            this.doFlipCard(card);
        }
    }

    doFlipCard(card) {
        this.playSound(this.flipSound);
        card.classList.add('flipped');
        this.flippedCards.push(card);

        if (this.flippedCards.length === 2) {
            const card1Name = this.flippedCards[0].querySelector('.card-front h3').innerText;
            const card2Name = this.flippedCards[1].querySelector('.card-front h3').innerText;

            if (card1Name === card2Name) {
                setTimeout(() => {
                    this.playSound(this.foundSound);
                    this.foundPairs.push(this.flippedCards[0], this.flippedCards[1]);
                    this.flippedCards = [];
                    this.incrementTries();

                    if (this.foundPairs.length === this.cardTargets.length) {
                        this.completeGame();
                    }
                }, 1000);
            } else {
                setTimeout(() => {
                    this.playSound(this.failedSound);

                    setTimeout(() => {
                        this.resetFlippedCards();
                        this.incrementTries();
                    }, 1000);
                }, 1000);
            }
        }
    }

    resetFlippedCards() {
        this.flippedCards.forEach(card => card.classList.remove('flipped'));
        this.flippedCards = [];
    }

    incrementTries() {
        this.tries++;
        document.getElementById('tries').innerText = this.tries + (this.tries > 1 ? ' essais' : ' essai');
    }

    isCardInFoundPairs(card) {
        return this.foundPairs.includes(card);
    }

    async completeGame() {
        try {
            const response = await fetch(`/complete-game/${this.gameId}/${this.tries}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                setTimeout(() => {
                    this.playSound(this.successSound);
                    alert('Partie gagnée !');
                    window.location.href = '/classement';
                }, 1000);
            }
        } catch (error) {
            console.error('Error completing the game:', error);
        }
    }
}
