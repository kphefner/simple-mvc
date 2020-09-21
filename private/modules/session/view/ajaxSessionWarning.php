<div>
    <p>Your session will expire at <span id="expire_time">2:00</span></p>
    <input class="primary-button btn btn-dark" type="button" name="continue_session" id="continue_session" value="Continue Session">
</div>
<script type="text/javascript">
    $(function(){
        var expireTime = new Date();
        expireTime.setSeconds(expireTime.getSeconds() + <?php echo ((!empty($this->seconds_remaining))?$this->seconds_remaining:120);?>)
        $("#expire_time").html(expireTime.toLocaleTimeString());
        setTimeout(function(){$("#continue_session").focus();},100);
        $("#continue_session").on("click",function(e){
            e.preventDefault();
            clearInterval(counter);
            var jqXHR = $.ajax({
                type: "POST",
                url: "/session/ajaxKeepAlive",
                dataType: "json"
            })
            .done(function(resp){
                // Load up a growler letting user know the session has been renewed
                // seed the message system
				$("#msgbox").systemMessage("option", "title", "SUCCESS");
				$("#msgbox").systemMessage("option", "messages", resp.msg);
				$("#msgbox").systemMessage("show");
                
                if(resp.success) {
    				$("#wc_dialog").dialog("close");
    				
    				// Reset the global timer
    				sessionWarn();
				}
            })
            .fail(function(resp,msg,err){
                $("#msgbox").systemMessage("option", "title", "ERROR");
                $("#msgbox").systemMessage("option", "messages", msg+" "+err);
                $("#msgbox").systemMessage("show");
                $("#wc_dialog").dialog("close");
            });
            
        });
        
        var count = <?php echo ((!empty($this->seconds_remaining))?$this->seconds_remaining:120);?>;
        var counter = setInterval(timer, 1000);
        function timer() {
            count = count - 1;
            var minutes = Math.floor(count / 60);
            var seconds = count - (minutes * 60);
            //$("#time_remaining").html(minutes+":"+seconds);
            if(count <= 0) {
                clearInterval(counter);
                counter = null;

                $.getJSON("/session/ajaxCheckSession",function(resp){
                    
                    if(resp.data.seconds_remaining <= 0) {
                        // we should be able to safely trigger the click and open the ajaxSessionLogin screen
                        $("#continue_session").trigger("click");
                    } else if(resp.data.seconds_remaining > 0 && resp.data.seconds_remaining < 300) {
                        // reset the reported time unitl expire and reset count and recall this funtion
                        var expireTime = new Date();
                        expireTime.setSeconds(expireTime.getSeconds() + <?php echo ((!empty($this->seconds_remaining))?$this->seconds_remaining:120);?>)
                        $("#expire_time").html(expireTime.toLocaleTimeString());
                        // reset the count
                        count = resp.data.seconds_remaining;
                        counter = setInterval(timer, 1000);
                    } else if(resp.data.seconds_remaining >= 300) {
                        // session activity has occured elsewhere and we should reset the session warning timer
                        // close the dialog and report session has been extended?
                        $(".ui-button[title='close']").trigger("click");
                        sessionWarn(Math.floor(resp.data.seconds_remaining / 60) - 2);
                    }
                    
                });
                

            }
        }
    });
</script>