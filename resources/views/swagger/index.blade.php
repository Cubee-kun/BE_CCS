{{-- filepath: resources/views/swagger/index.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>CCS API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist/swagger-ui.css" >
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist/swagger-ui-bundle.js"></script>
<script>
    window.onload = function() {
        SwaggerUIBundle({
            url: '/api-docs.json',
            dom_id: '#swagger-ui',
        });
    }
</script>
</body>
</html>