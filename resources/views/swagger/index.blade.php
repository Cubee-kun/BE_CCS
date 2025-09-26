<!-- filepath: c:\Xampp\htdocs\CCS-project\BE_CCS\resources\views\swagger\index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>CCS API Documentation</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist/swagger-ui.css">
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            font-family: 'Nunito', 'Inter', Arial, sans-serif;
        }
        .swagger-ui .topbar {
            background: #388e3c;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 2px 8px rgba(56,142,60,0.08);
        }
        .swagger-ui .topbar .topbar-wrapper span {
            color: #fffde7;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }
        .swagger-ui .topbar .topbar-wrapper span:before {
            content: '';
            display: inline-block;
            width: 32px;
            height: 32px;
            margin-right: 12px;
            background: url('https://cdn-icons-png.flaticon.com/512/427/427735.png') no-repeat center/contain;
            /* Ganti dengan logo daun/alam favorit Anda */
        }
        .swagger-ui .info {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(56,142,60,0.04);
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 8px solid #81c784;
        }
        .swagger-ui .scheme-container {
            background: #e0f2f1;
            border-radius: 0.5rem;
        }
        .swagger-ui .opblock {
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(56,142,60,0.05);
            border-left: 6px solid #388e3c;
        }
        .swagger-ui .opblock .opblock-summary {
            border-radius: 1rem 1rem 0 0;
            background: #a5d6a7;
        }
        .swagger-ui .btn {
            background: #388e3c;
            color: #fff;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        .swagger-ui .btn:hover {
            background: #2e7031;
        }
        .swagger-ui .responses-inner {
            background: #e8f5e9;
            border-radius: 0.5rem;
        }
        .swagger-ui .response-col_status {
            color: #388e3c;
            font-weight: bold;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            background: #e0f7fa;
        }
        ::-webkit-scrollbar-thumb {
            background: #81c784;
            border-radius: 4px;
        }
        /* Tag color */
        .swagger-ui .opblock-tag.no-desc span {
            background: #388e3c !important;
            color: #fffde7 !important;
        }
        /* Table header */
        .swagger-ui table thead tr th {
            background: #a5d6a7 !important;
            color: #1b5e20 !important;
        }
    </style>
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist/swagger-ui-bundle.js"></script>
<script>

    window.onload = function() {
        SwaggerUIBundle({
            url: '/api-docs.json',
            dom_id: '#swagger-ui',
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIBundle.SwaggerUIStandalonePreset
            ],
            layout: "BaseLayout",
            docExpansion: "none",
            deepLinking: true,
            defaultModelsExpandDepth: -1,
        });

        // Ganti judul topbar jika ingin
        setTimeout(function() {
            let topbar = document.querySelector('.swagger-ui .topbar-wrapper span');
            if (topbar) {
                topbar.innerHTML = '🌿 CCS API - Nature Theme';
            }
        }, 500);
    }
</script>
</body>
</html>