document.addEventListener("DOMContentLoaded", () => {

    // fetch check access token
    fetch('api/user/refresh.php', {
        method: 'POST',
        credentials: 'include',
    })
        .then(res => {
            if (!res.ok) throw new Error('HTTP error ' + res.status);
            return res.json();
        })
        .then(data => {
            if (data.access_token) {
                window.location.href = "home";
            }
        }).catch(err => {
            console.error('Fetch error:', err);
        })


    //fetch Login data
    const loginForm = document.getElementById("loginForm");

    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const username = document.getElementById("userName").value.trim();
        const password = document.getElementById("userPass").value;

        try {
            const res = await fetch("api/user/login.php", {
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
                // redirect ถ้าจำเป็น
                window.location.href = "home";
            } else {
                window.location.href = "./";
                alert("Login failed");
                // console.error("Login failed:", data);
            }
        } catch (err) {
            console.error("Login error:", err);
        }
    });
});


