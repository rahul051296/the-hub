let url = "./getposts.php";
let post = document.getElementById("posts");
fetch(url,{method:'GET'})
.then(response => response.json())
.then((response) =>{
 
    for(let i=response.length; i>=1; i--){
        post.innerHTML += `<div  id="post-box">
                            <div class="row" id="pro-info">
                            <div class="col-2 col-lg-1 ">
                                <img src="${response[i].Profile}" onerror="this.onerror=null;this.src='img/profile_pic/default.png';" class=" rounded-circle " width="50px" id="post-circle" />
                            </div>
                            <div class="col-10 col-lg-11" id="pro-name" style="text-align:left">
                                <h5 style="margin-bottom:0;">${response[i].Name}</h5>
                                <p>@${response[i].Username}</p>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-11 offset-lg-1 col-md-10 offset-md-2">
                                <p class="text-left text-md-left" >${response[i].Post}</p>
                                </div>
                            </div></div>
                                `
    }
 });

