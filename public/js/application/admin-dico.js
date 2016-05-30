$(document).ready(function() {
    $('.js-btn-danger').on( "click", function(event) {
        var idIteme = $(this).attr( 'id-iteme' );
        var url = $(this).attr( 'href' );
        alert(idIteme);
        event.preventDefault();
        bootbox.dialog({
            message : "Etes-vous sûr de supprimer cet iteme",
            title : "Confirmation",
            buttons : {
                confirm : {
                    label : "Supprimer",
                    className : "btn-danger",
                    callback : function() {
                        deleteIteme(url, idIteme);
                    }
                },
                cancel : {
                    label : "annuler",
                    className : "btn-default"
                }
            }
        });

    });
});
function deleteIteme(url, idIteme) {
    $.ajax({
        url : url,
        type : "POST",
        data: {id: idIteme, del: 'Yes'},
        dataType:   'json',
        success : function(data) {
            if (data.status == 'success') {
                location.reload();
            } else {
                alert(data.message, "Attention", "error");
            }
        }
    });
}
