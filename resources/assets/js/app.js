
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Laravel = { csrfToken: $('meta[name=csrf-token]').attr('content') };
require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('org-events', require('./components/organizer/Events.vue'));
Vue.component('category-editor', require('./components/organizer/CategoryEditor.vue'));
Vue.component('challenge-editor', require('./components/organizer/ChallengeEditor.vue'));
Vue.component('users-list', require('./components/organizer/Users.vue'));

Vue.component('player-events', require('./components/player/Events.vue'));
Vue.component('category-list', require('./components/player/Categories.vue'));
Vue.component('questions-list', require('./components/player/Questions.vue'));

Vue.component('leader-board', require('./components/LeaderBoard.vue'));

// utility components
Vue.component('countdown-timer', require('./components/utility/CountdownTimer.vue'));

const app = new Vue({
    el: '#app',
});
