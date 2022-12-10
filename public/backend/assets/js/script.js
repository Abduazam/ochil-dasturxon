$(document).ready(function () {
    $("#open-image").click(function () {
        $("#modal-default").find('.modal-body').html('');
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'payment/' + id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function (data) {
                let image = document.createElement("img");
                image.src = data.image;
                $("#modal-default .modal-body").html(image);
            }
        })
    })

    $("#open-accept").click(function () {
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'payment/' + id + '/edit',
            type: 'GET',
            data: {
                "id": id
            },
            success:function (data) {
                let input = "<div class='col-md-6'>" +
                    "<label for='minus_sum'>To'lov summasi</label>" +
                    "<input type='number' class='form-control form-control' id='minus_sum' name='minus_sum' required" +
                    "</div>";
                $("#modal-default2 .modal-body .form-group").html(input);
                $("#modal-default2 .modal-header .modal-title").html("To'langan qiymatni kiritng");
                $("#modal-default2 .modal-footer #accept-info").attr('data-id', data.id);
            }
        })
    })

    $("#accept-info").click(function () {
        const id = $(this).attr('data-id');
        const sum = $("#minus_sum").val();
        $.ajax({
            url: 'payment/accept/' + id + '/' + sum,
            type: 'GET',
            success:function (data) {
                window.location.reload();
            }
        })
    })
});
