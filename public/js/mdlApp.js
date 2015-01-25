'use strict';

var mdlApp = angular.module('mdlApp', ['ngRoute']);

mdlApp.config(function($routeProvider) {
	$routeProvider

		.when('/', {
			templateUrl : 'pages/login.html',
			controller  : 'loginController'
		})

		.when('/profile', {
			templateUrl : 'pages/profile.html',
			controller  : 'profileController'
		})

		.when('/newAccount', {
			templateUrl : 'pages/newAccount.html',
			controller  : 'newAccountController'
		})

		.when('/settings', {
			templateUrl : 'pages/settings.html',
			controller  : 'settingsController'
		})

		.when('/passwords', {
			templateUrl : 'pages/passwords.html',
			controller  : 'passwordsController'
		})

		.when('/panel', {
			templateUrl : 'pages/panel.html',
			controller  : 'panelController',
		})

		.when('/addPassword', {
			templateUrl : 'pages/addPassword.html',
			controller  : 'passwordsController',
		})

		.when('/tweets', {
			templateUrl : 'pages/tweets.html',
			controller  : 'tweetsController'
		})

		.when('/texts', {
			templateUrl : 'pages/texts.html',
			controller  : 'textsController'
		})

		.otherwise({
			redirectTo: '/'
		});
});

mdlApp.factory('ShareData', function() {
    var user = {};
    var userFunc = {};

    userFunc.edit = function(data) {
        this.user = data;
    };
    userFunc.get = function() {
        return this.user;
    };

    return userFunc;
});