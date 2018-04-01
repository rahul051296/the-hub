//python server.py -d models/dialogue -u models/nlu/default/wordsnlu -o out.log --cors *

let chatbox = document.getElementById('main-box');
let fab = document.getElementById('fab');
let fab_close = document.getElementById('fab-close');
let ul = document.getElementById('conversation');
let chat = document.getElementById("chat-container");
let input = document.getElementById("chat-input");


input.addEventListener("keyup", function (event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        document.getElementById("btn").click();
    }
});

window.onload = function () {
    fetch(`http://localhost:5004/status`)
        .then(function () {
            console.log("Bot Online");
            document.getElementById('status').innerHTML = `<span><i class="fas fa-circle"></i></span> Online`;
        })
        .catch(function () {
            console.log("Bot Offline");
            document.getElementById('status').innerHTML = `<span><i class="fas fa-circle" style="color:red"></i></span> Offline`;
        });
};

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
    let li = document.createElement('li');
    li.appendChild(document.createTextNode(msg));
    li.className = "sender";
    ul.appendChild(li);
    respond(msg);
    document.getElementById('chat-input').value = "";
    chat.scrollTop = chat.scrollHeight;
}

function respond(msg) {
    let id = getId();
    let url = `http://localhost:5004/respond?q=${msg}&id=${id}`
    fetch(url, {
            method: 'GET'
        })
        .then((response) => {
            response.json()
                .then((response) => {
                    let li = document.createElement('li');
                    li.innerHTML = response;
                    if (voice() == true)
                        speak(li.innerText);
                    li.className = "responder";
                    ul.appendChild(li);
                    chat.scrollTop = chat.scrollHeight;
                });
        });
}

function voice() {
    let speaker = document.getElementById('voice').checked;
    if (speaker == true)
        return true;
    else
        return false;
}