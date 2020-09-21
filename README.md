# Simple MVC

Simple MVC is a bare bones PHP MVC framework with potential to be expanded to support any requirements.

It supports templates, public/private modules and uses custom API adapter to connect to any api server returning JSON responses.

Uses .htaccess to manage the controller actions

## Usage
example: http://yourdomain.com/company/about maps to:
	Module Controller: /private/modules/CompanyController.php
	Controller Method: aboutAction() {}
