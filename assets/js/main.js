const register = () => {
    var nameVal = document.getElementById("name").value;
    var emailVal = document.getElementById("email").value;
    var passwordVal = document.getElementById("password").value;
    var body = {
        name: nameVal,
        email: emailVal,
        password: passwordVal
    }
    submitRequest("./api/register.php", JSON.stringify(body), true, true, "login.html");
}

const login = async () => {
    var emailVal = document.getElementById("email").value;
    var passwordVal = document.getElementById("password").value;
    var body = {
        email: emailVal,
        password: passwordVal
    }
    var response = await submitRequest("./api/login.php", JSON.stringify(body), true, true, "index.html");
    console.log(response);
    if (response['jwt']) {
        setCookie("jwt", response['jwt'], 1);
    }
}

const setCookie = (cname, cvalue, exdays) => {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

const getCookie = (cname) => {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

const checkIfAuthorized = async () => {
    var jwtVal = getCookie('jwt');
    var body = {
        jwt: jwtVal
    }

    var response = await submitRequest("./api/validate_token.php", JSON.stringify(body), false, false, "");
    console.log(jwtVal);
    if (response['authorization'] == "failure") {
        window.location.href = "login.html";
    } else {
        var elem = document.getElementById("name");
        elem.innerHTML = response['data']['name'];
    }
}

const submitRequest = (url, body, printOutput, refer, where) => {
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
            if (json['output'] == "success") {
                if (printOutput) {
                    var elem = document.getElementById("message");
                    elem.innerHTML = '<div class="success-msg">' + json['message'] + '</div>';
                }
                if (refer)
                    window.setTimeout(function () {
                        window.location.href = where;
                    }, 5000);
            } else {
                if (printOutput) {
                    var elem = document.getElementById("message");
                    elem.innerHTML = '<div class="error-msg">' + json['message'] + '</div>';
                }
            }
            return json;
        })
        .catch(error => console.error(error));
    return response;
}

let count = 2;

const addUploadFields = () => {
    var place = document.getElementById("add_documents");
    place.innerHTML += "<div class='elem'> <h4>Документ " + count++ + "</h4><div class='col-md-75 inline-block margin-bottom-10'> <input type='text' class='name' placeholder='Заглавие' /> </div> <div class='col-md-25 inline-block margin-bottom-10'> <select class='format'> <option value='html'>html</option> <option value='pdf'>pdf</option> </select> </div> <div class='margin-bottom-10'> <input type='text' class='keywords' placeholder='Ключови думи' /> </div> <div class='margin-bottom-10'> <textarea class='description' placeholder='Описание'></textarea> </div> <div> <label>Файл</label> <input class='file' type='file' /> </div> </div>";
}

const uploadDocuments = () => {
    const nameArr = document.getElementsByClassName('name');
    const formatArr = document.getElementsByClassName('format');
    const keywordsArr = document.getElementsByClassName('keywords');
    const descriptionArr = document.getElementsByClassName('description');
    const filesArr = document.getElementsByClassName('file');

    const formData = new FormData();
    for (var i = 0; i < nameArr.length; i++) {
        formData.append('name', nameArr[i].value);
        formData.append('format', formatArr[i].options[formatArr[i].selectedIndex].text);
        formData.append('keywords', keywordsArr[i].value);
        formData.append('description', descriptionArr[i].value);
        formData.append('file', filesArr[i].files[0]);
        formData.append('jwt', getCookie('jwt'));
        submitRequest("./api/upload_document.php", formData, true, false, "");
    }

}