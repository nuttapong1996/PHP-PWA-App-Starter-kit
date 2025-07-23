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
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ 
                    username : username,
                    password :password
                })
            });

            const data = await res.json();

            if (data.status === "success") {
                console.log("Login successful:", data);

                // เก็บ token ใน sessionStorage หรือ cookie (ถ้าใช้ JWT)
                // sessionStorage.setItem("access_token", data.access_token);

                // redirect ถ้าจำเป็น
                window.location.href = "?p=home";
            } else {
                window.location.href = "?p=login";
                alert("Login failed");
                // console.error("Login failed:", data);
            }
        } catch (err) {
            console.error("Login error:", err);
        }
    });
});
