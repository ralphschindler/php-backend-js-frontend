(function () {
  'use strict';

  angular.module('app.location')
    .controller('Location', Location);

  Location.$inject = ['ApiService'];

  function Location(ApiService) {
    var vm = this;

    vm.locations = [];

    activate();

    function activate() {
      ApiService.getLocations().then(function (data) {
        vm.locations = data;
      });
    }
  }

})();
