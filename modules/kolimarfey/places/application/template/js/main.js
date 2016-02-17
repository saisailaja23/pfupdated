
function placesShowBigImage (sImgUrl) {
    var e = $('#k_photo img');
    e.get(0).style.display = 'none';
    var i = new Image();    
    i.onload = function () {
        e.get(0).src = sImgUrl;   
        e.get(0).style.display = 'inline';    
    }
    i.src = sImgUrl;
}

function placesShowRealImage (e) { 
    var sImgUrl = $(e).children().filter("img").get(0).src;
    sImgUrl = sImgUrl.replace (/\/big\//, '/real/');
    e.href = sImgUrl;
    return true;
}

function placesShowBigVideo (sEmbed) { 
    var e = $('#k_video').get(0);
    e.innerHTML = decodeURIComponent(sEmbed);
    return true;
}
