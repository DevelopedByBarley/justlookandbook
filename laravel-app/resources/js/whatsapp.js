document.addEventListener('DOMContentLoaded', () => {
    const widget = document.getElementById('wa-widget');
    if (!widget) return;

    const phone = widget.dataset.phone ?? '';

    window.sendWA = function () {
        const msg = document.getElementById('wa-message').value.trim();
        const url = `https://wa.me/${phone}` + (msg ? `?text=${encodeURIComponent(msg)}` : '');
        window.open(url, '_blank');
    };

    window.openWAWidget = function (message = '', accepted = false) {
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('open-wa', {
                detail: { message, accepted }
            }));
        }, 0);
    };

 window.openMessengerWidget = function (event) {
    if (event) event.preventDefault();

    const pageId = "1132993199903672";
    const username = "justlookandbook";
    const fallbackUrl = `https://www.messenger.com/t/${username}`;

    const ua = navigator.userAgent || navigator.vendor || window.opera;

    if (/android/i.test(ua)) {
        // Androidon az app a "user" hostot ismeri fel (numerikus ID-vel), nem a "user-thread"-et.
        window.location.href = `fb-messenger://user/${pageId}`;
        // Csak akkor essünk webre, ha az app NEM vette át a fókuszt 1.5 mp alatt.
        setTimeout(function() {
            if (!document.hidden) {
                window.location.href = `https://m.me/${username}`;
            }
        }, 1500);
    }
    else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
        window.location.href = `fb-messenger://user-thread/${pageId}`;
        setTimeout(function() {
            window.open(fallbackUrl, '_blank');
        }, 1500);
    }
    else {
        window.open(fallbackUrl, '_blank');
    }
};
});
