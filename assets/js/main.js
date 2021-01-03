const register = () => {
    var nameVal = document.getElementById("name").value;
    var emailVal = document.getElementById("email").value;
    var passwordVal = document.getElementById("password").value;
    var body = {
        name: nameVal,
        email: emailVal,
        password: passwordVal
    }
    submitRequest("./api/register.php", body, true, true, "login.html");
}

const login = async () => {
    var emailVal = document.getElementById("email").value;
    var passwordVal = document.getElementById("password").value;
    var body = {
        email: emailVal,
        password: passwordVal
    }
    var response = await submitRequest("./api/login.php", body, true, true, "index.html");
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
    var response = await submitRequest("./api/validate_token.php", body, false, false, "");
    console.log(response);
    if (response['authorization'] == "failure") {
        window.location.href = "login.html";
    } else {
        var elem = document.getElementById("name");
        elem.innerHTML = response['data']['name'];
    }
}

const submitRequest = (url, body, printOutput, refer, where) => {
    var response = fetch(url, {
            method: 'post',
            body: JSON.stringify(body),
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