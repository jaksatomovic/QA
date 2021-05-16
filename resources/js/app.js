/**
 * Load all project's JavaScript dependencies
 */
require('./bootstrap');

/**
 * Registering Vue components
 */
import Main from './mixins/main';
Vue.mixin(Main);

import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

Vue.component('answers-list', require('./components/Answers/AnswersList.vue').default);

Vue.component('base-avatar', require('./components/Common/BaseAvatar.vue').default);
Vue.component('base-loading', require('./components/Common/BaseLoading.vue').default);
Vue.component('base-modal', require('./components/Common/BaseModal.vue').default);
Vue.component('base-toasts', require('./components/Common/BaseToasts.vue').default);
Vue.component('report-modal', require('./components/Common/ReportModal.vue').default);
Vue.component('share-modal', require('./components/Common/ShareModal.vue').default);

Vue.component('the-navbar', require('./components/Layout/TheNavbar.vue').default);
Vue.component('the-sidebar-main', require('./components/Layout/TheSidebarMain.vue').default);
Vue.component('the-sidebar-second', require('./components/Layout/TheSidebarSecond.vue').default);

Vue.component('notifications-list', require('./components/Notifications/NotificationsList.vue').default);

Vue.component('questions-list', require('./components/Questions/QuestionsList.vue').default);
Vue.component('question-item', require('./components/Questions/QuestionItem.vue').default);
Vue.component('question-ask-modal', require('./components/Questions/QuestionAsk.vue').default);

Vue.component('search-list', require('./components/Search/SearchList.vue').default);

Vue.component('topics-list', require('./components/Topics/TopicsList.vue').default);
Vue.component('topic-item', require('./components/Topics/TopicItem.vue').default);
Vue.component('topic-sidebar', require('./components/Topics/TopicSidebar.vue').default);
Vue.component('space-create-modal', require('./components/Topics/SpaceCreate.vue').default);

Vue.component('users-list', require('./components/Users/UsersList.vue').default);
Vue.component('user-item', require('./components/Users/UserItem.vue').default);
Vue.component('login-modal', require('./components/Users/UserLoginModal.vue').default);
Vue.component('register-modal', require('./components/Users/UserRegisterModal.vue').default);
Vue.component('user-sidebar', require('./components/Users/UserSidebar.vue').default);

Vue.component('the-admin-sidebar', require('./components/Admin/Layout/TheAdminSidebar.vue').default);
Vue.component('the-admin-dashboard', require('./components/Admin/TheAdminDashboard.vue').default);
Vue.component('the-admin-reports', require('./components/Admin/Reports/ReportsList.vue').default);
Vue.component('the-admin-topics', require('./components/Admin/Topics/TopicsList.vue').default);
Vue.component('the-admin-users', require('./components/Admin/Users/UsersList.vue').default);
Vue.component('the-admin-questions', require('./components/Admin/Questions/QuestionsList.vue').default);
Vue.component('the-admin-answers', require('./components/Admin/Answers/AnswersList.vue').default);
Vue.component('the-admin-pages', require('./components/Admin/Pages/PagesList.vue').default);
Vue.component('check-for-updates', require('./components/Admin/Common/CheckForUpdates.vue').default);

const app = new Vue({
    el: '#app'
});
