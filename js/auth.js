// This ONLY belong to login page.
import { renewRefreshToken } from "./tokenControl.js";

document.addEventListener("DOMContentLoaded", async () => {

    const loginForm = document.getElementById("loginForm");

    // Check Access Token
    const validate_token = await renewRefreshToken();

    if (validate_token ==='success' ) {
        window.location.href = "home";
        return;
    } 
    else {
        document.body.style.display = "block";
    }

    // Login
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
            alert("Network error, please try again.");
        }
    });
});







