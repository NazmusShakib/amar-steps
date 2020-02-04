<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('static/openapi/swagger-dist-ui/swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('static/openapi/swagger-dist-ui/favicon-32x32.png') }}"
          sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{ asset('static/openapi/swagger-dist-ui/favicon-16x16.png') }}"
          sizes="16x16"/>
</head>
<body>

<div id="swagger-ui"></div>

<script src="{{ asset('static/openapi/swagger-dist-ui/swagger-ui-bundle.js') }}"></script>
<script src="{{ asset('static/openapi/swagger-dist-ui/swagger-ui-standalone-preset.js') }}"></script>
<script>
    window.onload = function () {
// Begin Swagger UI call region
        const ui = SwaggerUIBundle({
            url: '{{ asset('static/openapi/schema.yml') }}',
            dom_id: '#swagger-ui',
            deepLinking: true,
            defaultModelsExpandDepth: -1,
            DisableTryItOutPlugin: false,
            presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
            plugins: [SwaggerUIBundle.plugins.DownloadUrl],
            layout: "StandaloneLayout"
        });
// End Swagger UI call region
        window.ui = ui
    }
</script>

</body>
</html>
