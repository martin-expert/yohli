require('./bootstrap');

window.Vue = require('vue');

import EchoLibrary from 'laravel-echo'

window.Moment = require('moment-timezone');

window.Pusher = require('pusher-js');

import VueChatScroll from 'vue-chat-scroll'

window.VueChatScroll = VueChatScroll;

let isProduction = process.env.MIX_WS_CONNECT_PRODUCTION === 'true';

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: "0b580d9d86bcbaeb4ec7",
//     cluster: 'us3',
//     forceTLS: true,
// });

window.axios.defaults.headers.common = {
    // 'X-CSRF-TOKEN': window.Laravel.csrfToken, <-- Comment it out (if you are extending layouts.app file, you won't require this.)
    'X-Requested-With': 'XMLHttpRequest'
};

window.Echo = new EchoLibrary({
    broadcaster: 'pusher',
    key: '0b580d9d86bcbaeb4ec7',
    cluster: 'us3',
    encrypted : true
});

Echo.channel('chat-room.1')
    .listen('ChatMessageWasReceived', (e) => {
        console.log(e);
    });
