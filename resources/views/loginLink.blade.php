@if(app()->environment(config('login-link.allowed_environments')))
    <form method="POST" action="{{ route('loginLinkLogin') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="key" value="{{  $key }}">
        <input type="hidden" name="attributes" value=@json($userAttributes)>

        @include('login-link::loginLinkButton')
    </form>
@endif
