@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <div class="bg-white">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="border-bottom pb-2">About</h5>
                <p class="mt-3">QALAR - question & answers platform build on Laravel and Vue.</p>
                <div class="mt-4 row">
                    <div class="col-md-3 text-center">
                        <a href="https://laravel.com/" target="_blank" class="text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="60" viewBox="0 0 84.1 57.6"><path fill="#FB503B" d="M83.8 26.9c-.6-.6-8.3-10.3-9.6-11.9-1.4-1.6-2-1.3-2.9-1.2s-10.6 1.8-11.7 1.9c-1.1.2-1.8.6-1.1 1.6.6.9 7 9.9 8.4 12l-25.5 6.1L21.2 1.5c-.8-1.2-1-1.6-2.8-1.5C16.6.1 2.5 1.3 1.5 1.3c-1 .1-2.1.5-1.1 2.9S17.4 41 17.8 42c.4 1 1.6 2.6 4.3 2 2.8-.7 12.4-3.2 17.7-4.6 2.8 5 8.4 15.2 9.5 16.7 1.4 2 2.4 1.6 4.5 1 1.7-.5 26.2-9.3 27.3-9.8 1.1-.5 1.8-.8 1-1.9-.6-.8-7-9.5-10.4-14 2.3-.6 10.6-2.8 11.5-3.1 1-.3 1.2-.8.6-1.4zm-46.3 9.5c-.3.1-14.6 3.5-15.3 3.7-.8.2-.8.1-.8-.2-.2-.3-17-35.1-17.3-35.5-.2-.4-.2-.8 0-.8S17.6 2.4 18 2.4c.5 0 .4.1.6.4 0 0 18.7 32.3 19 32.8.4.5.2.7-.1.8zm40.2 7.5c.2.4.5.6-.3.8-.7.3-24.1 8.2-24.6 8.4-.5.2-.8.3-1.4-.6s-8.2-14-8.2-14L68.1 32c.6-.2.8-.3 1.2.3.4.7 8.2 11.3 8.4 11.6zm1.6-17.6c-.6.1-9.7 2.4-9.7 2.4l-7.5-10.2c-.2-.3-.4-.6.1-.7.5-.1 9-1.6 9.4-1.7.4-.1.7-.2 1.2.5.5.6 6.9 8.8 7.2 9.1.3.3-.1.5-.7.6z"></path></svg>
                            <p class="mt-2">Laravel 5.8</p>
                        </a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="https://vuejs.org/" target="_blank" class="text-dark">
                            <img src="https://vuejs.org/images/logo.png" style="height: 60px;">
                            <p class="mt-2">VueJs 2.6</p>
                        </a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="https://getbootstrap.com/" target="_blank" class="text-dark">
                            <img src="https://getbootstrap.com/docs/4.3/assets/brand/bootstrap-solid.svg" style="height: 60px;">
                            <p class="mt-2">Bootstrap 4.3</p>
                        </a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="https://jquery.com/" target="_blank" class="text-dark">
                            <img src="https://brand.jquery.org/resources/jquery-mark-light.gif" style="height: 60px;">
                            <p class="mt-2">jQuery 3.4</p>
                        </a>
                    </div>
                </div>
                <div class="mt-3">
                    <p>Please avoid modifications with default css / js code. Instead, create new css and js files and add them into resources/views/layouts/app.blade.php</p>
                    <p>For any issues or customizations requests - send messages on <a href="https://xandr.co" target="_blank">xandr.co</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
