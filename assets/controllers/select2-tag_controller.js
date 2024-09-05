import 'select2';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';


import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        group: String,
    }

    connect() {
        this.connectedSelect2 = [];
        this.choices = [];

        this.select2instance = $(this.element).select2({
            theme: "bootstrap-5",
            tags: true,
            // data: this.getSelect2Data()
        }).on('select2:opening', (e) => {
            this.updateConnectedSelects();
            this.setConnectedSelect2Choices();

            this.select2instance.empty();

            this.choices.forEach((choice) => {
                console.log(choice)
                this.select2instance.append(new Option(choice, choice, false, false));
            });

            this.select2instance.trigger('change');
        });
    }

    updateConnectedSelects(){
        $(`[data-select2-tag-group-value="${this.groupValue}"]`).each((index, element) => {
            // if (!element.isSameNode(this.element)) {
            this.connectedSelect2.push(element)
            // }
        })
    }

    setConnectedSelect2Choices() {
        this.choices = [];
        this.connectedSelect2.forEach(element => {
            this.choices = this.choices.concat(Array.from(element.options).map((option) => option.value));
        })
        this.choices = this.choices.filter((value, index, array)=>{
            return array.indexOf(value) === index;
        });
    }
}
