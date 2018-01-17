let url = "./getposts.php";
let post = document.getElementById("posts");
fetch(url,{method:'GET'})
.then(response => response.json())
.then((response) =>{
 
    for(let i=response.length; i>=1; i--){
        post.innerHTML += `<div  id="post-box">
                            <div class="row" id="pro-info">
                            <div class="col-2 col-lg-1 ">
                               <a id="links" href="profile.php?user=${response[i].Username}"> <img src="${response[i].Profile}" onerror="this.onerror=null;this.src='img/profile_pic/default.png';" class=" rounded-circle " width="50px" id="post-circle" /></a>
                            </div>
                            <div class="col-10 col-lg-11" id="pro-name" style="text-align:left">
                                <a id="links" href="profile.php?user=${response[i].Username}"> <h5 style="margin-bottom:0;">${response[i].Name}</h5></a>
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

