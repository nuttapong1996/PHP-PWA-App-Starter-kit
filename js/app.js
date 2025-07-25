document.addEventListener('DOMContentLoaded', () => {
  refreshAccessToken();
});

function refreshAccessToken() {
  fetch('api/auth/refresh.php', {
    method: 'POST',
    credentials: 'include', // สำคัญสำหรับ cookie
  })
    .then(res => {
      if (!res.ok) throw new Error('HTTP error ' + res.status);
      return res.json();
    })
    .then(data => {
      if (data.access_token) {
        localStorage.setItem('access_token', data.access_token);
        console.log('Access token refreshed!');
      } else {
        console.error('Failed to refresh token:', data.error);
        // ตัวอย่าง: redirect ไปหน้า login
        // window.location.href = '/login.html';
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
      // ตัวอย่าง: redirect ไปหน้า login
      // window.location.href = '/login.html';
    });
}
