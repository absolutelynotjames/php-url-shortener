# Simple URL Shortener

![alt text](https://raw.githubusercontent.com/absolutelynotjames/php-url-shortener/master/screenshot.png)

Tiny URL shortener built on SlimPHP and VueJS.

Provides a secure input field for a user to submit a url. Upon submission, validates the url, normalizes the url pattern, generates a unique hash, and returns a short url to the user based on the hash. The application will then route that short url to the original user provided url.

## Install the Application

Run this command to build the application

	composer install && npm install

Run this command to run the application

	composer start
