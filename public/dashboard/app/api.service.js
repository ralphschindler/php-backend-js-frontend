(function () {
  'use strict';

  angular.module('app')
    .factory('ApiService', ApiService);

  ApiService.$inject = ['$q', '$http'];

  function ApiService ($q, $http) {

    var apiRoot = $http.get('/');

    return {

      getReminders: function () {
        var deferred = $q.defer();
        apiRoot.success(function(data) {
          var uri = data._links['ra:reminders']['href'];
          return $http.get(uri).success(function (data) {
            deferred.resolve(data._embedded['reminders']);
          });
        });
        return deferred.promise;
      },

      store: function (reminder) {
        var uri = reminder._links['self']['href'];

        var deferred = $q.defer();

        $http.patch(uri, {text: reminder.text}).success(function (data) {
          deferred.resolve(data);
        });

        return deferred.promise;
      },

      getLocations: function () {
        var deferred = $q.defer();
        apiRoot.success(function(data) {
          var uri = data._links['ra:locations']['href'];
          return $http.get(uri).success(function (data) {
            deferred.resolve(data._embedded['locations']);
          });
        });
        return deferred.promise;
      }
    };

  }

})();