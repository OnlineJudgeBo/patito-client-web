(function () {
    'use strict';

    function cookie(name) {
        const item = document.cookie.split('; ').find((value) => value.startsWith(`${name}=`));
        return item ? decodeURIComponent(item.slice(name.length + 1)) : '';
    }

    function setCookie(name, value) {
        document.cookie = `${name}=${encodeURIComponent(value)}; path=/`;
    }

    // Shared across every fetch(): if several requests 401 at the same time we only
    // want to hit refresh-token.php once, not once per request.
    let refreshPromise = null;

    function refreshAccessToken() {
        if (refreshPromise) return refreshPromise;

        refreshPromise = (async () => {
            const refreshToken = cookie('refreshToken') || localStorage.getItem('refreshToken') || '';
            if (!refreshToken) return null;
            try {
                const response = await fetch('refresh-token.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ refreshToken })
                });
                if (!response.ok) return null;
                const data = await response.json();
                if (!data || !data.accessToken) return null;
                setCookie('accessToken', data.accessToken);
                localStorage.setItem('accessToken', data.accessToken);
                return data.accessToken;
            } catch (_) {
                return null;
            }
        })().finally(() => {
            refreshPromise = null;
        });

        return refreshPromise;
    }

    window.PatitoAuth = { refreshAccessToken, cookie };
}());
