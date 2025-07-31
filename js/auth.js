document.addEventListener("DOMContentLoaded", async () => {

    // 1) ถ้า access token ยังดี -> เข้า home เลย
    await fetch('auth/token', {
        method: 'GET',
        credentials: 'include',
    })
        .then(ac => {
            if (ac.ok) {
                window.location.href = "home";
                return;
            }
            // 2) ถ้า access หมดอายุ -> ค่อยลอง refresh
            if (ac.status === 401) {
                fetch('auth/refresh', {
                    method: 'POST',
                    credentials: 'include'
                })
                    .then(ref => {
                        if (ref.ok) {
                            window.location.href = "home";
                            return;
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                    })
            }
        })

    //fetch Login data
    const loginForm = document.getElementById("loginForm");

    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const username = document.getElementById("userName").value.trim();
        const password = document.getElementById("userPass").value;

        try {
            // const res = await fetch("api/user/login.php", {
            const res = await fetch("auth/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                })
            });

            const data = await res.json();

            if (data.status === "success") {
                window.location.href = "home";
            } else {
                window.location.href = "login";
                alert("Login failed");
            }
        } catch (err) {
            console.error("Login error:", err);
        }
    });
});


