// controllers/game_index_controller.js
import {Controller} from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["card"]

    connect() {
        this.flippedCards = []
        this.foundPairs = []
        this.tries = 0
    }

    flipCard(event) {
        const card = event.target.closest('.app-card')
        if (card && !card.classList.contains('flipped') && !this.isCardInFoundPairs(card) && this.flippedCards.length < 2) {
            this.doFlipCard(card)
        }
    }

    doFlipCard(card) {
        card.classList.add('flipped')
        this.flippedCards.push(card)

        if (this.flippedCards.length === 2) {
            const card1Name = this.flippedCards[0].querySelector('.card-front h3').innerText
            const card2Name = this.flippedCards[1].querySelector('.card-front h3').innerText

            if (card1Name === card2Name) {
                this.foundPairs.push(this.flippedCards[0], this.flippedCards[1])
                this.flippedCards = []
                this.incrementTries()

                console.log('Paires trouvées:', this.foundPairs.length / 2)
                console.log('Total de cartes:', this.cardTargets.length)

                if (this.foundPairs.length === this.cardTargets.length) {
                    setTimeout(() => {
                        alert('Bravo ! Vous avez gagné en ' + this.tries + ' coup' + (this.tries > 1 ? 's' : '') + ' !')
                        //window.location.href = '/classement'
                    }, 500)
                }
            } else {
                setTimeout(() => {
                    this.resetFlippedCards()
                    this.incrementTries()
                }, 1000)
            }
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

    isCardInFoundPairs(card) {
        return this.foundPairs.includes(card)
    }
}
