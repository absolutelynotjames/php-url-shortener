"use strict";

var app = new Vue({
    el: '#app',
    ready: function () { 
        this.csrfName = document.getElementById('csrf_name').value;
        this.csrfValue = document.getElementById('csrf_value').value;
    },
    data: {
        input: {
            completed: false,
            disabled: "disabled",
            placeHolderText: 'Enter url...',
            url: '',
            style: {
                width: '500px',
                transition: 'width 0.5s'
            }
        },
        button: {
            text: 'Submit',
            disabled: 'disabled'
        },
    },
    watch: {
        'input.url': function () {
            if (this.formIsValid()) {
                this.$set('input.disabled', false)
            }
            else {
                this.$set('input.disabled', "disabled")
            }
            console.log(this.input.disabled);
            console.dir(this);                        
        }
    },
    methods: {
        formIsValid: function () {
            if (this.$els.input.validity.valid == true) {
                return 'true';
            }
            return false;
        },
        buttonHandler: function () {
            if (this.input.completed) {

                //copy text to clipboard
                this.$els.input.select();

                try {
                    document.execCommand('copy');
                } catch (err) {
                    console.log(err);
                }
            }
            else {
                this.input.style.width = '0px';
                var payload = JSON.stringify({
                    "url": this.input.url,
                });
                this.$http.post('/new', payload).then(function success(response) {

                    //short url is supplied by the url param of response body
                    var shortUrl = (JSON.parse(response.body)).url;

                    //update our bindings
                    this.input.completed = true;
                    this.input.placeHolderText = shortUrl;
                    this.input.url = shortUrl;
                    this.button.text = 'Copy';

                    //try to simulate a more reasonable input width based on url length
                    this.input.style.width = ((this.input.url.length) / 1.5) + 'rem';

                }, function error(response) {
                    console.log(response);
                });
            }
        }
    }
})
