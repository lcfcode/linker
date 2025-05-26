/**
 * 弹出框
 */
var msgBox = {
    "box_id": null,
    "box_parent_id": "#_msg_box",
    "mask_id": "#mask",
    "sure_callback_fun": null,
    "cancel_callback_fun": null,
    "bland": true,

    "resize": function () {
        var h = $(window).height();
        var ch = $(this.box_id).height();
        var b_height = $('body').height();
        var height = b_height > $(window).height() ? b_height : $(window).height();
        $(this.mask_id).height(height);
        $(this.box_parent_id).height(height);
    },
    "show_box": function (box_id, msg, sure_callback, cancel_callback) {
        $(document).on("mousewheel DOMMouseScroll", function (evt) {
            evt.preventDefault();
        });
        this.box_id = box_id;
        if (msg) {
            var mgs_ = msg;
            if (mgs_.length > 14) {
                mgs_ = msg.substr(0, 14) + '...';
            }
            $(box_id).find('.p-title').html(mgs_);
        }

        $(this.mask_id).css('display', 'block');
        $(this.box_parent_id).css('display', 'block');
        $(box_id).css('display', 'block');

        if (sure_callback) {
            msgBox.sure_callback_fun = sure_callback;
        }
        if (cancel_callback) {
            msgBox.cancel_callback_fun = cancel_callback;
        }

        if (this.bland === true) {
            this.bind_event();
        }
        this.resize();
    },
    "hide_layout": function () {
        $(document).off("mousewheel DOMMouseScroll");
        $(this.mask_id).hide();
        $(this.box_parent_id).hide();
    },
    "bind_event": function () {
        $(this.box_parent_id + " .msg-enter").click(function () {
            if (msgBox.sure_callback_fun) {
                msgBox.sure_callback_fun();
                msgBox.sure_callback_fun = null;
                msgBox.hide_layout();
            } else {
                msgBox.hide_layout();
            }
        });
        $(this.box_parent_id + " .msg-cancel").click(function () {
            if (msgBox.cancel_callback_fun) {
                msgBox.cancel_callback_fun();
                msgBox.cancel_callback_fun = null;
                msgBox.hide_layout();
            } else {
                msgBox.hide_layout();
            }
        });
        $(window).resize(function () {
            msgBox.resize();
        });
        this.bland = false;
    }
};