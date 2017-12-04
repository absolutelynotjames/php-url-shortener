"use strict";

var app = new Vue({
    el: '#app',
    ready: function() {},
    data: {
        input: {
            completed: false,
            placeHolderText: 'Enter url...',
            url: '',
            style: {
                width: '500px',
                transition: 'width 0.5s'
            }
        },
        button: {
            text: 'Submit'
        }
    },
    methods: {
        submitUrl: function() {
            this.input.style.width = '0px';
            var payload = JSON.stringify({
                "url": this.input.url,
            });
            this.$http.post('/new', payload).then(function success(response) {
                console.log(response.body);
                this.input.completed = true;
                this.input.url = response.body;
                this.button.text = 'Copy';
                this.input.style.width = (this.input.url.length) + 'em';
                console.log(this.input.completed);
            }, function error(response) {
                console.log(response);
            });
        }
    }
})
