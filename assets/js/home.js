// This is Home page .
import { enableNotif } from './module/subscription.js';
import { checksub } from './module/subscription.js';
import { get_current_profile } from './module/user_profile.js';

document.addEventListener('DOMContentLoaded', async () => {

    //Register Service Worker to browser.
    navigator.serviceWorker.register("./service-worker.js");

    // Subscription Handdling 
    const SubBtn = document.getElementById('BtnSub');
    const UnsubBtn = document.getElementById('BtnUnsub');
    const subStatus = document.getElementById('subStatus');

    SubBtn.addEventListener('click', enableNotif);

    const subscribe = await checksub();

    if (subscribe == true) {
        SubBtn.style.display = "none";
        subStatus.style.display = "block";
        subStatus.innerHTML ="<i class='bi bi-bell'></i> Subscribed"
    } else {
        SubBtn.style.display = "block";
        console.warn('Not subscribe to notification yet.');
    }

    // Assign value to element txUsername for display username.
    get_current_profile().then(profile => {
        document.getElementById('txUsername').innerText = profile.response[0].name;
    });

});



