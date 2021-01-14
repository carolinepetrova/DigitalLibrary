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
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const docKey = urlParams.get("doc");
    var jwtVal = getCookie('jwt');
    var data = await getData("./api/view_document.php" + "?docKey=" + docKey + "&jwt=" + jwtVal);
    console.log(data);
    if (data['output'] == "success") {
        var elem = document.getElementById("view-document");
        elem.innerHTML = '<iframe src="' + data['url'] + '" width="100%" height="100%" style="border:1px solid black;"> </iframe>'
    } else {
        var elem = document.getElementById("view-document");
        elem.innerHTML = '<div class="error-msg">' + data['message'] + '</div>';
    }
}