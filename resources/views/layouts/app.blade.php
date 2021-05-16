<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'QALAR') }}</title>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ asset('fontawesome-5.8.1/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" v-cloak>
        <the-navbar site_name="{{ config('app.name', 'QALAR') }}"></the-navbar>

        <main class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-lg-2 p-0">
                        <div class="sticky-top sticky-offset sticky-top-left">
                            @hasSection('sidebar-left')
                                @yield('sidebar-left')
                            @else
                                <the-sidebar-main action="add_question"></the-sidebar-main>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-8 pb-3">
                        @yield('content')
                    </div>

                    <div class="col-md-3 col-lg-2 p-0">
                        <div class="sticky-top sticky-offset">
                            @hasSection('sidebar-right')
                                @yield('sidebar-right')
                            @else
                                <the-sidebar-second
                                    site_name="{{ config('app.name', 'QALAR') }}">
                                </the-sidebar-second>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <base-toasts
            hide-timeout="10"
            sound="{{ asset('sounds/notification.mp3') }}">
        </base-toasts>

        <question-ask-modal></question-ask-modal>
        <space-create-modal></space-create-modal>
        <share-modal></share-modal>

        @if (! Auth::check())
            <login-modal></login-modal>
            <register-modal></register-modal>
        @else
            <report-modal></report-modal>
        @endif
    </div>

    <script type="text/javascript">
        window.Auth = {!! json_encode([
            'isAuth' => Auth::check(),
            'authUser' => Auth::user()
        ]) !!}
    </script>

    @if(config('app.tracking_code'))
        <script type="text/javascript">
            {{ config('app.tracking_code') }}
        </script>
    @endif
</body>
</html>
