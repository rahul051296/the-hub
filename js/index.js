let start = document.getElementById('start');

document.forms.register.password2.onkeyup = () => {
    let p1 = document.forms.register.password.value;
    let p2 = document.forms.register.password2.value;
    console.log(p2);
    if (p1 !== p2) {
        document.getElementById('passErr').innerHTML = "Password should be the same.";
        document.getElementById('submit').className = " btn btn-block btn-primary mars-top-10 disabled";

    } else {
        document.getElementById('passErr').innerHTML = "";
        document.getElementById('submit').className = " btn btn-block btn-primary mars-top-10";
    }
};

