{{@include:header.tpl}}

<h1>{{pageTitle}}</h1>
<article>

    {{@foreach:sections:section}}
        <section>
            <h2>{{section.title}}</h2>
        </section>
    {{@foreach:sections}}
</article>

{{@include:footer.tpl}}
