
let post = document.getElementById("posts");
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
       
        response = JSON.parse(xhttp.responseText);
        
            for(let i=response.length; i>=1; i--){
        let day = uToTime(response[i].Time);
        fetch(`like.php?postId=${response[i].Id}&username=${response.user}&liked=0`,{method:'GET'});
        
        post.innerHTML += 
            `
                        <div  id="post-box">
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
                                <div class="col-6 comm-box"><div onclick="like(${i},${response[i].Id},'${response.user}',${response[i].Liked});" id="like${i}"><i class="fas fa-heart"></i> Like (<span id="count${i}">${response[i].LikeCount}</span>)</div></div>
                                <div class="col-6 comm-box"><a href="comments.php?postId=${response[i].Id}"><div><i class="fas fa-comment"></i> Comment (${response[i].CommentCount})</div></a></div>
                            </div>
                            </div>
                    
                                `;
                
                if(response[i].Liked==1){
                        let red = document.getElementById("like"+i).style.color = "red";
                }
    }
    }
};
xhttp.open("GET", "./getposts.php", true);
xhttp.send();

function like(i,id,user,liked){
    if(liked!=1){
    let red = document.getElementById("like"+i).style.color = "red";
    let t = document.getElementById("count"+i).innerText;
    let l = parseInt(t)+1;
    document.getElementById("count"+i).innerText = l ;
    fetch(`like.php?postId=${id}&username=${user}&liked=1`,{method:'GET'});
    }
}
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

