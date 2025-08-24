export async function refreshAccessToken() {
    let result = null;
    try {
        const ref = await fetch('auth/refresh', {
            method: 'POST',
            credentials: 'include',
        });

        if (!ref.ok) {
            throw new Error('No token found , please login.');
            result = null;
        }

        console.info('Access token refreshed!')

    } catch (error) {
        console.warn('No token found , please login')
        result = null;
    }
}

export async function renewRefreshToken() {

    let result = null

    try {
        const response = await fetch('auth/renew', {
            method: 'POST',
            credentials: 'include',
        });

        if (!response.ok) {
            throw new Error('Please Login.');
            result = null;
        }
        const data = await response.json();

        if (data.code === 200) {
            console.info('Token Renewed !');
            result = data.code;
        }

    } catch (err_renew) {
        console.warn('No token found , please login')
        result = null;
    }

    return result;
}