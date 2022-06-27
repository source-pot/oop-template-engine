{{@include:header.tpl}}

<article>
    {{pageTitle}}

    {{@foreach:sections:section}}
        <section>
            <h2>{{section.title}}</h2>
        </section>
    {{@foreach:section}}
</article>

{{@include:footer.tpl}}
