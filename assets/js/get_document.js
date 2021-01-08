const getData = (url) => {
    var response = fetch(url, {
            mode: 'no-cors',
            method: 'get'
        }).then(response => {
            return response.json();
        })
        .then((json) => {
            if (json['output'] == "success") {
                return JSON.parse(json['data']);
            } else {
                return;
            }
        })
        .catch(error => console.error(error));
    return response;
}

window.onload = async function () {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const idVal = urlParams.get("id");
    var jwtVal = getCookie('jwt');
    var data = await getData("./api/get_document.php" + "?id=" + idVal + "&jwt=" + jwtVal);
    if (data) {
        var elem = document.getElementById('view_document');
        elem.innerHTML = ' <h2>Преглед на документ "' + data['name'] + '" </h2> <div class="container"> <div id="doc-preview" class="col-md-50 inline-block"> <iframe src="' + data['filename'] + '" width="100%" height="550" style="border:1px solid black;"> </iframe> </div> <div class="col-md-25 inline-block doc-info"> <h3 id="author">Автор</h3> ' + data['user_name'] + '<p> <h3 id="description">Описание</h3> ' + data['description'] + '</p> <h3 id="keywords">Ключови думи</h3> ' + data['keywords'] + '</p> </p> <h3 id="rating">Рейтинг</h3>' + data['rating'] + '/5 </p> <button type="button" class="btn-red">Заяви достъп</button> </div></div>';
        scrollDetection();
    }
};

const scrollDetection = () => {
    var myFrame = document.getElementsByTagName('iframe')[0];
    var flag = false;
    myFrame.contentWindow.onscroll = function () {
        if (myFrame.contentWindow.window.scrollY >= myFrame.contentWindow.document.body.scrollHeight * 0.3) {
            var elem = document.getElementById('doc-preview');
            elem.innerHTML = "<div id='overlay'><p>Заявете достъп до документа, за да достъпите пълното му съдържание.</p></div>";
        }

    };
}