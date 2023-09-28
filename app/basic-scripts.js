function showHint() {
    document.getElementById('podpowiedz').classList.remove('display-none');
    document.getElementById('hintbulb').classList.add('display-none');
}
document.onkeydown = function (evt) {
    var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
    if (keyCode == 13) {
        document.form.submit();
    }
}