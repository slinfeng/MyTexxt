@if(array_key_exists('toPdf',$_GET) && $_GET['toPdf'])
    <script>
        setTimeout(function () {
            doPrint(true,false,true);
        },1000);
    </script>
@endif
