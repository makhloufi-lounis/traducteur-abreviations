$(document).ready(function() {
    $("table").tablecloth();

    // With customizations
    $("table").tablecloth({
        theme: "default",
        bordered: false,
        condensed: true,
        striped: true,
        sortable: true,
        clean: true,
        cleanElements: "th td",
        customClass: "my-table"
    });
    $('#user_form').bootstrapValidator({
        fields : {
            username : {
                validators : {
                    notEmpty : {
                        message : 'Précisez l\'username'
                    }
                }
            },
            email : {
                validators : {
                    notEmpty : {
                        message : 'Précisez l\'email'
                    }
                }
            },
            password : {
                validators : {
                    notEmpty : {
                        message : 'Précisez le mot de passe'
                    }
                }
            }

        },
        submitHandler : function(validator, form, submitButton) {
            validator.defaultSubmit();
        }
    });

    $("button.deleterow").click(function(e) {
        $that = $(this);
        bootbox.dialog({
            message : "Etes-vous sûr de supprimer&nbsp" + $that.attr('user-nom').bold() + "&nbsp?",
            title : "Confirmation",
            buttons : {
                confirm : {
                    label : "Supprimer",
                    className : "btn-danger",
                    callback : function() {
                        deleteUser($that);
                    }
                },
                cancel : {
                    label : "annuler",
                    className : "btn-default",
                }
            }
        });
    });
});

function deleteUser($button) {
    var $kill_row = $button.parent('td').parent('tr');
    $kill_row.addClass("danger");
    $kill_row.fadeOut(800, function() {
        $button.remove();
    });
    $.ajax({
        type : "DELETE",
        url : "/franchises_adm/ajax-aclmanager/ajaxDeleteUser/" + $button.attr("user-id").toString(),
        success : function(data) {
            if (data.success == true) {
                return;
            } else {
                alert(data.message, "Attention", "error");
            }
        },
        dataType : "json"
    });
}