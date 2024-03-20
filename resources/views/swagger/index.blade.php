<html>
<head>
    <title>{{ config('app.name') }} | Frontend API's Swagger</title>
    <link href="{{asset('/public/swagger/style.css')}}" rel="stylesheet">
</head>
<body>
<div id="swagger-ui"></div>
<script src="{{asset('/public/swagger/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('/public/swagger/swagger-bundle.js')}}"></script>
<script type="application/javascript">
    const ui = SwaggerUIBundle({
        url: "{{ asset('/public/swagger/swagger.yaml') }}",
        dom_id: '#swagger-ui',
    });
</script>
</body>
</html>
