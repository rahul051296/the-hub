//python server.py -d models/dialogue -u models/nlu/default/wordsnlu -o out.log --cors *

let ul = document.getElementById('conversation');
var chat = document.getElementById("chat-container");

function send(){
    let msg = document.getElementById('chat-input').value;
    let li = document.createElement('li');
    li.appendChild(document.createTextNode(msg));
    li.className = "sender"
    ul.appendChild(li);
    respond(msg);
    document.getElementById('chat-input').value = "";
    chat.scrollTop = chat.scrollHeight;
}
function respond(msg){
 let url = `http://localhost:5004/respond?q=${msg}`
fetch(url,{method:'GET'})
.then((response)=>{
  response.json()
  .then((response)=>{
    let li = document.createElement('li');
    li.innerHTML = response;
    li.className = "responder"
    ul.appendChild(li);
    chat.scrollTop = chat.scrollHeight;
   console.log(response); 
  });
});
}
var input = document.getElementById("chat-input");
input.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        document.getElementById("btn").click();
    }
});
