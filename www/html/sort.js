
// 並び変え機能　sort_typeのタイプをindex.phpにpostする
$(function() {

    // セレクトが変更されたときに行う処理
    $('select').change(function() {   

        // 選択されているoptionのvalueを取得してsort_typeに代入する
        var sort_type = $("option:selected").val();

        // index.phpにsort_typeをpostする, 処理に成功したらbodyを書きかえる
        $.post("index.php", {sort_type: sort_type}, function(data, status){
            $("body").html(data); 
        });
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


});
