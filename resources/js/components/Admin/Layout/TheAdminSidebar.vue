<template>
    <div>
        <a href="/admin/index" class="btn btn-action btn-block mb-3">
            {{ __('Dashboard') }}
        </a>

        <div class="bg-white border">
            <ul class="list-group list-group-flush font-size-14 navbar-list">
                <a href="/admin/reports" class="text-dark">
                    <li class="list-group-item py-1 pt-2">
                        <i class="far fa-flag fa-fw text-muted mr-1"></i> {{ __('Reports') }}
                    </li>
                </a>
                <a href="/admin/topics" class="text-dark">
                    <li class="list-group-item py-1 text-ellipsis">
                        <i class="fas fa-tags fa-fw text-muted mr-1"></i> {{ __('Topics & Spaces') }}
                    </li>
                </a>
                <a href="/admin/users" class="text-dark">
                    <li class="list-group-item py-1">
                        <i class="fas fa-users fa-fw text-muted mr-1"></i> {{ __('Users') }}
                    </li>
                </a>
                <a href="/admin/questions" class="text-dark">
                    <li class="list-group-item py-1">
                        <i class="far fa-question-circle fa-fw text-muted mr-1"></i> {{ __('Questions') }}
                    </li>
                </a>
                <a href="/admin/answers" class="text-dark">
                    <li class="list-group-item py-1">
                        <i class="fas fa-reply fa-fw text-muted mr-1"></i> {{ __('Answers') }}
                    </li>
                </a>
                <a href="/admin/pages" class="text-dark">
                    <li class="list-group-item py-1">
                        <i class="fas fa-file-alt fa-fw text-muted mr-1"></i> {{ __('Pages') }}
                    </li>
                </a>
                <a href="/admin/settings" class="text-dark">
                    <li class="list-group-item py-1">
                        <i class="fas fa-cogs fa-fw text-muted mr-1"></i> {{ __('Settings') }}
                    </li>
                </a>
                <a href="/admin/about" class="text-dark">
                    <li class="list-group-item py-1 pb-2">
                        <i class="fas fa-info-circle fa-fw text-muted mr-1"></i> {{ __('About') }}
                    </li>
                </a>
            </ul>
        </div>
    </div>
</template>
