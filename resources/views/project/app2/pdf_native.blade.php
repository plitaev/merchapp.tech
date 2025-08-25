@extends('project.app2.app')

@section('head')
@endsection

@section('content')

    <script src="/js/jquery-3.6.0.min.js"></script>

    <script src="https://telegram.org/js/telegram-web-app.js?56"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            app.BackButton.show();
            app.BackButton.onClick(function() {
                console.log("bb pressed");
                window.location.href="/app2/1";
            });
            app.ready();
        });

    </script>

    <iframe style="width: 100%; height: 100vh" src="/content/miniapp_pdf/{{$pdf}}.pdf"/>

@endsection
