$(function() {

    // セレクトが変更されたときに行う処理
    $('select').change(function() {   

        // 選択されているoptionのvalueを取得してsortに代入する
        var sort = $("option:selected").val();

        // index.phpにsortをpostする, 処理に成功したらbodyを書きかえる
        $.post("index.php", {sort: sort}, function(data, status){ $("body").html(data); });
    });
});