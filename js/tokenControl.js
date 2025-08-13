export async function refreshAccessToken() {
    try {
        const ref = await fetch('auth/renew', {
            method: 'POST',
            credentials: 'include',
        });
        if (!ref.ok) return null;

        console.info('Access token refreshed!')

    } catch (error) {
        console.warn('No token found , please login')
        return null;
    }
}

export async function renewRefreshToken() {
    try {
        const response = await fetch('auth/renew', {
            method: 'POST',
            credentials: 'include',
        });

        if (!response.ok) return null;

        const data = await response.json();

        console.info('Token Renewed !');

        return data?.status || null;

    } catch (err_renew) {
        console.warn('No token found , please login')
        return null;
    }
}