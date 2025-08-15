// This is Home page .
import { enableNotif } from './module/subscription.js';
import { checksub } from './module/subscription.js';
import { get_current_profile } from './module/user_profile.js';

document.addEventListener('DOMContentLoaded', async () => {

    //Register Service Worker to browser.
    navigator.serviceWorker.register("./service-worker.js");

    const SubBtn = document.getElementById('BtnSub');
    const UnsubBtn = document.getElementById('BtnUnsub');

    // Add function to Subscribe button.
    SubBtn.addEventListener('click', enableNotif);
    UnsubBtn.addEventListener();

    // Define subscribe variable for checksub function.
    const subscribe = await checksub();

    // Check subscribtion status from subscribe variable.
    if (subscribe == true) {
        SubBtn.style.display = "none";
        console.info('Subscribed');
    } else {
        SubBtn.style.display = "block";
        console.warn('Not subscribe to notification yet.');
    }


    // Assign value to element txUsername for display username.
    get_current_profile().then(profile => {
        document.getElementById('txUsername').innerText = profile.response[0].name;
    });

});



