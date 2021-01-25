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

async function deleteDocument(docId){
    let url = 'api/delete_document.php';
    let body = {
        id: docId
    }
    let response = await postData(url, JSON.stringify(body));
    if (response.status == 200){
        location.reload();
    }

    if(response.status == 500){
        let elem = document.getElementsByClassName('error-msg')[0];
        elem.style.display = 'block';
        setTimeout(() => {
            elem.style.display = 'none';
        }, 2000);
    }
}


window.onload = async function () {
    let jwtVal = getCookie('jwt');
    let elem = document.getElementById('docs');
    let response = await getData("./api/get_documents.php" + "?jwt=" + jwtVal);
    if (response.status == 200) {
        let documents = JSON.parse(response.data);
        var counter = 0;
        for (var i = 0; i < documents.length; i++) {
            ++counter;
            let document = '<div class="doc-block"> <div style="display:inline">';
            document += '<h3>' + documents[i]['name'] + '</h3>';
            document += '<span style="margin-right:8px"> <b>Рейтинг:</b> ' + documents[i]['rating'] + '</span>';
            document +=  '<span style="margin-right:8px"><b>Брой заемания</b> ' + documents[i]['loans_count'] + '</span>'
            document += '<div style="display:inline; float:right;margin-top: -20px;">'
            document += '<button type="button" class="trash-btn" onclick="deleteDocument(' +  documents[i]['id']  + ')">';
            document += '<i class="fa fa-trash-o" style="font-size:48px;"></i></i></button>';
            document += '<div/></div>';
            
            elem.innerHTML += document
        }
        if (counter == 0) {
            elem.innerHTML += '<div class="error-msg">Все още нямате качени документи</div>'

        }
    }
}