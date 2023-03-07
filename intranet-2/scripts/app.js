'use strict';
/**
 * @ngdoc overview
 * @name facturacionApp
 * @description
 * # IntraCIELD
 *
 * Main module of the application.
 */
var app = angular.module('IntranetCIELD', [
  'ngAnimate',
  'ngCookies',
  //    'ngResource',
  'ngRoute',
  'ngSanitize',
  //    'ngTouch',
  'ui.router',
  'oc.lazyLoad',
  'angular-loading-bar',
  'angucomplete-alt',
  'angular-storage',
  'mdo-angular-cryptography',
  'ui.utils',
  'textAngular',
  'ui.rCalendar',
  'datetimepicker',
  'mwl.calendar',
  'moment-picker',
  'ngMaterial',
  'material.components.eventCalendar',
  'base64',
  'ng-file-model',
  'ngImageCompress',
  'angular-bind-html-compile',
  'chart.js',
  'ngBootstrap',
  'angular-flot',
  'btorfs.multiselect',
  'validator',
  'validator.rules',
  'builder',
  'builder.components',
  'as.sortable',
  'ja.qr',
  'ngHandsontable',
  // 'indexedDB', 
  'ngFileUpload',
  'signature'
  //    'angular.filter'
]);
//app.config(function() {
//  angular.lowercase = angular.$$lowercase;  
//});

//app.config(['$locationProvider', function($locationProvider) {
//  $locationProvider.hashPrefix('');
//}]);


app.run([
  "$rootScope", "$state",
  function ($rootScope, $state) {
    var lastDigestRun = new Date();
    $rootScope.$watch(function detectIdle() {
      var now = new Date();
      if (now - lastDigestRun > 10 * 30 * 60 * 4500) {
        console.log(new Date());
        // logout here, like delete cookie, navigate to login ...removeSesionAdm
        if (localStorage.getItem("UserData_Polivalente")) {
          localStorage.removeItem("UserData_Polivalente");
          $state.go("login");
        }
      }
      lastDigestRun = now;
    });
  }
]);

app.run(['$rootScope', '$location', '$routeParams', function ($rootScope, $location, $routeParams) {
  $rootScope.$on('$stateChangeStart',
    function (event, toState, toParams, fromState, fromParams) {
      $rootScope.currentState = toState.name;
    });
}]);

app.run(['$window', '$rootScope', function ($window, $rootScope) {
  $rootScope.online = navigator.onLine;
  $window.addEventListener("offline", function () {
    $rootScope.$apply(function () {
      $rootScope.online = false;
    });
  }, false);

  $window.addEventListener("online", function () {
    $rootScope.$apply(function () {
      $rootScope.online = true;
    });
  }, false);
}]);