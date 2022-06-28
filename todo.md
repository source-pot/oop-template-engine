# TemplateEngine todo list
Because who doesn't like a list of things to do


1. Allow piping of variables into functions, e.g. `{{variable|str_to_upper}}`
2. Figure out how to do multi-parameter piping, e.g. to functions like `number_format`
3. Create caching of templates to disk to speed up processing of templates for repeat calls
    * The groundwork is present, a template is split into its components when it's created.
      The parsing is a separate process that happens later.
    * Perhaps serializing the Template instance and storing in a file with the template filename?
4. Global data on templates - Set on TemplateEngine then have Templates merge that in when parsing