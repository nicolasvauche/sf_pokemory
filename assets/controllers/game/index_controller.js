// controllers/game_index_controller.js
import {Controller} from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["card"]

    connect() {
        this.flippedCards = []
        this.tries = 0
    }

    flipCard(event) {
        const card = event.target.closest('.app-card')
        if (card && !card.classList.contains('flipped') && this.flippedCards.length < 2) {
            this.doFlipCard(card)
        }
    }

    doFlipCard(card) {
        card.classList.add('flipped')
        this.flippedCards.push(card)

        if (this.flippedCards.length === 2) {
            setTimeout(() => {
                this.resetFlippedCards()
                this.incrementTries()
            }, 1000)
        }
    }

    resetFlippedCards() {
        this.flippedCards.forEach(card => card.classList.remove('flipped'))
        this.flippedCards = []
    }

    incrementTries() {
        this.tries++
        document.getElementById('tries').innerText = this.tries + (this.tries > 1 ? ' essais' : ' essai')
    }
}
