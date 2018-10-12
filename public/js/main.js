/*
$('document').ready(function (){

    function getParamByName(name, url) {
        if (!url) url = window.location.href;
        let myModal = $('.modal');
        let mainBody = $('body');
        let divModal = $('<div class="modal-backdrop fade show">');

        if (url.search("registred=1") >= 1){
            myModal.attr('class', 'modal fade show').css('display', 'block');
            mainBody.addClass('modal-open');
            console.log(mainBody);
            $(mainBody).append(divModal);
            myModal.removeAttr('aria-hidden');
            console.log(myModal);
        } else {
            /!*myModal.attr('aria-hidden', 'true');*!/
        }
    }
    /!*FIXME Appeler la fonction uniquement quand enregistré terminé*!/
    getParamByName('url');



});*/
