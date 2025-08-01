document.addEventListener('DOMContentLoaded', () => {
  // Refresh access token ทุก 14 นาที หรือ respone status 401
  setInterval(() => {
    refreshAccessToken();
  }, 14 * 60 * 1000);
  // },1000);
});

async function refreshAccessToken() {
  // fetch('api/user/refresh.php', {
  fetch('auth/refresh', {
    method: 'POST',
    credentials: 'include', // สำคัญสำหรับ cookie
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
        // ตัวอย่าง: redirect ไปหน้า login
        window.location.href = './';
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
      // ตัวอย่าง: redirect ไปหน้า login
      window.location.href = './';
    });

   await fetch('auth/renew',{
      method: 'POST',
      credentials: 'include',
   })
   .then(renew =>{
      console.log('Refresg token renewed!');
   })
   .catch(err_renew => {
      console.error('Fetch error:', err_renew);
   });

}
