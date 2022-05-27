<form method="POST" action="{{ route('loginLinkLogin') }}">
    @csrf

    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="id" value="{{  $id }}">
    <input type="hidden" name="attributes" value="@json($userAttributes)">

    @include('login-link::loginLinkButton')
</form>
