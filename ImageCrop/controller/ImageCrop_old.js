/********************************
Name:ProfileImageCrop.js
Desc: ProfileImageCrop Controller
Date: 2014-03-26 
Author:Aravind Buddha 
EMail:aravind.buddha@mediaus.com
**********************************/
//=================     as controller/ProfileImageCrop.js 
var ImageCrop = {
  uid: null,
  window_manager: null,
  window: [],
  layout: [],
  grid: [],
  form: [],
  status_bar: [],
  configuration: [],
  _window_manager: function() {
    var self = this;
    self.window_manager = new dhtmlXWindows();
    self.window_manager.enableAutoViewport(false);
    self.window_manager.attachViewportTo(vp);
    self.window_manager.setImagePath(self.model.conf_window.image_path);
  },
  _window: function(uid, conf) {
    var self = this,
      w, h, title;
    conf ? w = conf.width : w = self.model.conf_window.width;
    conf ? h = conf.height : h = self.model.conf_window.height;
    conf ? title = conf.window_title : title = self.model.text_labels.main_window_title;
    if (self.window_manager === null)
      self._window_manager();
    if (self.window_manager.isWindow("window_ProfileImageCrop_" + uid)) {
      self.window[uid].show();
      self.window[uid].bringToTop();
      return;
    }
    self.window[uid] = self.window_manager.createWindow("window_ProfileImageCrop_" + uid, 400, 100, w, h);
    self.window[uid].setText(title);
    self.window[uid].centerOnScreen();
    // self.window[uid].setIcon(self.model.conf_window.icon, self.model.conf_window.icon_dis);
    // self.window[uid].attachEvent("onClose", function(win) {});
    // self.status_bar[uid] = self.window[uid].attachStatusBar();
    /* added to clear uploder on win close without saving*/
    $(window).on("scroll", function() {
      $('img#imgArea').imgAreaSelect({
        hide: true
      });
    });
    self.window[uid].attachEvent("onClose", function(win) {
      var myUploader = self.parentObj.Profile_form_g.getUploader('Avatarphoto');
      $('img#imgArea').imgAreaSelect({
        hide: true
      });
      myUploader.clear();
      return true;
    });
  },
  _grid: function() {

  },
  _layout: function(uid, conf) {
    var self = this;
    conf = conf || self.model.conf_layout.pattern;
    self.layout[uid] = self.window[uid].attachLayout(conf);
  },
  _form: function(uid) {
    var self = this;
    self.form["uplader_form"] = self.layout[self.uid].cells("a").attachForm(self.model.conf_form.template[0].uplader_form);
    self.fuEvents(self.form["uplader_form"]);
  },
  fuEvents: function(uform) {
    var self = this,
      real,
      server, uploader = uform.getUploader("file");
    uform.attachEvent("onBeforeFileAdd", function(realName, size) {

      return true;
    });
    uform.attachEvent("onUploadComplete", function(count) {

      self.imgCroper(server, real);
    });
    uform.attachEvent("onUploadFile", function(realName, serverName) {
      server = serverName;
      real = realName;
    });
    uform.attachEvent("onUploadFail", function(realName) {
      console.log(realName);
    });
  },
  imgCroper: function(server, real) {
    var self = this,
      croplay,
      cropwin;

    server = self.configuration[self.uid].application_path + "uploads/" + server;
    self._window("img_crop_window", self.model.img_crop_window);
    self._layout("img_crop_window", self.model.img_crop_layout.pattern);
    self.window["img_crop_window"].denyMove();
    self.window["img_crop_window"].setText("");
    self.window["img_crop_window"].setModal(true);
    // self.window["img_crop_window"].centerOnScreen();
    // self.window[self.uid].setDimension(self.model.img_crop_window.width, self.model.img_crop_window.height);
    // self.window[self.uid].centerOnScreen();
    //   self.layout[self.uid] = self.window[self.uid].attachLayout(self.model.img_crop_layout.pattern);
    croplay = self.layout["img_crop_window"];
    croplay.cells("a").hideHeader();
    croplay.cells("b").hideHeader();
    croplay.cells("c").hideHeader();
    croplay.cells("a").setWidth(600);
    croplay.cells("a").attachHTMLString('<img id="imgArea" src="' + server + '" />');
    croplay.cells("b").setHeight(380);
    var prevForm = croplay.cells("b").attachForm(self.model.conf_form.template[0].img_crop_form_b);
    croplay.cells("c").attachHTMLString('<h2 class="imgcrop-info">Please select an area on the image.</h2>');
    window.prevForm = prevForm;
    self.imageArea(prevForm, server, real);
    // prevForm.setRequired("pic_name", true);
    prevForm.attachEvent("onValidateError", function(input, value, result) {
      dhtmlx.alert("Please Fill Required Fields");
    });

    prevForm.attachEvent("onButtonClick", function(id) {
      if (id == "crop") {
        prevForm.setItemValue("server", server);
        prevForm.setItemValue("real", real);
        self.ajaxCrop(prevForm);
      }
    });
  },
  imageArea: function(prevForm, server) {
    var imgArea = {
      init: function() {
        this.evetns();
        this.setPrevImg();
      },
      evetns: function() {
        var self = this;
        $("img#imgArea").on("load", function() {
          $(this).imgAreaSelect({
            aspectRatio: '4:3',
            handles: "corners",
            fadeSpeed: 200,
            hide: false,
            // maxWidth: 523,
            // maxHeight: 479,
            minWidth: 200,
            minHeight: 100,
            onSelectChange: self.preview
          });
        });
      },
      setPrevImg: function() {
        prevForm.setItemLabel("preview", '<div id="previewimg"><img  src="' + server + '" /></div>');
      },
      preview: function(img, selection) {
        if (!selection.width || !selection.height)
          return;
        var scaleX = 300 / selection.width;
        var scaleY = 230 / selection.height;
        $('#previewimg img').css({
          width: Math.round(scaleX * img.width),
          height: Math.round(scaleY * img.height),
          marginLeft: -Math.round(scaleX * selection.x1),
          marginTop: -Math.round(scaleY * selection.y1)
        });
        prevForm.setItemValue("x1", selection.x1);
        prevForm.setItemValue("y1", selection.y1);
        prevForm.setItemValue("w", selection.width);
        prevForm.setItemValue("h", selection.height);
      }
    };
    imgArea.init();
  },
  ajaxCrop: function(prevForm) {
    var self = this,
      data = "",
      hash = prevForm.getFormData();
    for (i in hash) {
      data += i + "=" + hash[i] + "&";
    }

    self._window("loader", self.model.progress_window);
    self._layout("loader", self.model.progress_layout.pattern);
    self.layout["loader"].progressOn();
    dhtmlxAjax.post(siteurl + "ProfilebuilderComponent/processors/saveProfilePhoto.php", encodeURI(data), function(loader) {
      self.window["loader"].hide();
      self.layout["loader"].progressOff();
      console.log(loader.xmlDoc.responseText);
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.state) {
          /* added to disable uploader after upload complete*/
          var myUploader = self.parentObj.Profile_form_g.getUploader('Avatarphoto');
          var status = myUploader.getStatus();
          if (status == 1)
            self.parentObj.Profile_form_g.disableItem("Avatarphoto");
          /* added to disable uploader after upload complete*/
          $('img#imgArea').imgAreaSelect({
            hide: true
          });
          //$("[class ^= 'imgareaselect']").remove();
          self.window["img_crop_window"].setModal(false);
          self.window["img_crop_window"].hide();

          dhtmlx.message({
            text: "Saved Successfully"
          });
          var nickname = self.parentObj.dataStore['Profiles'].rows[0].data.pop();
          console.log(self.parentObj);
          dhtmlx.message({
            type: "alert",
            text: 'Congratulations you have saved your new profile picture. To change it please go to Photos section.',
          });


          var poststr = "";
          dhtmlxAjax.post(siteurl + "ImageCrop/processors/profile_status.php", poststr, function(loader) {

            var json = JSON.parse(loader.xmlDoc.responseText);

            var profile_status = json.Profiles_matchstatus.rows;
            var profile_id = json.Profiles_logid.rows;

            var uc_str = profile_status.toLowerCase();

            var poststr = "id=" + profile_id + "&status=" + uc_str
            dhtmlxAjax.post(siteurl + "Agencyprofileviewcomponent/processors/watermark_latest.php", poststr, function(loader) {

            });
          });


        } else {
          dhtmlx.message({
            type: "error",
            text: json.response
          });

        }
      } catch (e) {
        console.log(e);
        dhtmlx.message({
          type: "error",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
      }
    });
  },
  init: function(model) {
    var self = this;
    self.model = model;
  },
  start: function(c) {
    var self = this;
    self.uid = c.uid;
    self.parentObj = c.parent_obj;
    if ((typeof c.uid === 'undefined') || (c.uid.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "uid is missing"
      });
      return;
    }
    if ((typeof c.application_path === 'undefined') || (c.application_path.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "application_path is missing"
      });
      return;
    }
    if ((typeof c.dhtmlx_codebase_path === 'undefined') || (c.dhtmlx_codebase_path.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "dhtmlx_codebase_path is missing"
      });
      return;
    }
    window.dhx_globalImgPath = c.dhtmlx_codebase_path + "imgs/";
    dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";
    c["imgs_path"] = "/imgs/";
    c["icons_path"] = "/imgs/";
    self.configuration[self.uid] = c;
    self.model.conf_window.image_path = c.dhtmlx_codebase_path + c.imgs_path;
    // self.imgCroper(c.server, c.real);

    self._window(self.uid);
    self._grid();
    self._layout(self.uid);
    self._form(self.uid);
  },
}
ProfileImageCrop.init(ProfileImageCrop_Model);