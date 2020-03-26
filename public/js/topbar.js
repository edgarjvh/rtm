$(document).ready(function () {
    $(document).on('change', '#cbox-toggle-menu', function (e) {
        let cbox = $(this);
        let sidebarContainer = $(document).find('.sidebar-container');
        let label = $(document).find('label.topbar-toggle-btn');
        let sidemenuModal = $(document).find('.sidemenu-modal');

        if (cbox.is(':checked')){
            sidemenuModal.fadeIn(300);
            sidemenuModal.addClass('shown');
            sidebarContainer.css('left', '0');
            label.addClass('shown');
            $(document).find('#app').css('overflow', 'hidden');
        }else{
            sidemenuModal.fadeOut(300);
            sidemenuModal.removeClass('shown');
            sidebarContainer.css('left', '-100%');
            label.removeClass('shown');
            $(document).find('#app').css('overflow', 'unset');
        }
    });

    $( window ).resize(function() {
        if ($( window ).width() > 768){
            $(document).find('.sidemenu-modal').hide();
        }else{
            if ($(document).find('.sidemenu-modal').hasClass('shown')){
                $(document).find('.sidemenu-modal').show();
            }
        }
    });

});