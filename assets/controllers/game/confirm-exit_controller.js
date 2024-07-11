import {Controller} from '@hotwired/stimulus'

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        this.boundConfirmExit = this.confirmExit.bind(this)
        window.addEventListener('beforeunload', this.boundConfirmExit)
        document.addEventListener('turbo:before-cache', this.removeConfirmExit)
        document.addEventListener('turbo:before-visit', this.handleTurboBeforeVisit)
    }

    disconnect() {
        window.removeEventListener('beforeunload', this.boundConfirmExit)
        document.removeEventListener('turbo:before-cache', this.removeConfirmExit)
        document.removeEventListener('turbo:before-visit', this.handleTurboBeforeVisit)
    }

    handleTurboBeforeVisit = (event) => {
        const confirmationMessage = 'Êtes-vous sûr de vouloir quitter cette partie ?'
        if (!window.confirm(confirmationMessage)) {
            event.preventDefault()
        }
    }

    removeConfirmExit = () => {
        window.removeEventListener('beforeunload', this.boundConfirmExit)
    }

    confirmExit(event) {
        const confirmationMessage = 'Êtes-vous sûr de vouloir quitter cette partie ?'
        event.returnValue = confirmationMessage
        return confirmationMessage
    }
}
