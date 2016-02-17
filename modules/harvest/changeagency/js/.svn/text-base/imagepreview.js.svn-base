// width to resize large images to
var maxWidth=300;
// height to resize large images to
var maxHeight=120;
// valid file types
var fileTypes=["bmp","gif","png","jpg","jpeg"];
// the id of the preview image tag
var outImage="previewImg";
// what to display when the image is not valid
var defaultPic="spacer.gif";
/***** DO NOT EDIT BELOW *****/
      function preview(what){
      var source=what.value;
      var ext=source.substring(source.lastIndexOf(".")+1,source.length).toLowerCase();
      for (var i=0; i<fileTypes.length; i++){
	  if (fileTypes[i]==ext){
	  break;
	  }
	  }
      globalPic=new Image();
      if (i<fileTypes.length){
	  
	  //Obtenemos los datos de la imagen de firefox
	  try{
	  globalPic.src=what.files[0].getAsDataURL();
	  }catch(err){
	  globalPic.src=source;
	  }
	  }else {
      globalPic.src=defaultPic;
      alert("Must be an image file "+fileTypes.join(", "));
      }
      setTimeout("applyChanges()",200);
      }
	  
      var globalPic;
      function applyChanges(){
      var field=document.getElementById(outImage);
      var x=parseInt(globalPic.width);
      var y=parseInt(globalPic.height);
      if (x>maxWidth) {
      y*=maxWidth/x;
      x=maxWidth;
      }
      if (y>maxHeight) {
      x*=maxHeight/y;
      y=maxHeight;
      }
      field.style.display=(x<1 || y<1)?"none":"";
      field.src=globalPic.src;
      field.width=x;
      field.height=y;
      }