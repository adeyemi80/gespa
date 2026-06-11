<!DOCTYPE html>
<html>
<head><title>Test Flash</title></head>
<body>

@if(session('success'))
    <div style="color: green; font-weight: bold;">
        {{ session('success') }}
    </div>
@else
    <div style="color: red;">Aucun message flash</div>
@endif

</body>
</html>
