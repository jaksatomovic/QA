@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <div class="bg-white">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="border-bottom pb-3">QALAR</h5>

                <p class="mb-3 text-muted">All these settings are loaded from .env file. After editing them manually you need to clear cache and cache config again. If you are updating them by submitting form - they will be cached automatically.</p>

                <form method="POST" action="/admin/settings">
                    @method('PUT')
                    @csrf

                    <div class="form-group row">
                        <label for="app_name" class="col-md-3 col-form-label">{{ __('Application name') }}</label>

                        <div class="col-md-8">
                            <input id="app_name" type="text" class="form-control" name="app_name" value="{{ config('app.name') }}" required>
                            <small>For replacing logo - upload your image into public/images folder with title logo.png</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="app_key" class="col-md-3 col-form-label">{{ __('Application key') }}</label>

                        <div class="col-md-8">
                            <input id="app_key" type="text" class="form-control" name="app_key" value="{{ config('app.key') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="app_url" class="col-md-3 col-form-label">{{ __('Application URL') }}</label>

                        <div class="col-md-8">
                            <input id="app_url" type="text" class="form-control" name="app_url" value="{{ config('app.url') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mail_driver" class="col-md-3 col-form-label">{{ __('Mail Driver') }}</label>

                        <div class="col-md-8">
                            <select id="mail_driver" class="form-control" name="mail_driver" required>
                                <option value="log" {{ (config('mail.host') == 'log' ? "selected":"") }}>Log</option>
                                <option value="mail" {{ (config('mail.host') == 'mail' ? "selected":"") }}>Mail</option>
                                <option value="smtp" {{ (config('mail.host') == 'smtp' ? "selected":"") }}>SMTP</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mail_host" class="col-md-3 col-form-label">{{ __('Mail Host') }}</label>

                        <div class="col-md-8">
                            <input id="mail_host" type="text" class="form-control" name="mail_host" value="{{ config('mail.host') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mail_port" class="col-md-3 col-form-label">{{ __('Mail Port') }}</label>

                        <div class="col-md-8">
                            <input id="mail_port" type="text" class="form-control" name="mail_port" value="{{ config('mail.port') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mail_username" class="col-md-3 col-form-label">{{ __('Mail Username') }}</label>

                        <div class="col-md-8">
                            <input id="mail_username" type="text" class="form-control" name="mail_username" value="{{ config('mail.username') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mail_password" class="col-md-3 col-form-label">{{ __('Mail Password') }}</label>

                        <div class="col-md-8">
                            <input id="mail_password" type="text" class="form-control" name="mail_password" value="{{ config('mail.password') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mail_from_address" class="col-md-3 col-form-label">{{ __('Mail Sender Address') }}</label>

                        <div class="col-md-8">
                            <input id="mail_from_address" type="text" class="form-control" name="mail_from_address" value="{{ config('mail.from.address') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mail_from_name" class="col-md-3 col-form-label">{{ __('Mail Sender Address') }}</label>

                        <div class="col-md-8">
                            <input id="mail_from_name" type="text" class="form-control" name="mail_from_name" value="{{ config('mail.from.name') }}">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-3">
                            <button type="submit" class="btn btn-action">
                                {{ __('Save Settings') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white mt-3">
        <div class="card">
            <div class="card-body p-4">
                <p class="mb-2">You can clear all application cache by clicking this button. Use it in case if you made changes with layout / config.</p>
                <a href="/admin/clear-cache" class="btn btn-action btn-sm">Clear All Cache</a>

                <p class="mt-5 mb-2">Your application version is <span class="text-app-primary">{{ config('app.version') }}</span>. Check for updates to check latest version available.</p>
                <check-for-updates></check-for-updates>
            </div>
        </div>
    </div>
@endsection
