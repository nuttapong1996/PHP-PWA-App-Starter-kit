export function get_current_profile() {

   return fetch('profile', {
        method: 'GET',
        credentials: 'include',
    })
        .then(res => {
            if (!res.ok) throw new Error('HTTP error ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.status === 'success') {
                return data;
            } else {
                alert('กรุณา login ใหม่');
                return null;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });
}

export function get_profile_by_id(usercode) {

   return fetch(`/profile/${usercode}`, {
        method: 'GET',
        credentials: 'include',
        headers:{
            'Accept': 'application/json'
        }
    })
        .then(res => {
            if (!res.ok) throw new Error('HTTP error ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.status === 'success') {
                console.log(data);
                return data.data;
            } else {
                alert('กรุณา login ใหม่');
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
        });
}