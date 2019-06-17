$(function () {
    // Switch application state via Ajax when the current state icon is clicked
    $('.-fn-application .iconPublish[data-url]').bind('click', function () {
        var $this = $(this);
        var url = $this.attr('data-url');
        var requestedState = $this.hasClass("enabled") ? "disabled" : "enabled";;



        $.ajax({
            url: url,
            type: 'POST',
            data: { state: requestedState }
        }).done(function (response) {
            var isEnabled = response.newState === 'enabled'
            isEnabled ? $this.removeClass("disabled").addClass('enabled iconPublishActive') : $this.removeClass('enabled iconPublishActive').addClass('disabled');


        }).fail(function (jqXHR, textStatus, errorThrown) {
            Mapbender.error(errorThrown);
        });
        return false;
    });

    $('#listFilterApplications').on('click', '.iconRemove[data-url]', function () {
        var $el = $(this);
        Mapbender.Manager.confirmDelete($el, $el.attr('data-url'), {
            // @todo: bring your own translation string
            title: "mb.manager.components.popup.delete_element.title",
            // @todo: bring your own translation string
            cancel: "mb.manager.components.popup.delete_element.btn.cancel",
            // @todo: bring your own translation string
            confirm: "mb.manager.components.popup.delete_element.btn.ok"
        });
        return false;
    });


    $('.-fn-list-search').keypress(function (event) {
        var searchValue = $(event.currentTarget).val();
        $('.-fn-application').prop('hidden', false);
        $('.-fn-application').each(function (iterator, node) {
            var iTitle = $(node).find(".-fn-application-title").text();
            if (!(iTitle.match(new RegExp(".*" + searchValue + ".*", "i")))) {
                $(node).prop('hidden', true)
            }
        })
    })
});
