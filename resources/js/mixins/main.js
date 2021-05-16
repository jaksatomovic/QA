export default {
    data () {
        return {
            isAuth: window.Auth.isAuth || false,
            authUser: window.Auth.authUser || null,
            isEditing: false,
            isUpdating: false,
        }
    },

    methods: {
        addParameterToURL (url, param) {
            url += (url.split('?')[1] ? '&':'?') + param;
            return url;
        },
        startUpdating () {
            this.isUpdating = true;
        },
        stopUpdating () {
            this.isUpdating = false;
        },
        canAccessPolicy (model) {
            if (! this.isAuth) return false;
            return this.authUser.id == model.user_id;
        },
        canAcceptPolicy (answer) {
            if (! this.isAuth) return false;
            return this.authUser.id == answer.question.user_id;
        },
        showLoginModal () {
            window.events.$emit('showLoginModal');
        },
        showRegisterModal () {
            window.events.$emit('showRegisterModal');
        },
        showReportModal (model_type, model_id) {
            window.events.$emit('showReportModal', model_type, model_id);
        },
        showShareModal (url) {
            window.events.$emit('showShareModal', url);
        },
        showAskModal (topic = '') {
            window.events.$emit('showAskModal', topic);
        },
        showAddSpaceModal () {
            window.events.$emit('showAddSpaceModal');
        },
        runToast (subject, message, type = 'primary', withSound = false) {
            window.events.$emit('runToast', subject, message, type, withSound);
        },
        __ (string) {
            return (window.lang.en && window.lang.en[string]) ? window.lang.en[string] : ':'+string;
        }
    }
}
