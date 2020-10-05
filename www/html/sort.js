
// 並び変え機能　sortのタイプをindex.phpにpostする
$(function() {

    // セレクトが変更されたときに行う処理
    $('select').change(function() {   

        // 選択されているoptionのvalueを取得してsortに代入する
        var sort = $("option:selected").val();

        // index.phpにsortをpostする, 処理に成功したらbodyを書きかえる
        $.post("index.php", {sort: sort}, function(data, status){ $("body").html(data); });
    });

});

// ページの番号割り振り　
$(function() {
    $('.page_number').on('click', function(){
        var page_number = $(this).attr('value');
        var current_page = $('#current_page').val();
        $.get('index.php', {page_number: page_number, current_page: current_page}, function(data, status){
            $('body').html(data);
        });
    });

    $('#back').click(function(){
        var page_up_type = 1;
        var current_page = $('#current_page').val();
        $.get('index.php', {page_up_type: page_up_type, current_page: current_page}, function(data, status){
            $('body').html(data);
        });
    });

    $('#next').click(function(){
        var page_up_type = 2;
        var current_page = $('#current_page').val();
        $.get('index.php', {page_up_type: page_up_type, current_page: current_page}, function(data, status){
            $('body').html(data);
        });
    });


});
