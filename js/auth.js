// This is Login page 
document.addEventListener("DOMContentLoaded", async () => {

    // fetch check Access Token .
    // 1) If User have Access Token -> go to Home.
    await fetch('auth/token', {
        method: 'GET',
        credentials: 'include',
    })
        .then(ac => {
            if (ac.ok) {
                window.location.href = "home";
                return;
            }
            // 2) If Access Token expires -> create new Access Token IF user's Refresh Token still active or login to get the new one.
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

    // fetch Login data.
    const loginForm = document.getElementById("loginForm");

    // On submit Login form 
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const username = document.getElementById("userName").value.trim();
        const password = document.getElementById("userPass").value;

        try {
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


