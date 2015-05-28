'use strict';

var urlServer = "../app/";

mdlApp.controller('pageController', ['$scope','$http', 'ShareData', function($scope, $http, ShareData) {

 	$scope.submit = function() {
		var form = $("#loginForm").serialize();

       	$http({ method : 'POST',
                url : urlServer + 'functions.php',
                data: form,
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
       			}
       		}).  
			
			success(function(data) {
				if(data.response == "true")
				{
					$scope.loggedIn = true;
					ShareData.edit(data);			
					window.location.href = "#/panel"; 
				}

				else
					alert("Wrong Email or Password");
			}).

			error(function(data) {
				alert("Wrong Email or Password");
			});
	};

	$scope.logout = function(){
		$http.get(urlServer + 'functions.php?logout=true').
			success(function(data){
				window.location.href = "#/"; 
			});
	}
}]);

mdlApp.controller('passwordsController', ['$scope', 'ShareData', '$http', function($scope, ShareData, $http) {

	var data = {};
	$scope.user = ShareData.get();
	var url = urlServer + 'functions.php?app=passwords&id=' + $scope.user.id;

	$http.get(url, data).
		
		success(function(data) {
			if(data != "Empty")
			{
				$scope.passwords        = data;
				$scope.passwords_length = data.length;
			}

			else
			{
				$scope.passwords_length = 0;
			}
		}).

		error(function(data) {
			alert("Error");
		});

 	$scope.submit = function() {
		var form = $("#passwordsForm").serialize();

        $http({ method : 'POST',
                url : urlServer + 'functions.php',
                data: form,
                headers: {
                  //'Authorization': 'Basic dGVzdDp0ZXN0',
                  'Content-Type': 'application/x-www-form-urlencoded'
       			}}).  
			
			success(function(data) {
				if(data == "true")
				{
					alert("Password added.");
					window.location.href = "#/passwords"; 
				}

				else
					alert("Error");
			}).

			error(function(data) {
				alert("Exception");
			});
	};
}]);

mdlApp.controller('tweetsController', ['$scope', 'ShareData', '$http', function($scope, ShareData, $http) {

	$scope.dateFrom = new Date("1960-01-01");
	$scope.dateTo   = new Date();		
	$scope.user     = ShareData.get();
	var url = urlServer + 'functions.php?app=twitter&id=' + $scope.user.id;

	function parseDateMysql(timestamp) {
		var regex = /^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
		var parts = timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');
		
		return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
	}


	$http.get(url, {}).
		
		success(function(data) {
			if(data != "Empty") {
				$scope.tweets        = data;
				$scope.tweets_length = data.length;
			}

			else {
				$scope.tweets_length = 0;
			}
		}).

		error(function(data) {
			alert("Error");
		});

	$scope.filterDate = function (tweet) {
		var dt = parseDateMysql(tweet.tweet_date);
		//tweet.tweet_dt = dt.toLocaleFormat('"%A, %B %e, %Y"');
	    return true; //(dt >= $scope.dateFrom && dt <= $scope.dateTo );
	};	
}]);

mdlApp.controller('textsController', ['$scope','$http', function($scope, $http) {

	var data = {};
	var url = urlServer + 'text-json.php';

	$http.get(url, data).
		
		success(function(data) {
			$scope.texts        = data;
			$scope.texts_length = data.length;
		}).

		error(function(data) {
			alert("Error");
		});
}]);

mdlApp.controller('loginController', function($scope) {

});

mdlApp.controller('signupController', function($scope) {

});

mdlApp.controller('panelController', ['$scope', 'ShareData', function($scope, ShareData) {
	$scope.user = ShareData.get();
}]);

mdlApp.controller('settingsController', function($scope) {

});

mdlApp.controller('profileController', ['$scope', 'ShareData', '$http', function($scope, ShareData, $http) {
	$scope.user = ShareData.get();

 	$scope.submit = function() {
		var form = $("#formProfile").serialize();

		if($("#password1Profile").val() != $("#password2Profile").val())
			alert("Passwords don't match");

		else
		{
	        $http({ method : 'POST',
	                url : urlServer + 'functions.php',
	                data: form,
	                headers: {
	                  //'Authorization': 'Basic dGVzdDp0ZXN0',
	                  'Content-Type': 'application/x-www-form-urlencoded'
	       			}}).   
				
				success(function(data) {
					if(data == "true")
					{
						alert("Your profile has been updated.");
						ShareData.edit($scope.user);			
					}

					else
						alert("Error");
				}).

				error(function(data) {
					alert("Error");
				});
		}
	};
}]);

mdlApp.controller('newAccountController', ['$scope','$http', function($scope, $http) {
 	$scope.submit = function() {
		var form = $("#createAccountForm").serialize();

		if($("#password1New").val() != $("#password2New").val())
			alert("Passwords don't match");

		else
		{
	        $http({ method : 'POST',
	                url : urlServer + 'functions.php',
	                data: form,
	                headers: {
	                 // 'Authorization': 'Basic dGVzdDp0ZXN0',
	                  'Content-Type': 'application/x-www-form-urlencoded'
	       			}}).   
				
				success(function(data) {
					if(data == "true")
					{
						alert("Your Account has been created. You may login now.");
						window.location.href = "#/"; 		
					}

					else
						alert(data);
				}).

				error(function(data) {
					alert("Error exception.");
				});
		}
	};
}]);
