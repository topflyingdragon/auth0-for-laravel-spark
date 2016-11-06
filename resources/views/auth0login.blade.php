@extends('spark::layouts.app')

@section('content')
<div class="container">
    <div id="root" style="width: 320px; margin: 40px auto; padding: 10px; border-style: dashed; border-width: 1px; box-sizing: border-box;">
        embedded area
    </div>
    <script src="https://cdn.auth0.com/js/lock/10.2/lock.min.js"></script>
    <script>
        var lock = new Auth0Lock('<?php echo env('AUTH0_CLIENT_ID'); ?>', '<?php echo env('AUTH0_DOMAIN'); ?>', {
            container: 'root',
            auth: {
                redirectUrl: '<?php echo env('AUTH0_CALLBACK_URL'); ?>',
                responseType: 'code',
                params: {
                    scope: 'openid email' // Learn about scopes: https://auth0.com/docs/scopes
                }
            }
        });

        lock.show();
    </script>
</div>
@endsection
