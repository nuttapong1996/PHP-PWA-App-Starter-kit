// This file MUST be in every page EXCEPT Log in page 
import { refreshAccessToken } from './module/tokenControl.js';
import { renewRefreshToken } from './module/tokenControl.js';
import { get_current_profile } from './module/user_profile.js';
import { enableNotif } from './module/subscription.js';
import { checksub } from './module/subscription.js';

document.addEventListener('DOMContentLoaded', async () => {

  //Register Service Worker to browser//
  navigator.serviceWorker.register("./service-worker.js");

  const txUsername = document.getElementById('txUsername');
  const SubBtn = document.getElementById('BtnSub');
  const subStatus = document.getElementById('subStatus');

  // Check Access Token and renew Refresh Token //

  setInterval(async () => { // If user still active (while using an Appplication) then Refresh Access token every 5 minnute .
    refreshAccessToken();
  }, 5 * 60 * 1000);

  setInterval(async () => {   // Renew Refresh Token every 30 minnute 
    await renewRefreshToken();
  }, 30 * 60 * 1000);

  get_current_profile().then(profile => { // Assign value to element txUsername for display username.
    if (txUsername) {
      txUsername.innerText = profile.response[0].name+'\n Empcode : '+profile.response[0].user_code;
    }
  });



  // Check Subscription status. //
  const subscribe = await checksub();

  SubBtn.addEventListener('click', enableNotif); //Assign event to Subscription Button.

  if (subscribe == true) {
    SubBtn.style.display = "none";
    subStatus.style.display = "block";
    subStatus.innerHTML = "<i class='bi bi-bell'></i> Subscribed"
  } else {
    SubBtn.style.display = "block";
    console.warn('Not subscribe to notification yet.');
  }

});
