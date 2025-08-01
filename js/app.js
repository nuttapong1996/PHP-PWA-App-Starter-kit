// This file MUST be in every page EXCEPT Log in page 
import { refreshAccessToken } from './tokenControl.js';
import { renewRefreshToken } from './tokenControl.js';

document.addEventListener('DOMContentLoaded', () => {

  // Refresh Access token and renew Refresh Token every 5 minnute if user still active (while using an Appplication).
  setInterval(() => { refreshAccessToken(); renewRefreshToken(); }, 5 * 60 * 1000);

});
