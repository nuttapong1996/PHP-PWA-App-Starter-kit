import { getToken } from "./tokenControl.js";
import {renewRefreshToken} from "./tokenControl.js";
import {refreshAccessToken} from "./tokenControl.js";

// This is Login page 
document.addEventListener("DOMContentLoaded", async () => {

    const loginForm = document.getElementById("loginForm");

    // Check Access Token
    const validate_token = await getToken();
    if(validate_token)
    {
        window.location.href = "home";
        return;
    }else{
        document.body.style.display = "block";
        const renew =  await renewRefreshToken();
        refreshAccessToken();
    }
    // fetch check Access Token .
    // 1) If User have Access Token -> go to Home.
    // await fetch('auth/token', {
    //     method: 'GET',
    //     credentials: 'include',
    // })
    //     .then(ac => {
    //         if (ac.ok) {
    //             window.location.href = "home";
    //             return;
    //         }
    //         // 2) If Access Token expires -> create new Access Token IF user's Refresh Token still active or login to get the new one.
    //         if (ac.status === 401) {
    //             fetch('auth/refresh', {
    //                 method: 'POST',
    //                 credentials: 'include'
    //             })
    //                 .then(ref => {
    //                     if (ref.ok) {
    //                         window.location.href = "home";
    //                         return;
    //                     } else if (ref.status === 401) {
    //                         document.body.style.display = "block";
    //                         console.info("Refresh token is invalid or expired , please log in.");
    //                     } else {
    //                         console.warn("Unexpected error from /auth/refresh:", refreshRes.status);
    //                     }
    //                 })
    //                 .catch(err => {
    //                     console.error('Refresh error:', err);
    //                     document.body.style.display = "block";
    //                 })
    //         } else {
    //             console.warn("Unexpected response ",ac.status);
    //         }
    //     })
    //     .catch(err => {
    //         document.body.style.display = "block";
    //         console.error('Access token error:', err);
    //     });

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







