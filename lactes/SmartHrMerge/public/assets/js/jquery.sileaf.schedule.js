(function ($) {
    "use strict";
    let thisObj;
    let Schedule = function (element, options) {
        this.default = {
            target: this,
            cellHeight: 6,
            itemCellWidth: 10,
            hourCellWidth: 8,
            em: 0,
            rows: 0,
            holiday: [],
            items: ['山本', '佐々木', '山田', '鈴木', '山岸'],
            hours: ['06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24'],
        };
        this.el = element;
        this.settings = $.extend(this.default, options);
        this.settings.em = Math.round(this.el.css('font-size').replace(/[^0-9.]/g, ''));
        this.init();
    };
    Schedule.prototype = {
        constructor: Schedule,
        init: function () {
            const obj = this.getSchedules(this);
            this.data = obj.data;
            this.item = obj.item;
            this.calendar = obj.calendar;
            this.event = obj.event;
        }, getSchedules: function (schedule) {
            let obj=null;
            $.ajax({
                url:this.settings.sourceUrl,
                type:'GET',
                data:this.settings.ajaxData,
                async:false,
                success:function (res) {
                    schedule.settings.items = res.items;
                    schedule.settings.data = res.data;
                    schedule.settings.members = res.members;
                    member_id=schedule.settings.ajaxData.memberId;
                    const item = new Items(schedule);
                    const calendar = new Calendar(schedule,item);
                    const event = new Event(schedule,item,calendar);
                    EventData.hourWidth = calendar.cellWidth;
                    const data = new Data(res.data);
                    // data.pushEventData(res.data);
                    obj = {item:item,calendar:calendar,event:event,data:data};
                    // this.event.calBodyCtx.strokeRect(409, 5, 200, 80 );
                    // const details=res.data;409 5 80
                    // $.each(details,function (index,detail) {
                    //     this.saveEvent(detail.schedule_date,detail.schedule_time,detail.member_id,detail.title,detail.user_name,detail.private_type,detail.anonymous_type,detail.color,detail.remark);
                    // });
                }
            });
            return obj;
        }, cancelAppendEvent:function () {
            this.event.calBodyCtx.putImageData(this.event.imgData, 0, 0);
        }, saveEvent:function (schedule_date,schedule_time,member_id,title,user_name,private_type,anonymous_type,color,remark) {
            // let event = this.event;
            // let data = event.eventData;
            // data.schedule_date = schedule_date;
            // data.schedule_time = schedule_time;
            // data.member_id = member_id;
            //
            // const arr = data.timePeriodToOffset(schedule_time);
            // data.x = arr[0];
            // data.w = arr[1]-arr[0];
            // data.h=70;
            // const ctx = event.calBodyCtx;
            // ctx.putImageData(event.imgData, 0, 0);
            // ctx.fillStyle=color;
            // ctx.fillRect(data.x, data.y, data.w, data.h);
            //
            // ctx.font='bold 12px';
            // ctx.textBaseline='top';
            // ctx.fillStyle='#000';
            // ctx.fillText(title,data.x, data.y,data.w);
            // ctx.fillText(remark,data.x, data.y+20,data.w);
            //
            //
            // data.user_name = user_name;
            // data.title = title;
            // data.anonymous_type = anonymous_type;
            // data.private_type = private_type;
            // data.color = color;
            // data.remark = remark;
            // this.data.pushEventData(data);
            // this.event.eventData = new EventData(0,0,0,0,false);
        }, getItems:function () {
            return this.settings.items;
        }, getItem:function (id) {
            let items=this.settings.items;
            let returnItem;
            $.each(items,function (index,item) {
                if(item.id==id){
                    returnItem = item;
                }
            });
            return returnItem;
        },  getSchedule:function () {
            return this;
        },getFontWidth:function (str,width) {
            // let fontWidth = str.
            // return this;
        },  getFontColor:function (color) {
            color=color.substring(1);
            let colorArr=[];
            if(color.length===3){
                colorArr[0]=color.substring(0,1);
                colorArr[0]=colorArr[0]+''+colorArr[0];
                colorArr[1]=color.substring(1,1);
                colorArr[1]=colorArr[1]+''+colorArr[1];
                colorArr[2]=color.substring(2,1);
                colorArr[2]=colorArr[2]+''+colorArr[2];
            }else{
                colorArr[0]=color.substring(0,2);
                colorArr[1]=color.substring(2,2);
                colorArr[2]=color.substring(4,2);
            }
            var i=0;
            if(colorArr[0]>'88'){
                i++;
            }
            if(colorArr[1]>'88'){
                i++;
            }
            if(colorArr[2]>'88'){
                i++;
            }
            if(i>=2){
                return '#000';
            }
            return '#fff';
    },InitData:function(data){
            let ajaxData=this.settings.ajaxData
            let items=this.settings.items;
            let hours=this.settings.hours;
            let schedule=this.getSchedule();
            $.each(items,function (index,item) {
                let hei=0;
                $.each(data,function (dataIndex,dataMember) {
                    let leftOption=item.id;
                    let rightOption=dataMember.member_id;
                    if(ajaxData.memberId!=''){
                        leftOption=item.name;
                        rightOption=dataMember.schedule_date;
                    }
                    if(leftOption==rightOption){
                        let event = schedule.event;
                        let data = event.eventData;
                        const ctx = event.calBodyCtx;
                        event.imgData = ctx.getImageData(0, 0, ctx.canvas.width, ctx.canvas.height);
                        ctx.putImageData(event.imgData, 0, 0);
                        data.item=item;
                        data.dataId=dataMember.id;
                        data.color = dataMember.schedule_color_type.css_name;

                        data.schedule_date = dataMember.schedule_date;
                        data.schedule_time = dataMember.schedule_time;
                        data.color_id=dataMember.color_id;
                        data.user_id=dataMember.user_id;
                        if(dataMember.user!=null){
                            data.user_name=dataMember.user.name;
                        }else{
                            data.user_name='';
                        }

                        data.member_id = dataMember.member_id;
                        data.title = dataMember.title==null?'':dataMember.title;
                        data.anonymous_type = dataMember.anonymous_type;
                        data.private_type = dataMember.private_type;
                        data.remark = dataMember.remark==null?'':dataMember.remark;

                        // schedule.saveEvent(dataMember.schedule_date,dataMember.schedule_time,dataMember.member_id,dataMember.title,dataMember.user_name,dataMember.private_type,dataMember.anonymous_type,dataMember.color,dataMember.remark);

                        const timeArr = dataMember.schedule_time.split(' ～ ');
                        let startAt=timeArr[0];
                        let endAt=timeArr[1];
                        const startArr = startAt.split(':');
                        const endArr = endAt.split(':');
                        endArr[1] = (parseInt(endArr[1])-1) + '';
                        let x = Math.floor((startArr[0]-hours[0])*EventData.hourWidth+Math.round(startArr[1]/60*EventData.hourWidth)+thisObj.calendar.paddingSide);
                        let w = Math.floor((endArr[0]-hours[0])*EventData.hourWidth+Math.round(endArr[1]/60*EventData.hourWidth)+thisObj.calendar.paddingSide)-x;


                        data.x=x;
                        if((schedule.calendar.cellHeight+hei+100)>schedule.calendar.cellHeight){
                            hei=hei-5;
                        }else{
                            hei=hei+5;
                        }
                        data.h=schedule.calendar.cellHeight+hei;

                        data.w=w;
                        data.y=schedule.calendar.cellHeight*index;

                        data.item=item;
                        data.startAt=startAt;
                        data.endAt=endAt;

                        const arr = data.timePeriodToOffset(dataMember.schedule_time);
                        data.x = arr[0];
                        data.w = arr[1]-arr[0];
                        ctx.fillStyle=dataMember.schedule_color_type.css_name;
                        ctx.fillRect(data.x, data.y, data.w, data.h);

                        ctx.font='bold 14px arial';
                        ctx.textBaseline='top';


                        ctx.fillStyle=schedule.getFontColor(data.color);
                        ctx.fillText(data.title,data.x+5, data.y+5,data.w-10);
                        ctx.fillText(data.schedule_time,data.x+5, data.y+25,data.w-10);
                        ctx.fillText(data.remark,data.x+5, data.y+45,data.w-10);

                        schedule.data.pushEventData(data);
                        schedule.event.eventData = new EventData(0,0,0,0,false);
                    }
                });
            });
        }
    };



    let Items = function (schedule) {
        this.em = schedule.settings.em;
        this.items = schedule.settings.items;
        this.itemsCount = this.items.length;
        this.cellHeight = this.em * schedule.settings.cellHeight;
        this.cellWidth = this.em * schedule.settings.itemCellWidth;
        this.parent = schedule.el;
        this.drawItems();
    };
    Items.prototype = {
        drawItems: function () {
            this.parent.append('<div id="items-canvas-body"></div>');
            const div = $('<div id="items"></div>');
            $('#items-canvas-body').append(div);
            div.height((this.itemsCount * this.cellHeight + 1) + 'px');
            div.width((this.cellWidth + 1) + 'px');
            this.appendCanvas(div);
        },
        appendCanvas: function (obj) {
            const canvas = $('<canvas id="canvas-items"></canvas>');
            canvas[0].width = obj.width();
            canvas[0].height = obj.height();
            obj.append(canvas);

            this.drawCanvas(canvas[0].getContext("2d"));
        }, drawCanvas: function (ctx) {
            let canvasWidth = ctx.canvas.width;
            let canvasHeight = ctx.canvas.height;
            ctx.translate(0.5, 0.5);
            ctx.lineWidth = 1;
            for (let i = 0; i < this.itemsCount + 1; i++) {
                ctx.beginPath();
                ctx.moveTo(0, i * this.cellHeight);
                ctx.lineTo(canvasWidth, i * this.cellHeight);
                ctx.strokeStyle = "#ccc";
                ctx.stroke();
            }
            for (let i = 0; i < 2; i++) {
                ctx.beginPath();
                ctx.moveTo(i * this.cellWidth, 0);
                ctx.lineTo(i * this.cellWidth, canvasHeight);
                ctx.strokeStyle = "#ccc";
                ctx.stroke();
            }
            ctx.font = this.em + "px 微软雅黑";
            for (let i = 0; i < this.itemsCount; i++) {
                ctx.fillText(this.items[i].name, 5, (i + 0.5) * this.cellHeight + 0.3 * this.em);
            }
        }
    };

    let Calendar = function (schedule,items) {
        this.paddingSide = 7;
        this.paddingUpDown = 5;
        this.items = schedule.settings.hours;
        this.itemsCount = this.items.length;
        this.offsetLeft = $('#items').width() + 10;
        this.em = schedule.settings.em;
        this.cellHeight = this.em * schedule.settings.cellHeight;
        this.cellWidth = this.em * schedule.settings.hourCellWidth;
        this.parent = schedule.el;
        this.rows = items.itemsCount;
        this.drawCalendar();
    }
    Calendar.prototype = {
        drawCalendar: function () {
            this.drawCalendarTitle();
            this.drawCalendarBody();
            this.synchronize();
        }, drawCalendarTitle: function () {
            const div = $('<div id="calendar-title"></div>');
            div.css('margin-left', this.offsetLeft + 'px')
            div.width('calc( 100% - ' + this.offsetLeft + 'px )');
            div.height(2 * this.em);
            div.css('line-height', 2 * this.em + 'px');
            this.parent.prepend(div);
            this.appendTitleCanvas(div);
        }, drawCalendarBody: function () {
            const div = $('<div id="calendar-body"></div>');
            $('#items-canvas-body').append(div);
            div.height();
            div.width('calc( 100% - ' + this.offsetLeft + 'px )');
            this.appendBodyCanvas(div);
        }, appendTitleCanvas: function (obj) {
            const canvas = $('<canvas id="canvas-cal-title"></canvas>');
            canvas[0].width = (this.itemsCount - 1) * this.cellWidth + 2 * this.paddingSide + 1;
            canvas[0].height = obj.height() + 1;
            obj.append(canvas);
            this.drawTitleCanvas(canvas[0].getContext("2d"));
        }, appendBodyCanvas: function (obj) {
            const canvas = $('<canvas id="canvas-cal-body"></canvas>');
            canvas[0].width = (this.itemsCount - 1) * this.cellWidth + 2 * this.paddingSide + 1;
            canvas[0].height = this.cellHeight * this.rows + 1;
            obj.append(canvas);
            this.drawBodyCanvas(canvas[0].getContext("2d"));
        }, drawTitleCanvas: function (ctx) {
            ctx.font = this.em + "px arial,sans-serif";
            for (let i = 0; i < this.itemsCount; i++) {
                ctx.fillText(this.items[i], i * this.cellWidth, this.em);
            }
        }, drawBodyCanvas: function (ctx) {
            let canvasWidth = ctx.canvas.width;
            let canvasHeight = ctx.canvas.height;
            ctx.save();
            ctx.translate(0.5, 0.5);
            ctx.lineWidth = 1;
            for (let i = 0; i < this.rows + 1; i++) {
                ctx.beginPath();
                ctx.moveTo(this.paddingSide, i * this.cellHeight);
                ctx.lineTo(canvasWidth - this.paddingSide, i * this.cellHeight);
                ctx.strokeStyle = "#ccc";
                ctx.stroke();
            }
            for (let i = 0; i < this.itemsCount + 1; i++) {
                ctx.beginPath();
                ctx.moveTo(i * this.cellWidth + this.paddingSide, 0);
                ctx.lineTo(i * this.cellWidth + this.paddingSide, canvasHeight);
                ctx.strokeStyle = "#ccc";
                ctx.stroke();
            }
            this.imgData = ctx.getImageData(0,0, ctx.canvas.width, ctx.canvas.height);
        }, synchronize: function () {
            $('#calendar-body').scroll(function () {
                $('#calendar-title')[0].scrollTo(this.scrollLeft, 0);
            });
        }
    };

    let Event = function (schedule,items,calendar){
        this.drag = false;
        this.ignoreWidth = 2;
        this.appendEvent = schedule.settings.appendEvent;
        this.editEvent = schedule.settings.editEvent;
        this.ajaxData = schedule.settings.ajaxData;
        this.members = schedule.settings.members;
        // this.showEventInfo = schedule.settings.showEventInfo;
        this.appendEventCancel = schedule.settings.appendEventCancel;
        this.showEventDiv = schedule.settings.showEventDiv;
        this.calBody = $('#calendar-body');
        this.calBodyCanvas = $('#canvas-cal-body');
        // this.rect=this.calBody[0].getBoundingClientRect();
        this.calBodyCtx = this.calBodyCanvas[0].getContext('2d');
        this.eventData = new EventData(0,0,0,0,false);
        this.init(items,calendar);
    }
    Event.prototype = {
        init: function () {
            this.bindDrag();
            this.bindMove();
            this.bindClick();
            this.bindDblClick();
        }, bindDrag() {
            const event = this;
            this.reOffset();
            event.calBody.mousedown(function (ev) {
                const calendar =thisObj.calendar;
                const e = ev || window.event;
                if (e.button === 0) {
                    const ctx = event.calBodyCtx;
                    event.drag = true;
                    event.lastDragWidth = 0;

                    const startCx = e.clientX;
                    const startCy = e.clientY;
                    const scrollY = document.documentElement.scrollTop || document.body.scrollTop;
                    // let startX = startCx - event.offsetLeft;
                    let startX = e.offsetX;
                    const temp = e.offsetY;
                    let startY = temp - temp % calendar.cellHeight + calendar.paddingUpDown;
                    // let startY = e.offsetY;
                    // const calBody=$('#calendar-body');
                    // const rect=$('#calendar-body').scrollLeft;
                    // startX=startX-rect.left*(calBody.width/rect.width);

                    // let startY = e.offsetY;

                    const hY = calendar.cellHeight - 2 * calendar.paddingUpDown;
                    event.eventData.x = startX;
                    event.eventData.y = startY;
                    event.eventData.h = hY;




                    event.handleWhenInEvent(startX,startY,function (eventData,index) {
                        event.dragEvent = true;
                        event.eventData.x = eventData.x;
                        event.eventData.y = eventData.y;
                        event.eventData.h = eventData.h;
                        event.eventData.w = eventData.w;
                        event.eventData.index = index;
                        event.eventData.dataId=eventData.dataId;
                        event.eventData.color=eventData.color;
                        event.eventData.title = eventData.title;
                        event.eventData.remark = eventData.remark;
                    });

                    event.imgData = ctx.getImageData(0, 0, ctx.canvas.width, ctx.canvas.height);
                    event.calBody.unbind('mousemove');
                    event.calBody.on('mousemove', function (ev) {
                        ctx.strokeStyle = "#ccc";
                        ctx.linewidth = 1;
                        const e = ev || window.event;
                        ctx.putImageData(event.imgData, 0, 0);
                        if(event.dragEvent){
                            ctx.putImageData(calendar.imgData, 0, 0, event.eventData.x, event.eventData.y, event.eventData.w+1, event.eventData.h+1);
                            event.lastDragWidth = e.clientX - startCx;
                            event.lastDragHeight = e.clientY - startCy;
                            ctx.strokeRect(event.eventData.x+event.lastDragWidth, event.eventData.y+event.lastDragHeight, event.eventData.w, event.eventData.h);
                        }else{
                            event.lastDragWidth = e.clientX - startCx;
                            ctx.strokeRect(startX, startY, event.lastDragWidth, hY);
                        }
                    });
                }
            });
            $(document).mouseup(function () {
                event.calBody.unbind('mousemove');
                event.bindMove();
                const lastDragWidth = Math.abs(event.lastDragWidth);
                const lastDragHeight = event.lastDragHeight === undefined ? 0 : event.lastDragHeight;
                if(event.dragEvent) {
                        const temp = event.eventData.y+lastDragHeight;

                        // const leftTopX=event.eventData.x+event.lastDragWidth;
                        // const leftTopY=temp - temp % thisObj.calendar.cellHeight + thisObj.calendar.paddingUpDown;
                        //
                        // const leftBottomX=leftTopX+event.eventData.w;
                        // const leftBottomY=leftTopY+event.eventData.h;
                        //
                        // const rightTopX=leftTopX+event.eventData.w;
                        // const rightTopY=leftTopY+event.eventData.h;
                        //
                        // const rightBottomX=leftTopX+event.eventData.w;
                        // const rightBottomY=leftTopY+event.eventData.h;
                        // let react=false;
                        // thisObj.data.eventDataArr.forEach(function(eventData,index){
                        //     if(((leftTopX>=eventData.x && leftTopX<=eventData.x+eventData.w && leftTopY>=eventData.y && leftTopY<=eventData.y+eventData.h) ||
                        //         (rightBottomX>=eventData.x && rightBottomX<=eventData.x+eventData.w && rightBottomY>=eventData.y && rightBottomY<=eventData.y+eventData.h) ||
                        //         (leftBottomX>=eventData.x && leftBottomX<=eventData.x+eventData.w && leftBottomY>=eventData.y && leftBottomY<=eventData.y+eventData.h) ||
                        //         (rightTopX>=eventData.x && rightTopX<=eventData.x+eventData.w && rightTopY>=eventData.y && rightTopY<=eventData.y+eventData.h) ||
                        //         (leftTopX<=eventData.x && rightBottomX>=eventData.x+eventData.w && rightBottomY<=eventData.y && leftTopY>=eventData.y+eventData.h))) {
                        //         react = true;
                        //     }
                        // });
                        const ctx = event.calBodyCtx;
                        ctx.putImageData(thisObj.calendar.imgData, 0, 0, event.eventData.x+event.lastDragWidth, temp, event.eventData.w+1, event.eventData.h+1);

                        // if(!react){
                        ctx.fillStyle=event.eventData.color;
                        ctx.fillRect(event.eventData.x+event.lastDragWidth, temp - temp % thisObj.calendar.cellHeight + thisObj.calendar.paddingUpDown, event.eventData.w, event.eventData.h);

                        const eventData = thisObj.data.eventDataArr[event.eventData.index];
                        eventData.change(event.eventData.x+event.lastDragWidth, temp - temp % thisObj.calendar.cellHeight + thisObj.calendar.paddingUpDown, event.eventData.w, event.eventData.h);
                        ctx.font='bold 12px';
                        ctx.textBaseline='top';
                        ctx.fillStyle=schedule.getFontColor(event.eventData.color);
                        ctx.fillText(event.eventData.title,event.eventData.x+event.lastDragWidth+5,temp - temp % thisObj.calendar.cellHeight + thisObj.calendar.paddingUpDown+5,event.eventData.w);
                        ctx.fillText(eventData.schedule_time,event.eventData.x+event.lastDragWidth+5, temp - temp % thisObj.calendar.cellHeight + thisObj.calendar.paddingUpDown+25,event.eventData.w);
                        ctx.fillText(event.eventData.remark,event.eventData.x+event.lastDragWidth+5, temp - temp % thisObj.calendar.cellHeight + thisObj.calendar.paddingUpDown+45,event.eventData.w);
                        // }else {
                        //     ctx.strokeRect(event.eventData.x, event.eventData.y, event.eventData.w, event.eventData.h);
                        // }
                        event.editEvent.call(undefined,eventData);
                        event.dragEvent = false;
                } else if (lastDragWidth <= event.ignoreWidth && event.drag) event.calBodyCtx.putImageData(event.imgData, 0, 0);
                else if (event.drag) {
                    event.drag = false;
                    event.eventData.w = event.lastDragWidth;
                    event.eventData.getItem();
                    event.eventData.calcTime();
                    event.appendEvent.call(undefined,event.eventData);
                }
            });
        }, bindMove() {
            const event = this;
            const div = $(event.showEventDiv);
            event.calBody.mousemove(function (ev) {
                div.hide();
                const e = ev || window.event;
                const calendar = thisObj.calendar;
                const x = e.clientX - event.offsetLeft;
                const scrollX = $('#canvas-cal-body').scrollLeft();
                const scrollY = document.documentElement.scrollTop || document.body.scrollTop;
                const temp = e.clientY - event.offsetTop + scrollY;
                const y = temp - temp % calendar.cellHeight + calendar.paddingUpDown;
                event.handleWhenInEvent(x,y,function (eventData,index) {
                    // event.showEventInfo.call(undefined,eventData);
                    // div.show();
                    // div.offset({left:e.clientX+scrollX,top:e.clientY+20+scrollY});
                });
            });
        },bindClick(){
            const event = this;
            event.calBody.mousedown(function (ev) {
                const e = ev || window.event;
                if(e.button === 2){
                    const x=e.clientX;
                    const y=e.clientY;
                    const mouseX=e.offsetX;
                    const mouseY=e.offsetY;
                    event.handleWhenInEvent(mouseX,mouseY,function (eventData,index) {
                        rightMenuShow(x,y,eventData);
                    },function () {
                        rightMenuHide();
                    });
                }else {
                    rightMenuHide();
                }
            });
        },bindDblClick(){
            const event = this;
            event.calBody.dblclick(function (ev) {
                const e = ev || window.event;
                const x=e.offsetX;
                const y=e.offsetY;
                event.handleWhenInEvent(x,y,function (eventData,index) {
                    event.editEvent.call(undefined,eventData);
                    event.dragEvent = false;
                    event.drag = false;
                });
            });
        }, reOffset() {
            const canvas = this.calBodyCanvas;
            canvas.offset({left:Math.floor(canvas.offset().left),top:Math.floor(canvas.offset().top)});

            canvasOffsetLeft=canvas.offset().left===0?canvasOffsetLeft:canvas.offset().left;
            canvasOffsetTop=canvas.offset().top===0?canvasOffsetTop:canvas.offset().top;
            // this.offsetLeft = canvas.offset().left;
            // this.offsetTop = canvas.offset().top;
            this.offsetLeft = canvasOffsetLeft;
            this.offsetTop = canvasOffsetTop;
        }, handleWhenInEvent(x,y,handle,elseHandle=function () {}) {
            let boo=0;
            thisObj.data.eventDataArr.forEach(function(eventData,index){
                if(x>=eventData.x && x<=eventData.x+eventData.w && y>=eventData.y && y<=eventData.y+eventData.h) {
                    handle(eventData,index);
                    boo++;
                }
            });
            if(boo===0){
                elseHandle();
            }
        },
    };

    let Data = function (){
        this.imgData = null;
        this.eventDataArr = [];
    }
    Data.prototype = {
        pushEventData:function (eventData) {
            this.eventDataArr.push(eventData);
        }
    };

    let EventDataId = 1;
    let EventData = function (x,y,w,h,f){
        this.change(x,y,w,h,f);
    }
    EventData.prototype = {
        xToStartHour:function (xSta) {
            // let hours=schedule.settings.hours;
            return Math.floor(xSta/EventData.hourWidth)+hours[0];
        },
        xToStartMinute:function (xSta){
            return Math.floor(60*(xSta%EventData.hourWidth)/EventData.hourWidth);
        },
        xwToEndHours:function (w,xSta) {
            // let hours=schedule.settings.hours;
            return Math.floor((w+xSta)/EventData.hourWidth)+hours[0];
        },
        xwToEndMinutes:function (w,xSta) {
            return Math.floor(60*((w+xSta)%EventData.hourWidth)/EventData.hourWidth) + 1;
        }, calcTime:function () {
            this.exchange();
            const xSta = this.x-thisObj.calendar.paddingSide;
            const startHour = this.xToStartHour(xSta);
            const startMinute = this.xToStartMinute(xSta);
            let m = this.xwToEndMinutes(this.w,xSta);
            let endHour = this.xwToEndHours(this.w,xSta);
            if(m%60===0) {
                endHour+=1;
                m = 0;
            }
            this.startAt =('0'+startHour).substr(-2)+':'+('0'+startMinute).substr(-2);
            this.endAt = ('0'+endHour).substr(-2)+':'+('0'+m).substr(-2);
            this.schedule_time = this.startAt + ' ～ ' + this.endAt;
        }, timePeriodToOffset:function (schedule_time) {
            const timeArr = schedule_time.split(' ～ ');
            const startArr = timeArr[0].split(':');
            const endArr = timeArr[1].split(':');
            endArr[1] = (parseInt(endArr[1])-1) + '';
            return [this.hmToX(startArr),this.hmToX(endArr)];
        }, hmToX:function (arr) {
            // let hours=schedule.settings.hours;
            return Math.floor((arr[0]-hours[0])*EventData.hourWidth+Math.round(arr[1]/60*EventData.hourWidth)+thisObj.calendar.paddingSide);
        }, getItem:function(){
            this.item = thisObj.settings.items[Math.round((this.y-thisObj.calendar.paddingUpDown)/thisObj.item.cellHeight)];
        }, change:function (x,y,w,h,f=true) {
            this.id = EventDataId;
            EventDataId++;
            this.x = x;
            this.y = y;
            this.w = w;
            this.h = h;
            if(f) {
                this.calcTime();
                this.getItem();
            }
        },exchange:function () {
            if(this.w<0){
                this.x = this.x+this.w;
                this.w = -this.w;
            }
        }
    }

    $.fn.schedule = function (options) {
        thisObj = new Schedule(this, options);
        return thisObj;
    };

})(jQuery);
