/*--------------------------------------------------------------------------------------------
|	@desc:		live image crop with PHP&jquery
|	@author:	Aravind Buddha
|	@url:		  http://www.techumber.com
|	@date:		16 September 2012
|	@email:   aravind@techumber.com
|	@license:	Free! to Share,copy, distribute and transmit , 
|           but i'll be glad if my name listed in the credits'
---------------------------------------------------------------------------------------------*/

$(function () {
 
  var
    x = 0,
    y = 0,
    w = 0,
    h = 0,
    rw = 302, //preview width;
    rh = 230; //preview height
  //setvalues
  // $('img#imgc').on('load', function() {
  //     alert("hi");
  // });
  //Calling imgAreaSelect plugin
  $('img#imgc').imgAreaSelect({
    handles: false,
    onInit: init,
    onSelectEnd: setValue,
    // onSelectChange: preview,
    aspectRatio: '4:3',
    fadeSpeed: 200,
    minWidth: 100,
    minHeight: 100,
  });

  function init(img, selection) {
    $('.img-loader').hide();
    $('#imgc').css('opacity', 1);
    //$('#preview img').width('100%');
  }
  //setvalue function
  function setValue(img, selection) {
    if (!selection.width || !selection.height)
      return;
    x = selection.x1;
    y = selection.y1;
    w = selection.width;
    h = selection.height;
    $('#x').val(x);
    $('#y').val(y);
    $('#w').val(w);
    $('#h').val(h);
    $('#img').val(img);

  }
  //ajax request get the 
  function getCImage() {
    $("#cropbtn").addClass("disabled").html("croping...");
    alert($("#crop_form").serialize());
    console.log($("#crop_form").serialize());
    $.ajax({
      type: "POST",
      data: $("#crop_form").serialize(),
      url: "process.php",
      cache: false,
      success: function (response) {
        // alert('Congratulations you have saved your Avatar');
        setTimeout(function () {
          parent.$.prettyPhoto.close();
        }, 1000);
        //alert('Photo cropped successfully!');
        //alert("crop success");
        $("#cropbtn").addClass("enable").html('crop');
        // $("#output").html("");
        // $("#cropbtn").removeClass("disabled").html("crop");
        // $("#output").html("<h2>Out put</h2><img src='" + response + "' />");
      },
      error: function () {

        alert("error on ajax");
      },
    });
  }
  //preview function
  function preview(img, selection) {
    if (!selection.width || !selection.height) {
      return;
    }
    var scaleX = rw / selection.width;
    var scaleY = rh / selection.height;
    $('#preview img').css({
      width: Math.round(scaleX * img.width),
      height: Math.round(scaleY * img.height),
      marginLeft: -Math.round(scaleX * selection.x1),
      marginTop: -Math.round(scaleY * selection.y1)
    });
  }

  //will triger on crop button click 
  $("#cropbtn").click(function (e) {
    alert("hi");
    e.preventDefault();
    if (w === 0 || h === 0) {
      alert("Please select an area first!");
      return;
    }
    getCImage();
  });


});