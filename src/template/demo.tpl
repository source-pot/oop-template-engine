{{@include:header.tpl}}

<h1>{{pageTitle}}</h1>
{{@if:loggedIn}}
    <h2>Welcome, {{username}}</h2>
{{@endif:loggedIn}}

<article>
{{@include:section.tpl}}
</article>

{{@include:footer.tpl}}
