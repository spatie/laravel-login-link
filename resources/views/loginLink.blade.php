@env(config('login-link.allowed_environments'))
    <form method="POST" action="{{ route('loginLinkLogin') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="key" value="{{ $key }}">
        <input type="hidden" name="redirect_url" value="{{ $redirectUrl }}">
        <input type="hidden" name="guard" value="{{ $guard }}">
        <input type="hidden" name="user_attributes" value="{{ json_encode($userAttributes) }}">
        <input type="hidden" name="user_model" value="{{ $userModel }}">

        @include('login-link::loginLinkButton')
    </form>
@endenv
