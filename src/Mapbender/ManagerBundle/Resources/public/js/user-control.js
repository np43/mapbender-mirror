$(function(){
    var popup;

    $(".checkbox").on("change", function(){
      $("#selectedUsersGroups").text(($(".tableUserGroups").find(".iconCheckboxActive").length));
    });
    var showConfirmationDialog = function(){
        var $this  = $(this);
        var subtitle = $this.attr("id") === "#listFilterGroups" ? Mapbender.trans("fom.user.dialog.group") :  Mapbender.trans("fom.user.dialog.user")  ;
        var content = $this.attr("title");


        if(popup){
            popup = popup.destroy();
        }

        popup = new Mapbender.Popup2({
            title: Mapbender.trans("fom.user.dialog.title"),
            subtitle: subtitle,
            closeOnOutsideClick: true,
            content: [content + "?"],
            buttons: {
                "cancel": {
                    label: Mapbender.trans("fom.user.dialog.cancel"),
                    cssClass: "btn btn-critical float-right",
                    callback:  this.close
                },
                "delete": {
                    label: Mapbender.trans("fom.user.dialog.delete"),
                    cssClass: "btn btn-success float-right",
                    callback: function() {
                        $.ajax({
                            url: $this.data("url"),
                            data : {"id": $this.data("id")},
                            type: "POST"

                        }).done(window.location.reload);
                    }
                }
            }
        });
        return false;

    };
    // Delete group/user via Ajax
    $("#listFilterGroups #listFilterUsers").on("click", ".-fn-delete",showConfirmationDialog.bind(this) );



});