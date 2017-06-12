(function ($) {
    $(document).ready(function () {
        var flyoutToggleBtn = document.querySelector('.top-nav-bar-toggle');
        flyoutToggleBtn.addEventListener('click', function (event) {
            event.preventDefault();
            var flyout = document.querySelector('.top-nav-bar-flyout');
            flyout.classList.toggle('open');
        });
    });
})(jQuery)