(function () {
  'use strict';

  angular.module('app.reminder')
    .controller('Reminder', Reminder);

  Reminder.$inject = ['ApiService'];

  function Reminder(ApiService) {
    var vm = this;

    vm.reminders = [];
    vm.updateText = updateText;

    activate();

    function activate() {
      ApiService.getReminders().then(function (data) {
        vm.reminders = data;
      });
    }

    function updateText(text, index) {
      vm.reminders[index]['text'] = text;
      ApiService.store(vm.reminders[index]).then(function (data) {

      });
    }
  }

})();
