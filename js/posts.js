let url = "./getposts.php";
let post = document.getElementById("posts");
fetch(url,{method:'GET'})
.then(response => response.json())
.then((response) =>{
    
    for(let i=response.length; i>=1; i--){
        let day = uToTime(response[i].Time);
        console.log(day);
        post.innerHTML += `<div  id="post-box">
                            <div class="row" id="pro-info">
                            <div class="col-2 col-lg-1 ">
                               <a id="links" href="profile.php?user=${response[i].Username}"> <img src="${response[i].profile}" onerror="this.onerror=null;this.src='img/profile_pic/default.png';" class=" rounded-circle " width="50px" id="post-circle" /></a>
                            </div>
                            <div class="col-10 col-lg-11" id="pro-name" style="text-align:left">
                                <a id="links" href="profile.php?user=${response[i].Username}"> <h5 style="margin-bottom:0;">${response[i].Name}</h5></a>
                                <p style="font-size:0.75em">${day}</p>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-11 offset-lg-1 col-md-10 offset-md-2">
                                <p  class="text-left text-md-left" >${response[i].Post}</p>
                                </div>
                            </div>
                            <div class="row text-center ">
                                <div class="col-6 comm-box"><a href="#"><i class="fas fa-heart"></i> Like </a></div>
                                <div class="col-6 comm-box"><i class="fas fa-comment"></i> Comment</div>
                            </div>
                            </div>
                                `
    }
 });
function uToTime(t)
{
var dt = new Date(t*1000);
var hr = dt.getHours();
var m = "0" + dt.getMinutes();
var day = dt.getDate()
var month = dt.getMonth();
var year = dt.getFullYear();
switch(month){
    case 0: month = "Jan"; break;
    case 1: month = "Feb"; break;
    case 2: month = "Mar"; break;
    case 3: month = "Apr"; break;
    case 4: month = "May"; break;
    case 5: month = "Jun"; break;
    case 6: month = "Jul"; break;
    case 7: month = "Aug"; break;
    case 8: month = "Sep"; break;
    case 9: month = "Oct"; break;
    case 10: month = "Nov"; break;
    case 11: month = "Dec"; break;
}
return month+ " "+day+" "+ year+" at "+hr+ ':' + m.substr(-2) + ' hrs';  
}

