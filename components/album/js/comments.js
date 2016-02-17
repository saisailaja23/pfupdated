var Comments = (function() {
    var $wrap = "",
        $fileID = 0,
        $input = "",
        $list = "",
        $cid = "",
        $cuserName = "",
        $cavatar = "",
        $type = "";
    return {
        init: function(wrap, fileID, type) {
            this.getUserData();
            $type = type;
            $wrap = wrap;
            $fileID = fileID,
            this.buildFrame()
            this.getComments();
        },
        getUserData: function() {
            $.getJSON(site_url + 'components/album/processors/comments.php?user=ture', function(d) {
                $cid = d.id;
                $cuserName = d.name;
                $cavatar = d.avatar;
            });
        },
        buildFrame: function() {
            var html =
                '<div class="msg"></div>' +
                '<ul class="comment-box">' +
                '</ul>' +
                '<div id="comment-form">' +
                '	<textarea placeholder="Type a message here..." rows="1" id="comment"></textarea>' +
                '</div>';
            $wrap.html(html);
            $input = $('#comment');
            $list = $('.comment-box');
            this.events();
        },
        getComments: function() {
            var self = this;
            $.getJSON(site_url + 'components/album/processors/comments.php?type=' + $type + '&action=get&fileID=' + $fileID, function(d) {

                if (d.status == "success") {
                    for (var i = 0; i < d.data.length; i++) {
                        self.buildUI(d.data[i]);
                    };
                } else {
                    $wrap.find('.msg').html('<h2>No comments, Be the first commenter.</h2>');
                }
            });
        },
        buildUI: function(d) {
            var time = new Date(d.cmt_time);

            var time_str = "at ";
            time_str += (time.getMonth() + 1) + "-";
            time_str += time.getDate() + "-";
            time_str += time.getFullYear() + " ";
            time_str += time.getHours() + ":";
            time_str += time.getMinutes();
            var tpl = '';
            tpl += '<li class="arrow-box-right gray">';
            tpl += '<div class="avatar"><img class="avatar-small" src="' + site_url + 'modules/boonex/avatar/data/images/' + d.Avatar + '.jpg?' + new Date().getTime() + '"></div>';
            tpl += '<div class="info">';
            tpl += '<span class="name"><strong>' + d.NickName + '</strong></span>';
            if(d.cmt_ago)
                tpl += '<span class="time">' + d.cmt_ago + '</span>';
            else
                tpl += '<span class="time">Just Now</span>';
            tpl += '</div>';
            tpl += d.cmt_text;
            tpl += '</li>';
            var msg = $list.append(tpl);
        },
        events: function() {
            var self = this;
            $('textarea').on('keyup', function(e) {
                e.stopPropagation();
                e.preventDefault();
                self.postComment(e, this);
            });
        },
        postComment: function(e, ths) {
            var self = this;

            if (e.keyCode == 13 && e.ctrlKey) {
                var content = ths.value,
                    caret = getCaret(this);
                ths.value = content.substring(0, caret) + "\n" + content.substring(caret, content.length - 1);
                e.stopPropagation();
            } else {
                if (e.keyCode == 13) {
                    if ($(ths).val().length == "1") {
                        $(ths).val("");
                        $(ths).height("23px");
                        return false;
                    } else {
                        var msg = $(ths).val();
                        $(ths).val("");
                        var d = {
                            "Avatar": $cavatar,
                            "cmt_text": msg,
                            "cmt_time": new Date().toString(),
                            "NickName": $cuserName
                        };
                        if ($wrap.find('.msg')) {
                            $wrap.find('.msg').remove();
                        }

                        self.buildUI(d);
                        $.get(site_url + "components/album/processors/comments.php?type=" + $type + "&action=insert", {
                            "fileID": $fileID,
                            "msg": msg,
                            "author": $cid,
                            "time": (new Date().getTime()) / 1000
                        }, function() {

                        });

                        $('#comment').css('height', '25px');
                        $('#comments').css('height', '84%');

                        $('#comments').slimScroll({
                            scrollTo: $list.height()
                        });

                        $('#comment').css('height', '23px');
                    }
                }
            }
        },
        getCaret: function(el) {
            if (el.selectionStart) {
                return el.selectionStart;
            } else if (document.selection) {
                el.focus();
                var r = document.selection.createRange();
                if (r == null) {
                    return 0;
                }

                var re = el.createTextRange(),
                    rc = re.duplicate();
                re.moveToBookmark(r.getBookmark());
                rc.setEndPoint('EndToStart', re);

                return rc.text.length;
            }
            return 0;
        }
    }
}());