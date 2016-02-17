<div>
     <div>
        <img style="max-width: 600px;" src="testimage.jpg" id="imageArea" />
     </div>
           <!-- Load Feather code -->
     <script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>
           <!-- Instantiate Feather -->
     <script type="text/javascript">
        featherEditor = new Aviary.Feather({
               apiKey: '63a6c8ae9d78e698',
               apiVersion: 3,
               theme: 'light', // Check out our new 'light' and 'dark' themes!
               tools: 'all',
               appendTo: '',
             onSave: function (imageID, newURL) {
                   var img = document.getElementById(imageID);
                   img.src = newURL;
             }
          });
 
          function launchEditor(id, src) {
              featherEditor.launch({
                  image: id,
                  url: src,
                  postUrl: 'http://www.parentfinder.com/ProfileViewComponent/processors/saveEditedImage.php',
                  postData: 12
              });
              return false;
          }
     </script>
     <p>
        <input id="imgbtnEditPhoto" type="image" src="http://advanced.aviary.com/images/feather/edit-photo.png" value="Edit photo" onclick="return launchEditor('imageArea');" />
     </p>
  </div>