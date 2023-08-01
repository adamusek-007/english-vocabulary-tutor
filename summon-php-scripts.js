// !Used in v_mode-selection.php
// !Used in v_words.php

// !Using s_get-subunit.php
// !Using s_get-words-view.php

function addWord(requestingElement) {
    const xhttp = new XMLHttpRequest();
    var userSelectionUnit = requestingElement.value;
    xhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("subunit-sel").innerHTML = this.responseText;
            getWordCount();
        }
    }
    xhttp.open("GET", "s_get-subunit.php?q=" + userSelectionUnit);
    xhttp.send();
}

function getTableWordsView(userSelectionUnit) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("table-view").innerHTML = this.responseText;
        }
    }
    xhttp.open("GET", "s_get-words-view.php?user-selection=" + userSelectionUnit);
    xhttp.send();
}
function setEditView(element) {
    var id = element.id;
    location.href = "v_edit-word.php?id=" + id;
}
function updateWord() {
    var hint = document.getElementById("hint").value;
    var unit = document.getElementById("unit").value;
    var subunit = document.getElementById("subunit").value;
    var id = document.getElementById("word-id").value;
    var en = document.getElementById("en").value;
    var pl = document.getElementById("pl").value;

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.href = 'v_words.php';
        }
    }
    xhttp.open("GET", "s_update-word.php?u=" + unit + "&s=" + subunit + "&i=" + id + "&e=" + en + "&v=" + hint + "&p=" + pl);
    xhttp.send();
}
