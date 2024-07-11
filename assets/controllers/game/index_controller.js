import {Controller} from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["card"]

    connect() {
        this.flippedCards = []
        this.foundPairs = []
        this.tries = 0
        this.gameId = this.element.dataset.gameId
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

                if (this.foundPairs.length === this.cardTargets.length) {
                    this.completeGame()
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

    async completeGame() {
        try {
            const response = await fetch(`/complete-game/${this.gameId}/${this.tries}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                alert('Partie gagnée !')
                window.location.href = '/classement'
            }
        } catch (error) {
            console.error('Error completing the game:', error);
        }
    }
}
