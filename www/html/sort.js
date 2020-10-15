$(function(){
    // セレクトが変更されたときに行う処理
    $('select').change(function() {   
        var sort_type = $('option:selected').val();
        var last_item_number = $('#last_item_number').val();

        $.ajax('/index_sort.php',{
            async: true,
            type: 'GET',
            data: {
              sort_type: sort_type, 
              last_item_number: last_item_number
            },
            dataType: 'json'
        })
        // Ajaxリクエストが成功したとき
        .done((data) => {
            $('#display').empty();        // #displayの要素を空っぽにする
            $('#display').html(data);     // #displayに新しい要素を追加する
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
});
