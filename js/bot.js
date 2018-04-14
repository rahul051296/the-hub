let chatbox = document.getElementById('main-box');
let fab = document.getElementById('fab');
let fab_close = document.getElementById('fab-close');
let ul = document.getElementById('conversation');
let chat = document.getElementById("chat-container");
let input = document.getElementById("chat-input");
let id = getId();

const URL = 'http://localhost:8081/api/v1';

input.addEventListener("keyup", function (event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        document.getElementById("btn").click();
    }
});

window.onload = function () {
    let status = document.getElementById('status');
    fetch(`${URL}/status`, {
        method: 'GET'
    })
        .then(function (response) {
            status.innerHTML = "<i class='fas fa-circle'></i> Online";
        })
        .catch(function (response) {
            status.innerHTML = "<i class='fas fa-circle' style='color:red'></i> Offline";
        })
}

function openchat() {
    chatbox.style.display = "block";
    fab.style.display = "none";
    fab_close.style.display = "block";
}

function closechat() {
    chatbox.style.display = "none";
    fab_close.style.display = "none";
    fab.style.display = "block";
}

function createSender(msg) {
    let li = document.createElement('li');
    li.appendChild(document.createTextNode(msg));
    li.className = "sender"
    ul.appendChild(li);
    document.getElementById('chat-input').value = "";
    chat.scrollTop = chat.scrollHeight;
}

function createResponder(msg) {
let li = document.createElement('li');
li.innerHTML = msg;
if (voice() == true)
    speak(li.innerText);
li.className = 'responder';
ul.appendChild(li)
chat.scrollTop = chat.scrollHeight;
}

function getId() {
    let id = '';
    let cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        if (cookie.includes("PHPSESSID")) {
            keys = cookie.split('=');
            id = keys[1];
            break;
        }
    }
    return id;
}

function speak(msg) {
    var speech = new SpeechSynthesisUtterance(msg);
    speech.voice = speechSynthesis.getVoices()[3];
    window.speechSynthesis.speak(speech);
}

function send() {
    let msg = document.getElementById('chat-input').value;
    createSender(msg);
    respond(msg);
}

function respond(msg) {
    data = {
        query: msg
    }
    fetch(`${URL}/${id}/respond`, {
        method: 'POST',
        body: JSON.stringify(data)
    })
        .then(function (response) {
            // document.getElementById('typing').style.display = "none";
            return response.json();
        })
        .then(function (responses) {
            console.log(responses);
            if (responses) {
                for (let response of responses) {
                    createResponder(response);
            }
            } else {
                createResponder("Sorry, I'm having trouble understanding you, try asking me in an other way")
            }

        })
        .catch(function (err) {
            // document.getElementById('typing').style.display = "none";
            createResponder("I'm having some technical issues. Try again later :)");
        });
}

function voice() {
    let speaker = document.getElementById('voice').checked;
    if (speaker == true)
        return true;
    else
        return false;
}