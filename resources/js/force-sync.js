$(document).ready(function () {
     $.ajaxSetup({
         headers: {
             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
         },
     });

     function checkLastSync() {
         $.ajax({
             url: "/api/last-sync",
             type: "GET",
             success: function (response) {
                 const data = response.data;
                 if (data.status == 3) {
                     $(".last-sync-error-tag").removeClass("d-none");
                     $(".last-sync-date").text("-");
                     $(".last-sync-success-tag").addClass("d-none");
                     $(".last-sync-pending-tag").addClass("d-none");
                 } else if (data.status == 2) {
                     $(".last-sync-success-tag").removeClass("d-none");
                     $(".last-sync-date").text(data.last_synced_at);
                     $(".last-sync-error-tag").addClass("d-none");
                     $(".last-sync-pending-tag").addClass("d-none");
                 } else {
                     $(".last-sync-pending-tag").removeClass("d-none");
                     $(".last-sync-date").text("-");
                     $(".last-sync-error-tag").addClass("d-none");
                     $(".last-sync-success-tag").addClass("d-none");
                 }
                 $(".upcomming-sync-date").text(data.upcoming_sync);
             },
         });
     }

     checkLastSync();
     setInterval(checkLastSync, 10000);


    $("#force-sync-btn").click(function () {
        $.ajax({
            url: "/api/force-sync",
            type: "POST",
            success: function (response) {
                console.log(response);
                $(".last-sync-pending-tag").removeClass("d-none");
                $(".last-sync-date").text("-");
                $(".last-sync-error-tag").addClass("d-none");
                $(".last-sync-success-tag").addClass("d-none");
            },
        });
    });
});
