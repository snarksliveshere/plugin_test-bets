jQuery(document).ready(function($){

    function setAjaxBets(data, needCoockie = null  ) {
        var afterSendAddText = $('.after-bets_ajax');
        $.post(window.wp.ajax_url, data, function (res) {
            if (res.success) {
                afterSendAddText.text('Ставка прошла успешно');
                if (needCoockie) {
                    var formWithCookie =  $('form[data-cookie]');
                    var cook =  $('form[data-cookie]').data('cookie');
                    Cookies.set(cook, 'yes', 1);
                    formWithCookie.find('input[type=submit]').prop('disabled', 'disabled');
                }
            } else {
                afterSendAddText.text('Ставка не прошла. Проверьте введенные данные');
            }
        });
    }

    // для формы "Одиночная ставка"
    $('.add-bets_send').click(function () {
        var data = {
            'action': 'addBet',
            'id_user' : $('input[name=id_user]').val(),
            'title' : $('input[name=title]').val(),
            'content' : $('textarea[name=content]').val(),
            'type_bet' : $('select[name=bets_type]').val(),
        };
        setAjaxBets(data);
        return false;
    });

    // для формы "Ставка пройдет"
    if ($('.set_bets_form').length) {
        var getSetBetsCookie = Cookies.get('setBets');
        if (getSetBetsCookie) {
            $('.set_bets_form input[type=submit]').prop('disabled', 'disabled');
            $('.set_bets_form').next().text('Вы сегодня уже совершали ставку');
        }
    }

    $('.set_bets_send').click(function () {
        var data = {
            'action': 'setBet',
            'bet_id': $('input[name=id_set_bet]').val(),
        };
        setAjaxBets(data, 'yes');
        return false;
    });
});