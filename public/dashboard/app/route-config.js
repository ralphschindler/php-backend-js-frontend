(function () {
  angular
    .module('app')
    .config(config);

  config.$inject = ['$routeProvider'];

  function config($routeProvider) {
    $routeProvider
      .when('/reminders', {
        templateUrl: 'app/reminder/reminder.html',
        controller: 'Reminder',
        controllerAs: 'vm'
      })
      .when('/locations', {
        templateUrl: 'app/location/location.html',
        controller: 'Location',
        controllerAs: 'vm'
      })
      .otherwise({
        redirectTo: '/reminders'
      });
  }
})();