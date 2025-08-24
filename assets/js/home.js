import fetchWithAuth from "./module/api.js";

document.addEventListener("DOMContentLoaded", async () => {
    await fetchWithAuth("home");
});
