
/* 清除搜索表单 */
function clearSearchForm() {
    let url_all = window.location.href;
    let arr = url_all.split('?');
    let url = arr[0];
    // $.pjax({url: url, container: '#pjax-container'});
}

$(function () {
    /* 返回按钮 */
    $('body').on('click', '.BackButton', function (event) {
        event.preventDefault();
        history.back(1);
    });

    /* 刷新按钮 */
    $('body').on('click', '.ReloadButton', function (event) {
        event.preventDefault();
        window.location.reload()
    });

});