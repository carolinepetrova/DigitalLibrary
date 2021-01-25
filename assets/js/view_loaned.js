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

const postData = (url, body) => {
    var response = fetch(url, {
            mode: 'no-cors',
            method: 'post',
            body: body,
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            }
        }).then(response => {
            return response.json();
        })
        .then((json) => {
            return json;
        })
        .catch(error => console.error(error));
    return response;
}

function rate(docId){
    let modalId = 'modal' + docId;
    let modal = document.getElementById(modalId);
    modal.style.display = 'block';
    let closeId = 'close' + docId;
    let close = document.getElementById(closeId);
    close.onclick = function () {
        modal.style.display = 'none';
    }
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    
}

async function submitRate(docId){
    let rateId = 'rates' + docId;
    let rate = document.getElementById(rateId).value;
    // api call to update rate for doc with id = docId
    let url = 'api/rate_document.php'
    let jwtVal = getCookie('jwt');
    let body = {
        id: docId,
        rating: rate,
        jwt: jwtVal
    }
    let response = await postData(url, JSON.stringify(body));
    let modalId = 'modal' + docId;
    let modal = document.getElementById(modalId);
    let errorId = 'error' + docId;
    let errorMessage = document.getElementById(errorId);
    let successId = 'success' + docId;
    let successMessage = document.getElementById(successId);
    if(response.status == 200){
        successMessage.style.display = 'block';

        setTimeout(() => {
            modal.style.display = 'none';
            successMessage.style.display = 'none';
        }, 2000);
    }
    else {
       errorMessage.style.display = 'block';

       setTimeout(() => {
        modal.style.display = 'none';
        errorMessage.style.display = 'none';
    }, 2000);
    }
}


window.onload = async function () {
    var jwtVal = getCookie('jwt');
    var data = await getData("./api/get_loaned_by_user.php" + "?jwt=" + jwtVal);
    var elems = JSON.parse(data['data']);
    if (data['output'] == "success") {
        var elem = document.getElementById("loaned-docs");
        var elems = JSON.parse(data['data']);
        var counter = 0;
        for (var i = 0; i < elems.length; i++) {
            var date = new Date(elems[i]['exp_date'] * 1000);
            if (date < new Date()) {
                continue;
            }
            ++counter;
            dateVal = date.getDate() + "/" + date.getMonth() + 1 + "/" + date.getFullYear();
            console.log(elems[i]['token']);
            let document = '<div class="loaned-doc-block"> <div style="display:inline"> <h3>' + elems[i]['doc_name'] + '</h3> <span style="margin-right:8px"><b>Автор:</b>' + elems[i]['owner'] + '</span><span style="margin-right:8px"> <b>Рейтинг:</b> ' + elems[i]['rating'] + '</span> <span style="margin-right:8px"><b>Достъпен до:</b> ' + dateVal + '</span> </div> <div style="display:inline; float:right;margin-top: -20px;"> <a class="view-doc-btn" href="http://localhost:8080/DigitalLibrary/view_document?doc=' + elems[i]['token'] + '">Преглед</a>'
            document += '<button type="button" class="rate-doc-btn" onclick="rate(' +  elems[i]['doc_id']  + ')">Оцени</button>';
            document += '<div class="modal" id="modal' + elems[i]['doc_id'] + '">';
            document += '<div class="modal-content">';
            document += '<span class="close" id="close' + elems[i]['doc_id'] + '">&times;</span>';
            document += '<div id="rate-cont"> <form><label>Оцени</label>';
            document += '<select class="rates" id="rates' + elems[i]['doc_id'] + '">';
            document += '<optgroup><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></optgroup></select>';
            document += '<button type="button" onclick="submitRate(' + elems[i]['doc_id'] + ')" class="btn-yellow">Оцени</button>';
            document += '<div class="error-msg hidden" id="error' + elems[i]['doc_id'] + '">Нещо се обърка. Моля, опитайте по-късно.</div>';
            document += '<div class="success-msg hidden" id="success' + elems[i]['doc_id'] + '">Оценката е записана успешно!</div>';
            document +='</form></div></div></div></div></div>'
            elem.innerHTML += document
        }
        if (counter == 0) {
            elem.innerHTML += '<div class="error-msg">В момента нямате заети документи</div>'

        }
    } else {
        var elem = document.getElementById("loaned-docs");
        elem.innerHTML = '<div class="error-msg">' + data['message'] + '</div>';
    }
}