"use strict";

var app = new Vue({
    el: '#app',
    ready: function() {},
    data: {
        input: {
            enabled: true,
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
                this.input.url = response.body;
                this.input.enabled = false;
                this.button.text = 'Copy';
                this.input.style.width = ((this.input.url.length) * 8) + 'px';
            }, function error(response) {
                console.log(response);
            });
        }
    }
})
