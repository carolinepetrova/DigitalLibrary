const getData = (url) => {
    var response = fetch(url, {
            mode: 'no-cors',
            method: 'get'
        }).then(response => {
            return response.json();
        })
        .then((json) => {
            console.log(json);
            if (json['output'] == "success") {
                return JSON.parse(json['data']);
            } else {
                return json;
            }
        })
        .catch(error => console.error(error));
    return response;
}

const getData2 = (url) => {
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

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const idVal = urlParams.get("id");
    var jwtVal = getCookie('jwt');
    var data = await getData("./api/get_document.php" + "?id=" + idVal + "&jwt=" + jwtVal);
    if (data) {
        var elem = document.getElementById('view_document');
        elem.innerHTML = ' <h2>Преглед на документ "' + data['name'] + '" </h2> <div class="container"> <div id="doc-preview" class="col-md-50 inline-block"> <iframe src="' + data['filename'] + '" width="100%" height="550" style="border:1px solid black;"> </iframe> </div> <div class="col-md-25 inline-block doc-info"> <h3 id="author">Автор</h3> ' + data['user_name'] + '<p> <h3 id="description">Описание</h3> ' + data['description'] + '</p> <h3 id="keywords">Ключови думи</h3> ' + data['keywords'] + '</p> </p> <h3 id="rating">Рейтинг</h3>' + data['rating'] + '/5 </p> <button type="button" id="gain-access" class="btn-red">Заяви достъп</button> </div></div>';
        scrollDetection();
        test();
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

const test = () => {
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("gain-access");
    var span = document.getElementsByClassName("close")[0];
    btn.onclick = function () {
        modal.style.display = "block";
    }
    span.onclick = function () {
        modal.style.display = "none";
    }
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}



const loanDocument = async () => {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const idVal = urlParams.get("id");
    const expDateElem = document.getElementsByClassName('exp-dates')[0];
    const expDate = expDateElem.options[expDateElem.selectedIndex].text;
    var jwtVal = getCookie('jwt');
    var data = await getData2("./api/loan_document.php" + "?doc_id=" + idVal + "&expiration_date=" + expDate + "&points=" + expDate + "&jwt=" + jwtVal);
    if (data['output'] == "success") {
        //setCookie("test", data['jwt'], 1);
        var elem = document.getElementById('loan-cont');
        let success = '<h3> Успешно заявихте документът!</h3>';
        success += '<p>Може да го достъпите на следния линк</p>';
        let link = 'http://localhost:8080/DigitalLibrary/view_document?doc=' + data['jwt'];
        success +=  '<p><input class="link-contaier" type="text" value="' + link + '"/></p>';
        elem.innerHTML = success;

        let linkContainer = document.getElementsByClassName('link-contaier')[0];
        linkContainer.onclick = function () {
            window.open(
                link,
                '_blank' 
              );
        }
    } else {
        var elem = document.getElementById('loan-cont');
        elem.innerHTML = '<div class="error-msg">' + data['message'] + '</div>';

    }
}