export async function getToken() {
    try {
        const res = await fetch('auth/token',{
            method: 'GET',
            credentials: 'include'
        });
        if(res.ok){
            const data = await res.json();
            return data;
        }else{
             throw new Error('HTTP Error:'+res.status);
        }
    } catch (error) {
        console.error('Unexpected response : ',error)
        return null;
    }
}


export async function refreshAccessToken() {
    await fetch('auth/refresh', {
        method: 'POST',
        credentials: 'include',
    })
        .then(res => {
            if (!res.ok) throw new Error('HTTP error ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.access_token) {
                console.log('Access token refreshed!');
            } else {
                console.error('Failed to refresh token:', data.error);
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            return null;
        });
}

export async function renewRefreshToken() {
    await fetch('auth/renew', {
        method: 'POST',
        credentials: 'include',
    })
        .then(renew => {
            if (renew.ok) {
                console.log('Refresh token renewed!');
            }
        })
        .catch(err_renew => {
            console.error('Fetch error:', err_renew);
        });
}