document.addEventListener("DOMContentLoaded", () => {
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
                // console.log("Login successful:", data);

                // เก็บ token ใน sessionStorage หรือ cookie (ถ้าใช้ JWT)
                localStorage.setItem("access_token", data.access_token);
                // localStorage.setItem("refresh_token", data.refresh_token);

                // redirect ถ้าจำเป็น
                // window.location.href = "home";
                fetch("home", {
                    headers: {
                        Authorization: "Bearer " + data.access_token
                    }
                })
                    .then(res => {
                        if (!res.ok) throw new Error("Token invalid or expired");
                        return res.text();
                    })
                    .then(html => {
                        document.body.innerHTML = html;
                    })
                    .catch(err => {
                        console.error("Load home failed:", err);
                        alert("Token หมดอายุ กรุณา login ใหม่");
                        window.location.href = "/login";
                    });

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
