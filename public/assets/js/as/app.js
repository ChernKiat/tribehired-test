var as = {};

as.toggleSidebar = function () {
    $("#sidebar-nav").toggleClass('expanded');
};

as.hideNotifications = function () {
    $(".alert-notification").slideUp(600, function () {
        $(this).remove();
    })
};

as.updateSidebarSize = function () {
    if (window.innerWidth >= 992 && window.innerWidth <= 1199) {
        window.document.body.classList.add("sidebar-collapsed");
    } else {
        window.document.body.classList.remove("sidebar-collapsed");
    }
};

as.init = function () {

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    //$("#sidebar-toggle").click(as.toggleSidebar);
    $('[data-target="#sidebar-nav"]').click(as.toggleSidebar);

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $(".alert-notification .close").click(as.hideNotifications);

    setTimeout(as.hideNotifications, 3500);

    $("a[data-toggle=loader], button[data-toggle=loader]").click(function () {
        if ($(this).parents('form').valid()) {
            as.btn.loading($(this), $(this).data('loading-text'));
            $(this).parents('form').submit();
        }
    });

    // $(window).resize(as.updateSidebarSize);
};

// as.updateSidebarSize();

$(document).ready(as.init);
