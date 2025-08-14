// This is Home page .
import { enableNotif } from './module/subscription.js';
import { checksub } from './module/subscription.js';
import { get_current_profile } from './module/user_profile.js';

document.addEventListener('DOMContentLoaded', async () => {

    //Register Service Worker to browser.
    navigator.serviceWorker.register("./service-worker.js");

    // Subscribe button.
    const SubBtn = document.getElementById('BtnSub');
    // Unsubscribe button.
    const UnsubBtn = document.getElementById('BtnUnsub');

    // Hide Unsubscribe button on start.
    UnsubBtn.style.display = "none"

    //// Add function to Subscribe button.
    // SubBtn.addEventListener('click', enableNotif);

    //// Define subscribe variable for checksub function.
    // const subscribe = await checksub();

    //// Check subscribtion status from subscribe variable.
    // if (subscribe !== true) {
    //     console.warn('not subscribe to notification yet.');
    // } else {
    //     console.info('Subscribed');
    // }

    // Assign value to element txUsername for display username.
    get_current_profile().then(profile => {
        document.getElementById('txUsername').innerText = profile.response[0].name;
    });

});



