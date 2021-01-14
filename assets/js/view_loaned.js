const getData = (url) => {
    var response = fetch(url, {
            mode: 'no-cors',
            method: 'get'
        }).then(response => {
            return response.json();
        })
        .then((json) => {
            return json;
        })
        .catch(error => console.error(error));
    return response;
}

window.onload = async function () {
    var jwtVal = getCookie('jwt');
    var data = await getData("./api/get_loaned_by_user.php" + "?jwt=" + jwtVal);
    var elems = JSON.parse(data['data']);
    if (data['output'] == "success") {
        var elem = document.getElementById("loaned-docs");
        var elems = JSON.parse(data['data']);
        for (var i = 0; i < elems.length; i++) {
            console.log(elems[i]['token']);
            var date = new Date(elems[i]['exp_date'] * 1000);
            dateVal = date.getDate() + "/" + date.getMonth() + 1 + "/" + date.getFullYear();
            elem.innerHTML += '<div class="loaned-doc-block"> <div style="display:inline"> <h3>' + elems[i]['doc_name'] + '</h3> <span style="margin-right:8px"><b>Автор:</b>' + elems[i]['owner'] + '</span><span style="margin-right:8px"> <b>Рейтинг:</b> ' + elems[i]['rating'] + '</span> <span style="margin-right:8px"><b>Достъпен до:</b> ' + dateVal + '</span> </div> <div style="display:inline; float:right;margin-top: -20px;"> <a class="view-doc-btn" href="http://localhost:8080/DigitalLibrary/view_document?doc=' + elems[i]['token'] + '">Преглед</a> <a class="rate-doc-btn" href="#">Оцени</a> </div> </div>'
        }
    } else {
        var elem = document.getElementById("loaned-docs");
        elem.innerHTML = '<div class="error-msg">' + data['message'] + '</div>';
    }
}