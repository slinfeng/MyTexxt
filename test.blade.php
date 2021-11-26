<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <style>
        .page{
            width: 200px;
            padding: 10px 20px;
            border: 1px solid #eee;
        }
        .container {
            overflow: hidden;
        }
        .container > .options{
            transition: all .5s;
            max-height: 0;
        }
        .container > .unfold{
            max-height: 250px;
        }
        .container > .btn{
            color: #4C98F7;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
    <script type="text/javascript">
        function operate(btn){
            const optionsNode = document.querySelector(".container > .options");
            const unfold = btn.getAttribute("unfold");
            if(unfold && unfold==="1"){
                btn.innerText = "收缩";
                optionsNode.classList.add("unfold");
            }else{
                btn.innerText = "展开";
                optionsNode.classList.remove("unfold");
            }
            btn.setAttribute("unfold", unfold === "0" ? "1" : "0");
        }
    </script>
</head>
<body>
<div class="page">
    <div class="container">
        <div class="btn" onclick="operate(this)" unfold="1">展开</div>
        <div class="options">
            <div class="option">选项1</div>
            <div class="option">选项2</div>
            <div class="option">选项3</div>
            <div class="option">选项4</div>
            <div class="option">选项5</div>
            <div class="option">选项6</div>
            <div class="option">选项7</div>
        </div>
    </div>
</div>
</body>
</html>
