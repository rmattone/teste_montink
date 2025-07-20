$(document).ready(function () {
    const $sidebar = $('#sidebar');
    const $content = $('#content-area');
    const $backdrop = $('.sidebar-backdrop');
    const SIDEBAR_KEY = 'sidebar_state';

    function updateSidebarState() {
        const isMobile = window.innerWidth < 768;
        const savedState = localStorage.getItem(SIDEBAR_KEY);

        if (isMobile) {
            $sidebar.addClass('hide');
            $content.addClass('expanded');
        } else {
            if (savedState === 'hidden') {
                $sidebar.addClass('hide');
                $content.addClass('expanded');
            } else {
                $sidebar.removeClass('hide');
                $content.removeClass('expanded');
            }
        }
    }

    updateSidebarState();

    $(window).on('resize', updateSidebarState);

    $('#sidebar-toggle').on('click', function () {
        const isMobile = window.innerWidth < 768;
        const isHidden = $sidebar.hasClass('hide');

        $sidebar.toggleClass('hide');

        if (isMobile) {
            $backdrop.toggleClass('show');
        } else {
            $content.toggleClass('expanded');

            localStorage.setItem(SIDEBAR_KEY, isHidden ? 'visible' : 'hidden');
        }
    });

    $backdrop.on('click', function () {
        $sidebar.addClass('hide');
        $content.addClass('expanded');
        $backdrop.removeClass('show');

    });
});
