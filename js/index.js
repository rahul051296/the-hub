let start = document.getElementById('start');

document.forms.register.password2.onkeyup = () => {
    let p1 = document.forms.register.password.value;
    let p2 = document.forms.register.password2.value;
    console.log(p2);
    if (p1 !== p2) {
        document.getElementById('passErr').innerHTML = "Password should be the same.";
        document.getElementById('submit').className = " btn btn-block btn-success mars-top-30 disabled";

    } else {
        document.getElementById('passErr').innerHTML = "";
        document.getElementById('submit').className = " btn btn-block btn-success mars-top-30";
    }
};

let url = "search.php";

fetch(url,{method:'GET'})
.then(response => response.json())
.then((response) =>{

 let arr = [];
 arr = response.results;
 console.log(arr);
});