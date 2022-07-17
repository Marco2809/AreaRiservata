$(document).ready(function () {
    $('#refuseActivityModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var activityId = button.data('activity-id');

        document.getElementById('activity-id').value = activityId;
    });
    
    $('#disapproveVacationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var activityId = button.data('activity-id');

        document.getElementById('activity-id').value = activityId;
    });
});