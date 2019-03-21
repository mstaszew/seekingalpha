$(document).ready(function() {
    function renderFollowBtns(following) {
        function isAuthorInTheList(id,following) {
            var i;
            for (i = 0; i < following.length; i++) {
                if (following[i].author_id==id) {
	            return true;
                }
            }
            return false; 
        }
        $.each($(".followlist button"),function(index,element){
                jq_el = $(element);
		var list_el_user_id=jq_el.attr("data-user-id");
                if(isAuthorInTheList(list_el_user_id,following)) {
                    jq_el.text('');
                    jq_el.removeClass('follow');
                    jq_el.addClass('unfollow');
                } else {
                    jq_el.text('Follow');
                    jq_el.addClass('follow');
                    jq_el.removeClass('unfollow');
                }
                jq_el.removeClass('hidden');
                console.log(list_el_user_id);
                console.log(following);
            }
	);
    }
    function followBtnClick(event) {
        var jq_target = $(event.currentTarget);
        var author_id = jq_target.attr('data-user-id');
        if (jq_target.hasClass('follow')) {
	    $.post('/index.php/follow',{'author_id':author_id},function(response) {
                console.log(response);
                if (response.status===0) {
                    var author_counter = $("#user_"+author_id+"_counter");

                    /* this is not going to be 100% accurate, for example if there are other users logged-in and they are 
                    following/unfollowing, but, in order to have it synchronized accurately with the DB 
                    much more work would be needed, for example by implementing polling or data push from the server. 
                    The approach here (modifying it locally) also minimizes network traffic */
                    author_counter.text(parseInt(author_counter.text())+1);

                    window.cache.getfollowing.push({'author_id':author_id});
                    renderFollowBtns(window.cache.getfollowing);
                } else {
                    alert(response.message);
                }
            });
        } else {
	    $.post('/index.php/unfollow',{'author_id':author_id},function(response) {
                console.log(response);
                if (response.status===0) {
                    var author_counter = $("#user_"+author_id+"_counter");

                    author_counter.text(parseInt(author_counter.text())-1);

                    var i;
                    var tmp=[];
                    for (i=0;i<window.cache.getfollowing.length;i++) {
                        if (window.cache.getfollowing[i].author_id==author_id)
                            continue;
                        tmp.push(window.cache.getfollowing[i]);
                    }
                    window.cache.getfollowing = tmp;
                    renderFollowBtns(window.cache.getfollowing);
                } else {
                    alert(response.message);
                }

            });
        }
        console.log(event);
    }
    function init() {	
        $.get('/index.php/getfollowing', [], function(response) {
            window.cache = {};
            window.cache.getfollowing = response;
            renderFollowBtns(response);   
        });
        $(".followlist button").on('click',followBtnClick);
    }
    init();
});
