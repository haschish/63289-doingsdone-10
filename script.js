'use strict';

var $checkbox = document.getElementsByClassName('show_completed');

if ($checkbox.length) {
  $checkbox[0].addEventListener('change', function (event) {
    var is_checked = +event.target.checked;

    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set('show_completed', is_checked);

    window.location = 'index.php?' + searchParams.toString();
  });
}


var onTaskCheckboxChange = function(evt) {
  var checkbox = evt.target;
  var searchParams = new URLSearchParams(window.location.search);
  searchParams.set('done', checkbox.checked);
  searchParams.set('id', checkbox.dataset.taskId);

  window.location = 'task-done.php?' + searchParams.toString();
};

var checkboxesTasks = document.querySelectorAll('.tasks .checkbox__input');
checkboxesTasks.forEach(function(item) {
  item.addEventListener('change', onTaskCheckboxChange);
});

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});
