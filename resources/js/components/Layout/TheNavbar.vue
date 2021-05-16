<template>
    <nav class="navbar navbar-expand navbar-light fixed-top bg-white border-bottom">
        <div class="container">
            <a href="/" class="mr-1">
                <img src="/images/logo.png" height="20" class="align-top" :alt="site_name">
            </a>
            <a @click.prevent="scrollToTop(1000)" class="btn btn-sm btn-light bg-app-secondary mx-2 border py-2">
                <i class="fas fa-chevron-up text-muted"></i>
            </a>
            <div class="d-block d-md-none">
                <a @click.prevent="showSearch()" class="btn btn-sm btn-light bg-app-secondary border py-2">
                    <i class="fa fa-search text-muted"></i>
                </a>
            </div>

            <div id="search" class="d-none d-md-block w-50">
                <search-input></search-input>
            </div>

            <ul class="navbar-nav ml-auto pl-2">
                <span v-if="!isAuth">
                    <li class="nav-item d-none d-md-inline-block">
                        <a class="nav-link" href="#" @click.prevent="showLoginModal">
                            {{ __('Login') }}
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-inline-block">
                        <a class="nav-link" href="#" @click.prevent="showRegisterModal">
                            {{ __('Register') }}
                        </a>
                    </li>
                    <li class="nav-item d-inline-block d-md-none">
                        <a href="#" @click.prevent="showLoginModal" class="btn btn-sm btn-light bg-app-secondary border py-2">
                            <i class="fa fa-user text-muted"></i>
                        </a>
                    </li>
                </span>
                <span v-else>
                    <li class="nav-item d-none d-md-inline-flex align-top">
                        <notifications-check></notifications-check>
                    </li>
                    <li class="nav-item dropdown d-inline-flex">
                        <a id="navbarDropdown align-middle" class="nav-link" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <base-avatar
                                :user="authUser"
                                class="avatar-sm"
                                style="margin-top: -0.1rem">
                            </base-avatar>
                            <i class="fas fa-ellipsis-v ml-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <div class="border-bottom py-2 pl-4 pr-2 mb-1 text-app-primary">
                                {{ authUser.name }}
                            </div>
                            <a v-if="isAdmin" class="dropdown-item py-2" href="/admin/index">
                                <b>{{ __('Admin panel') }}</b>
                            </a>
                            <a class="d-md-none dropdown-item py-2" href="/notifications">
                                {{ __('Notifications') }}
                            </a>
                            <a class="dropdown-item py-2" :href="authUser.path">
                                {{ __('Your Profile') }}
                            </a>
                            <a class="dropdown-item py-2" href="#" @click.prevent="logout">
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </li>
                </span>
            </ul>
        </div>
    </nav>
</template>

<script type="text/javascript">
import NotificationsCheck from './../Notifications/NotificationsCheck.vue';
import SearchInput from './../Search/SearchInput.vue';

export default {
    props: {
        site_name: {
            type: String,
            required: true
        },
    },

    components: {
        NotificationsCheck, SearchInput
    },

    computed: {
        isAdmin () {
            return (this.authUser) ? this.authUser.is_admin == 1 : false;
        }
    },

    methods: {
        logout () {
            axios.post('/logout').then(() => {
                location.reload();
            });
        },
        scrollToTop (scrollDuration) {
            var cosParameter = window.scrollY / 2,
                scrollCount = 0,
                oldTimestamp = performance.now();
            function step (newTimestamp) {
                scrollCount += Math.PI / (scrollDuration / (newTimestamp - oldTimestamp));
                if (scrollCount >= Math.PI) window.scrollTo(0, 0);
                if (window.scrollY === 0) return;
                window.scrollTo(0, Math.round(cosParameter + cosParameter * Math.cos(scrollCount)));
                oldTimestamp = newTimestamp;
                window.requestAnimationFrame(step);
            }
            window.requestAnimationFrame(step);
        },
        showSearch () {
            document.getElementById('search').setAttribute('class', 'fixed-search');
        }
    }
}
</script>
