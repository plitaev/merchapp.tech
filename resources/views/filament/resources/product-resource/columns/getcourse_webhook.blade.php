<a
    href="{{env("APP_URL")}}/getcourse_webhook/{{$getRecord()->id}}"
    target="_blank"
    class="fi-ta-text-item-label group-hover/item:underline group-focus-visible/item:underline text-sm leading-6 text-custom-600 dark:text-custom-400"
    style="--c-400:var(--primary-400);--c-600:var(--primary-600);"
>
    {{env("APP_URL")}}/getcourse_webhook/{{$getRecord()->id}}/GETCOURSE_USER_ID/GETCOURSE_USER_NAME/GETCOURSE_USER_EMAIL/IS_RECURRENT/RECURRENT_STATUS
</a>
