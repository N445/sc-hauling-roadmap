import 'select2';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';


import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        $(this.element).select2({
            theme: "bootstrap-5",
        });
    }
}