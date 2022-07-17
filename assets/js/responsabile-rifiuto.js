$(document).ready(function () {
    $('#refuseActivityModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var month = button.data('month');
        var year = button.data('year');

        document.getElementById('user-id').value = userId;
        document.getElementById('month').value = month;
        document.getElementById('year').value = year;
    });
    
    $('#disapproveVacationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var month = button.data('month');
        var year = button.data('year');

        document.getElementById('user-id').value = userId;
        document.getElementById('month').value = month;
        document.getElementById('year').value = year;
    });
});