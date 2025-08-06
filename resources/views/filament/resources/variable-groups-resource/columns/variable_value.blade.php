@if (isset($getRecord()->value_string))
    {{$getRecord()->value_string}}
@endif

@if (isset($getRecord()->value_integer))
    {{$getRecord()->value_integer}}
@endif

@if (isset($getRecord()->value_date))
    {{$getRecord()->value_date}}
@endif

@if (isset($getRecord()->value_text))
    {{$getRecord()->value_text}}
@endif
