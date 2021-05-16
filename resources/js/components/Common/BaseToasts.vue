<template>
    <div aria-live="polite" aria-atomic="true" class="toast-container">
        <TransitionGroup name="toast" tag="div" appear>
            <div v-for="item in messages" :key="item.id" class="toast show"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong :class="'py-2 mr-auto text-' + item.type" v-text="item.subject"></strong>
                    <button @click="remove(item)" type="button" class="close"
                        data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body" v-html="item.message"></div>
            </div>
        </TransitionGroup>
    </div>
</template>

<script>
export default {
    props: {
        hideTimeout: {
            default: 10
        },
        maxToasts: {
            default: 5
        },
        sound: {
            default: null
        },
    },

    data () {
        return {
            count: 0,
            messages: [],
        }
    },

    created () {
        window.events.$on('runToast', (subject, message, type, withSound) => {
            this.add(subject, message, type, withSound);
        });
    },

    methods: {
        add (subject, message, type, withSound) {
            var messageData = {
                id: this.count++,
                subject: subject,
                message: message,
                withSound: withSound,
                type: type,
                progress: 0,
                paused: false,
                animationFrame: null
            };

            if (this.messages.length >= this.maxToasts) {
                this.messages.splice(this.maxMessages);
            }

            if(this.sound && messageData.withSound) {
                var audio = new Audio(this.sound);
                audio.play();
            }

            this.messages.unshift(messageData);

            this.startTimeout(messageData);
        },

        startTimeout (messageData) {
            setTimeout(() => {
                this.remove(messageData);
            }, (this.hideTimeout * 1000));
        },

        remove (messageData) {
            const index = this.messages.findIndex(
                message => message.id === messageData.id
            );
            this.messages.splice(index, 1);
        },

        exists (messageData) {
            const index = this.messages.findIndex(
                message => message.id === messageData.id
            );
            if (index === -1) {
                return false;
            }
            return true;
        },
    }
}
</script>

<style scoped>
.toast-container {
    position: fixed;
    bottom: 20px;
    left: 20px;
    min-width: 250px;
    z-index: 1090;
}
.toast-enter {
    opacity: 0.5 !important;
}
.toast-enter-active {
    transition: opacity 0.5s ease-in;
}
.toast-enter-to {
    opacity: 1;
}
.toast-leave {
    opacity: 0.5;
}
.toast-leave-active {
    opacity: 0;
    transition: opacity 0.5s ease-out;
}
.toast-leave-to {
    opacity: 0;
}
</style>
