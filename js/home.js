import {enableNotif} from './subscription.js';
import {checksub} from './subscription.js';
import {get_current_profile} from './user_profile.js';

document.addEventListener('DOMContentLoaded',async ()=>{

    //ลงทะเบียน Service Worker กับ Browser
    navigator.serviceWorker.register("./service-worker.js");

    // ปุ่ม Subscribe
    const SubBtn = document.getElementById('BtnSub');
    // ปุ่ม Unsubscribe
    const UnsubBtn = document.getElementById('BtnUnsub');

    UnsubBtn.style.display="none"

    SubBtn.addEventListener('click', enableNotif);

    const subscribe = await checksub();

        if(subscribe !== true) {
              console.warn('not subscribe to notification yet.');
        }else{
            console.info('Subscribed');
        }

       get_current_profile().then(profile =>{
            document.getElementById('txUsername').innerText = profile.username;
       });

});



