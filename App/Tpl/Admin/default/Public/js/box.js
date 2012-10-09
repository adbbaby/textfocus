(function($){
    $.fn.box = function(opts) {
        var defaults = {
            opacity:0.6,
            drag:true,
            title:'',
            content:'',
            closeText:'[x]',
            closeCallback:'',
            createCallback:'',
            left:300,
            top:100,
            width:400,
            height:300,
            setPos:true,
            overlay:false,
            loadStr:'Loading',
            ajaxSrc:''
        };
        var opts = $.extend(defaults, opts);

        var boxHtml = "<div id='box' style='background:#fff;border:solid 1px #93C3DF;position:absolute;z-index:100;'>";
        boxHtml += "<div id='bHead' style='height:20px;border-bottom:solid 1px #93C3DF;background:#B1DAEC;'>";
        boxHtml += "<div id='bTitle' style='float:left;width:80%;color:#fff; text-align:center; line-height:20px;'>" + opts.title + "</div>";
        boxHtml += "<div id='bClose' style='float:right;cursor:pointer;color:#00E'>"+opts.closeText+"</div>";
        boxHtml += "</div>";
        boxHtml += "<div id='bContent' style='background:#fff;'>" + opts.content + "</div>";
        boxHtml += "</div>";

        $(this).click(function(){
            if($.isFunction(opts.createCallback)){
                if(!opts.createCallback()){
                    return false;
                }
            }
            $("body").append(boxHtml);
            if (opts.ajaxSrc != "") {
                $("#bContent").append("<div id='bajax' style='width:" + (opts.width - 0) + "px;height:" + (opts.height - 20) + "px;overflow:scroll;'><div id='ajaxcontent'></div></div>");
                $("#ajaxcontent").load(opts.ajaxSrc);
            }
            addStyle();
            if (opts.drag) {
                drag();
            }
            $("#bClose").click(boxRemove);
            return false;
        });

        function addStyle() {
            var pos = setPosition();
            $('#box').css({ "left": pos[0], "top": pos[1], "width": opts.width + "px", "height": opts.height + "px" });
        }

        function boxRemove() {
            if($.isFunction(opts.closeCallback)){
                opts.closeCallback();
            }
            if ($("#box")) {
                $("#box").stop().fadeOut(200, function () {
                    $("#box").remove();
                    if (opts.overlay) {
                        $("#d_bg").remove();
                        $("#d_iframebg").remove();
                    }
                });
            }
        }
        function drag() {
            var dx,dy,moveout;
            var handle = $('#bHead').css('cursor','move');

            handle.mousedown(function(e){
                dx = e.clientX - parseInt($('#box').css('left'));
                dy = e.clientY - parseInt($('#box').css('top'));

                $('#box').mousemove(move).mouseout(out).css({ "opacity": opts.opacity });

                handle.mouseup(up);
            });
            move = function(e){
                moveout = false;
                win = $(window);
                var x,y;
                if(e.clientX - dx <0){
                    x = 0;
                }else{
                    if(e.clientX - dx >(win.width() - $('#box').width())){
                        x  = win.width() - $('#box').width();
                    }else {
                        x = e.clientX - dx;
                    }
                }
                if (e.clientY - dy < 0) {
                    y = 0;
                } else {
                    y = e.clientY - dy;
                }
                $("#box").css({
                    left: x,
                    top: y
                });
            }
            out = function (e) {
                moveout = true;
                setTimeout(function(){
                    moveout && up(e);
                },10);
            }
            up = function(e){
                $("#box").unbind("mousemove", move).unbind("mouseout", out).css("opacity", 1);
                handle.unbind("mouseup", up);
            }
        }

        function setPosition() {
            if (opts.setPos) {
                l = opts.left;
                t = opts.top;
            } else {
                var w = opts.width;
                var h = opts.height;

                var width = $(document).width();
                var height = $(window).height();
                var left = $(document).scrollLeft();
                var top = $(document).scrollTop();

                var t = top + (height / 2) - (h / 2);
                var l = left + (width / 2) - (w / 2);
            }
            return Array(l, t);
        }
        return this.each(function() {
            obj = $(this);

        });
    };
})(jQuery);