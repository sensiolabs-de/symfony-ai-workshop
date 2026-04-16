import { Controller } from '@hotwired/stimulus';

/*
 * Reveals the latest bot answer character by character.
 *
 * The HTML is rendered right away, so markdown formatting stays intact, and only
 * its text nodes get filled up step by step.
 */
export default class extends Controller {
    static values = {
        content: String,
        speed: { type: Number, default: 5 },
    };

    connect() {
        this.element.innerHTML = this.contentValue;
        this.queue = [];

        const walker = document.createTreeWalker(this.element, NodeFilter.SHOW_TEXT);
        while (walker.nextNode()) {
            this.queue.push({ node: walker.currentNode, text: walker.currentNode.textContent });
            walker.currentNode.textContent = '';
        }

        this.timer = setInterval(() => this.typeNextCharacter(), this.speedValue);
    }

    disconnect() {
        clearInterval(this.timer);
    }

    typeNextCharacter() {
        const current = this.queue[0];

        if (!current) {
            clearInterval(this.timer);

            return;
        }

        current.node.textContent = current.text.slice(0, current.node.textContent.length + 1);

        if (current.node.textContent.length >= current.text.length) {
            this.queue.shift();
        }
    }
}
