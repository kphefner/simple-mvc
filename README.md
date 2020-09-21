# Simple MVC

Simple MVC is a bare bones PHP MVC framework with potential to be expanded to support any requirements.

It supports templates, public/private modules and uses custom API adapter to connect to any api server returning JSON responses.

Uses .htaccess to manage the controller actions

## Installation

Copy the repository to a directory on an apache/php enabled machine.

Open /private/config.php and modify to meet your needs.

Launch a dev PHP server FROM INSIDE the /public folder, i.e.,
cd /yourcomputer/sites/simple-mvc/public

php -S 127.0.0.7:8080

*note: for cookie functionality to work locally, 127.0.0.1:8080 must be mapped to a domain name in the machine's host file.
For Mac, I recommend gasmask as a host file management app.

## Usage

example: http://yourdomain.com/company/about maps to:

	Module Controller: /private/modules/CompanyController.php
	
	Controller Method: aboutAction() {}

