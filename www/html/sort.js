$(function(){
    // セレクトが変更されたときに行う処理
    $('select').change(function() {   
        var sort_type = $('option:selected').val()
        $.ajax('/index_sort.php',{
            async: true,
            type: 'GET',
            data: {sort_type: sort_type},
        })
        // Ajaxリクエストが成功したとき
        .done((data) => {
            var json = {"a": data};
            console.log(json);
            $('row').html(json);
        })
        // Ajaxリクエストが失敗したとき
        .fail((data) => {
            alert('Error');      
        })
    });
});

// ページの番号割り振り　

$(function() {

    $('.page_number').on('click', function(){
        var current_page = $('#current_page').val();
        var page_number = $(this).attr('value');

        $.get('index.php', {page_number: page_number, current_page: current_page}, function(data, status){
            $('body').html(data);
        });
    });
/*
    $('#back').click(function(){
        var current_page = $('#current_page').val();
        var page_up_type = 1;
        $.get('index.php', {page_up_type: page_up_type, current_page: current_page}, function(data, status){
            $('body').html(data);
        });
    });

    $('#next').click(function(){
        var current_page = $('#current_page').val();
        var page_up_type = 2;
        $.get('index.php', {page_up_type: page_up_type, current_page: current_page}, function(data, status){
            $('body').html(data);
        });
    });
*/
});
