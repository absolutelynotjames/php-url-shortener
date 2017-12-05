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
})
