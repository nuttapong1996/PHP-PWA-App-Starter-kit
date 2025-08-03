// This file MUST be in every page EXCEPT Log in page 
import { refreshAccessToken } from './tokenControl.js';
import { renewRefreshToken } from './tokenControl.js';

document.addEventListener('DOMContentLoaded', async () => {
  // If user still active (while using an Appplication) then 
  // Refresh Access token every 5 minnute 
  setInterval(async () => {
    refreshAccessToken();
  }, 5 * 60 * 1000);
  // Renew Refresh Token every 30 minnute 
    setInterval(async () => {
    await renewRefreshToken();
  }, 30 * 60 * 1000);
});
