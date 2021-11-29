
<div style="width: 250px;height: 250px;border: 1px grey solid">
    <canvas id="myCanvas" width="200" height="200">

    </canvas>
</div>
<div style="display: none">
    <canvas id="tempCanvas" width="200" height="200">

    </canvas>
</div>
<input type="text" id="client-name">
<button onclick="newIcon()">入力</button>
<script>
    function newIcon() {
        let cname=$('#client-name').val();
        canvasDraw(cname);
    }
    function canvasDraw(cname) {
        let c=document.getElementById("myCanvas");
        let ctx=c.getContext("2d");
        ctx.strokeStyle="#ff0000";
        ctx.strokeRect(0,0,200,200);

        let tempimg = document.getElementById("tempCanvas");
        tempimg.width = 400;
        tempimg.height = 320;
        let oc = tempimg.getContext('2d');
        oc.color='#ff0000';
        oc.font="400px 篆体";	//css font属性
        oc.lineWidth=2;
        oc.fillText('忍',0,310)
        ctx.drawImage(tempimg, 0, 0, 400, 320, 0, 0, 100, 200);
        // ctx.drawImage(tempimg, 0, 0, 400, 400, 0, 0, 100, 100);
        ctx.drawImage(tempimg, 0, 0, 400, 320, 100, 0, 100, 100);
        ctx.drawImage(tempimg, 0, 0, 400, 320, 100, 100, 100, 50);
    }
</script>
