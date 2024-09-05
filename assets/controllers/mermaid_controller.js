import mermaid from 'mermaid'


import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        mermaid.initialize({ startOnLoad: false });
        mermaid.run({
            nodes: [this.element],
        });
    }
}