const isFieldEmpty = (field) => {
    return (field == null || field == "");
}

const throwError = (where, what) => {
    var elem_error = document.getElementById(where);
    elem_error.innerHTML = '<div class="error-msg">' + what + "</div>";
    setTimeout(() => {
        elem_error.removeChild(elem_error.children[0]);
    }, 3000);
}

const register = () => {
    var nameVal = document.getElementById("name").value;
    var emailVal = document.getElementById("email").value;
    var passwordVal = document.getElementById("password").value;

    let error = false;

    if (isFieldEmpty(nameVal)) {
        throwError("name-error", "Невалидно име");
        error = true;
    }

    if (isFieldEmpty(emailVal)) {
        throwError("email-error", "Невалиден имейл");
        error = true;
    }

    if (isFieldEmpty(passwordVal)) {
        throwError("password-error", "Невалидна парола");
        error = true;
    }

    var body = {
        name: nameVal,
        email: emailVal,
        password: passwordVal
    }
    if (!error)
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
        window.location.href = "index";
    }
}

const exit = () => {}

const setCookie = (cname, cvalue, exdays) => {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    console.log(document.cookie);
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

const destroyCookie = (cname) => {
    var cvalue = getCookie(cname);
    var d = new Date();
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

const logout = () => {
    destroyCookie('jwt');
    window.location.href = "login?logout=true";
}

const checkIfAuthorized = async () => {
    var jwtVal = getCookie('jwt');
    var body = {
        jwt: jwtVal
    }

    var response = await submitRequest("./api/validate_token.php", JSON.stringify(body), false, false, "");
    if (response['authorization'] === "failure") {
        window.location.href = "login";
    } else {
        var data = JSON.parse(response['data']);
        var elem = document.getElementById("name");
        elem.innerHTML = data['name'];
        var elem = document.getElementById("my_points");
        elem.innerHTML = "Моите точки: " + data['rating'];
    }
}

const checkIfLogged = async () => {
    var jwtVal = getCookie('jwt');
    var body = {
        jwt: jwtVal
    }

    var response = await submitRequest("./api/validate_token.php", JSON.stringify(body), false, false, "");
    if (response['authorization'] === "success") {
        window.location.href = "index";
    }
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const logout = urlParams.get("logout");
    if (logout) {
        var elem = document.getElementById("message");
        elem.innerHTML = '<div class="success-msg">Успешно излязохте!</div>';
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
        if (isFieldEmpty(nameArr[i].value) || isFieldEmpty(keywordsArr[i].value) ||
            isFieldEmpty(filesArr[i].files[0]) || isFieldEmpty(descriptionArr[i].value)) {
            throwError("message", "Всички полета са задължителни!");
            return;
        }
        formData.append('name', nameArr[i].value);
        formData.append('format', formatArr[i].options[formatArr[i].selectedIndex].text);
        formData.append('keywords', keywordsArr[i].value);
        formData.append('description', descriptionArr[i].value);
        formData.append('file', filesArr[i].files[0]);
        formData.append('jwt', getCookie('jwt'));
        submitRequest("./api/upload_document.php", formData, true, false, "");
    }

}

function dropdown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

function submitGetRequest(url) {
    let response = fetch(url, {
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

function createStarsFromRating(rating) {

    let fullStar = document.createElement("i");
    fullStar.setAttribute("class", "fa fa-star orange");
    let halfStar = document.createElement("i");
    halfStar.setAttribute("class", "fa fa-star-half-o orange");
    let emptyStar = document.createElement("i");
    emptyStar.setAttribute("class", "fa fa-star-o orange");

    let stars = [];
    for (let i = 0; i < 5; i++) {
        stars.push(emptyStar.cloneNode(true));
    }
    if (rating >= 0.5) {
        stars[0] = halfStar.cloneNode(true);
    }
    if (rating >= 1) {
        stars[0] = fullStar.cloneNode(true);
    }
    if (rating >= 1.5) {
        stars[1] = halfStar.cloneNode(true);
    }
    if (rating >= 2) {
        stars[1] = fullStar.cloneNode(true);
    }
    if (rating >= 2.5) {
        stars[2] = halfStar.cloneNode(true);
    }
    if (rating >= 3) {
        stars[2] = fullStar.cloneNode(true);
    }
    if (rating >= 3.5) {
        stars[3] = halfStar.cloneNode(true);
    }
    if (rating >= 4) {
        stars[3] = fullStar.cloneNode(true);
    }
    if (rating >= 4.5) {
        stars[4] = halfStar.cloneNode(true);
    }
    if (rating >= 5) {
        stars[4] = fullStar.cloneNode(true);
    }

    return stars;
}

function viewDocuments(response) {
    //clear documents from previous search if any
    let documentsContainer = document.getElementById("documents");
    documentsContainer.innerHTML = '';
    if (response.status == 200) {
        let documents = JSON.parse(response.data);
        for (let i = 0; i < documents.length; i++) {
            //document container
            let documentContainer = document.createElement("div");
            documentContainer.classList.add("doc-block");
            // document
            let documentElement = document.createElement("div");
            documentElement.classList.add("inline");
            // document name
            let nameElement = document.createElement("h3");
            let nameText = document.createTextNode(documents[i].name);
            nameElement.appendChild(nameText);
            // document rating
            let raitingContainer = document.createElement("span");
            raitingContainer.classList.add("ml-5");
            let stars = createStarsFromRating(documents[i].rating);
            for (let i = 0; i < stars.length; i++) {
                raitingContainer.appendChild(stars[i]);
            }
            nameElement.appendChild(raitingContainer);
            // document author
            let authorElement = document.createElement("span");
            authorElement.classList.add("bold");
            let authorText = document.createTextNode("Автор: ");
            authorElement.appendChild(authorText)
            let authorNameElement = document.createElement("span");
            let authorNameText = document.createTextNode(documents[i].author);
            authorNameElement.appendChild(authorNameText)
            //append document parts
            documentElement.appendChild(nameElement);
            documentElement.appendChild(authorElement);
            documentElement.appendChild(authorNameElement);
            //button
            let viewButton = document.createElement("div");
            viewButton.classList.add("inline");
            viewButton.classList.add("view-doc-btn-container");
            let button = document.createElement("a");
            button.classList.add("view-doc-btn");
            let previewLink = "http://localhost:8080/DigitalLibrary/preview_document?id=" + documents[i].id;
            button.setAttribute("href", previewLink);
            let buttonText = document.createTextNode("Преглед");
            button.appendChild(buttonText)
            viewButton.appendChild(button);
            // append document and button to document container
            documentContainer.appendChild(documentElement);
            documentContainer.appendChild(viewButton);
            // add whole document
            documentsContainer.appendChild(documentContainer);
        }
    }
    if (response.status == 404) {
        let errorElement = document.createElement("div");
        errorElement.classList.add("error-msg");
        let errorText = document.createTextNode("Няма намерени реферати.");
        errorElement.appendChild(errorText);

        documentsContainer.appendChild(errorElement);
    }
    if (response.status == 400) {
        let errorElement = document.createElement("div");
        errorElement.classList.add("error-msg");
        let errorText = document.createTextNode("Моля, въведете ключови думи или натиснете 'Чувствам се късметлия'.");
        errorElement.appendChild(errorText);

        documentsContainer.appendChild(errorElement);
    }
}

async function luckySearch(){
    let jwtVal = getCookie('jwt');
    let url = './api/lucky_search.php' + '?jwt=' + jwtVal;
    let response = await submitGetRequest(url);
    viewDocuments(response);
}

async function search(){
    let searchTerm = document.getElementById("search-term").value; 
    let jwtVal = getCookie('jwt');
    let url = './api/search.php' + "?q=" + searchTerm + '&' + 'jwt=' + jwtVal;

    let response = await submitGetRequest(url);
    viewDocuments(response);
}