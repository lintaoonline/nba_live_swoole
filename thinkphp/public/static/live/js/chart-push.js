$(function () {
    $("#discuss-box").keydown(function (event) {
        // console.log(111);
        if (event.keyCode == 13) {
            var text = $(this).val();
            var url = 'http://swoole.imooc.test:8811/?s=index/chart/index';
            var data = {'content': text, 'game_id': 1};
            $.post(url, data, function (result) {
                $(this).val("");
            }, 'json');
        }
    });
});