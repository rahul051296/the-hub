let chatbox = document.getElementById('main-box');
let fab = document.getElementById('fab');
let fab_close = document.getElementById('fab-close');
chatbox.style.display = "none";
function openchat(){  
    chatbox.style.display = "block";
    fab.style.display = "none";
    fab_close.style.display = "block"; 
}
function closechat(){
    chatbox.style.display = "none";
    fab_close.style.display = "none";
    fab.style.display = "block";
}