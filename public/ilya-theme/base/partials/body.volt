{{ partial('body_tags') }}
<body{{ body_tags('base', '') }}>

{#{{ partial('body/header') }}#}
{{ partial('body-content') }}
{#{{ partial('body/footer') }}#}
{#{{ partial('body/hidden') }}#}
{{ partial('body_script') }}

</body>