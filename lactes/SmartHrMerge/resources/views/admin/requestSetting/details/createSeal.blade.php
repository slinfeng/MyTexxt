
    <style>
        @font-face {
            font-family: "古印";
            src:url("{{ asset('assets/ttf/koin.ttf') }}");
        }
        @font-face {
            font-family: "印相";
            src:url("{{ asset('assets/ttf/insyo.ttf') }}");
        }
        @font-face {
            font-family: "篆書";
            src:url("{{ asset('assets/ttf/zhuanshu.ttf') }}");
        }
        #down_button{
            text-decoration:none;
            color: #000000;
        }
        #canvas{
            -webkit-transform: rotate(90deg);
        }
        #container{
            border-style:solid;
            width:150px;
            height:150px;
            color:red;
            border-width:4px;
        }

    </style>


<br>
<div id="container">
    <canvas width="150" height="150" id="canvas"></canvas>
</div><br>
<select  id="imj"  size="1"  onchange="pao()">
    <option value="">请选择</option>
    <option value="1">一行</option>
    <option value="2"></option>
    <option value="3">古印</option>
</select>
<select  id="img"  size="1"  onchange="pao()">
    <option value="">请选择</option>
    <option value="1">篆書</option>
    <option value="2">印相</option>
    <option value="3">古印</option>
</select>
<input id="fn" width="50" type="text" value='' onkeyup="setText()" maxlength="42" />
<a href="javascript:void(0)" id="down_button">下载</a>

