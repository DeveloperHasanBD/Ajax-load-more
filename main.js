
// chi siamo load more btn 
var url = action_url_ajax.ajax_url;
// collection load more btn 
var page = 2;
$("body").on('click', '.collection_lm_btn', function (e) {
    var get_luser_id = $(".get_luser_id").val();
    $.ajax({
        url: url,
        data: {
            action: 'collection_list_load_more',
            page: page,
            get_luser_id: get_luser_id,
        },
        type: 'post',
        dataType: 'JSON',
        success: function (data) {
            if (data.results.error == true) {
                $(".collection_lm_btn").html(data.results.message);
            } else {
                $(".collection_list_main").append(data.results.post_tems);
                page++;
            }
        },
    });
});


    // start mail sending #1 