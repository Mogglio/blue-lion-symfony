$(document).ready(function(){
    var contentCalled = false;
    var popoverElement = $('[data-toggle="popover"]');

    popoverElement.popover({
        "html": true,
        trigger: 'manual',
        "content": function(){
            var div_id =  $(this).attr('id');
            return details_in_popup(div_id);
        }
    }).click(function(e) {
        $(this).popover('toggle');
        e.stopPropagation();
    });

    popoverElement.on('click', function (e) {
        popoverElement.not(this).popover('hide');
    });

    function details_in_popup(div_id){
        if (!contentCalled) {
            contentCalled = true;
            return " ";
        }
        $.ajax({
            type: 'POST',
            url: '/calendar/loading',
            data: {event_id: div_id},
            dataType: 'html',
            success: function(response){
                $('#thepopover').html(response);
            }
        });
        contentCalled = false;
        return '<div id="thepopover">Loading...</div>';
    }
});

$(document).on("click", '#add_to_event', function(event) {
    var id_event = $(this).data('event');

    $.ajax({
        type: 'POST',
        url: '/calendar/addtoevent',
        data: {event_id: id_event},
        dataType: 'html',
        success: function(response){
            $('#thepopover').html(response);
        }
    });
});