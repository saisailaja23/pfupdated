	var LikeComp_controller = {
	  model: null,
	  config: null,
	  dataStore: [],
	  events: [],
	  dhxWins: null,
	  start: function (e) {
	    var t = this;
	    this.config = e;

	    this.CheckLogin()
	  },
	  onSelect: function () {
	    var e = this;
	    e.view_alert.attachEvent("onButtonClick", function (t, n) {
	      switch (t) {
	      case "createaccount":
	        e.joinComp();
	        e.callEvent("onOptionSelect", [t]);
	        e.win_join.close();
	        break;
	      case "login":
	        e.LoginComp();
	        e.callEvent("onOptionSelect", [t]);
	        e.win_join.close();
	        break;
	      case "cancel":
	        e.callEvent("onOptionSelect", [t]);
	        e.win_join.close();
	        break
	      }
	    })
	  },
	  joinComp: function () {
	    var e = this;
	    MemberComponent.start({
	      uid: (new Date()).getTime(),
	      siteurl: siteurl,
	      application_path: siteurl + "/MemberComponent/",
	      dhtmlx_codebase_path: siteurl + "/plugins/dhtmlx/"
	    });

	    // JoinComponent.start({
	    //   uid: (new Date).getTime(),
	    //   application_path: siteurl + "JoinComponent/",
	    //   dhtmlx_codebase_path: siteurl + "/plugins/dhtmlx/"
	    // })
	  },
	  LoginComp: function () {
	    var e = this;
	    LoginComponent.start({
	      uid: (new Date).getTime(),
	      application_path: siteurl + "LoginComponent/",
	      dhtmlx_codebase_path: siteurl + "plugins/dhtmlx/"
	    })
	  },
	  getUrlVars: function () {
	    var e = this;
	    var vars = {};
	    if (e.config.like != undefined) {
	      vars['id'] = e.config.like;

	      return vars;
	    }
	    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
	      vars[key] = value;
	    });

	    return vars;
	  },
	  confirmWindow: function () {
	    var e = this;
	    if (!e.dhxWins) e.dhxWins = new dhtmlXWindows();
	    else {
	      return;
	    }
	    //e.dhxWins = new dhtmlXWindows();
	    e.dhxWins.enableAutoViewport(false);
	    e.dhxWins.attachViewportTo(vp);
	    if (!e.dhxWins.window("w1")) {
	      e.win_join = e.dhxWins.createWindow("w1", 440, 80, 500, 180);
	    } else {
	      return;
	    }
	    e.win_join.button("park").hide();
	    e.win_join.button("minmax1").hide();
	    e.win_join.button("minmax2").hide();
	    e.win_join.setText("");

	    e.win_join.setModal(true);
	    $(window).on("scroll", function () {
	      e.win_join.center();
	    });

	    e.view_alert = e.win_join.attachForm(e.model.view_alert_temp);


	    e.win_join.centerOnScreen();
	    var pos = e.win_join.getPosition();
	    var offset = (document.body.scrollTop ? document.body.scrollTop : window.pageYOffset);
	    e.win_join.setPosition(pos[0], pos[1] + offset)

	    // e.changeCss();
	    e.onSelect()
	    e.win_join.attachEvent("onClose", function (win) {
	      $("#vp").css("overflow", "visible");
	      delete e.dhxWins;
	      return true;
	    });
	  },
	  CheckLogin: function () {
	    var e = this;
	    var t = "1-1";
	    dhtmlxAjax.post(siteurl + "LikeComponent/processors/profile_view_information.php", t, function (t) {
	      try {
	        var n = JSON.parse(t.xmlDoc.responseText);
	        if (n.status == "success") {
	          e.dataStore["Profiles_value"] = n.Profiles_value;
	          if (e.dataStore.Profiles_value.rows != 0 && e.dataStore.Profiles_value.profile_typoe == 4) {
	            var r = "userid=" + e.config.like + "&Birthmotherid=obj.profile_id";
	            dhtmlxAjax.post(siteurl + "LikeComponent/processors/insert_like_user.php", r, function (t) {
	              var n = JSON.parse(t.xmlDoc.responseText);
	              if (n.status == "success") {
	                dhtmlx.message({
	                  text: 'Added to "Families I Like" list'
	                })
	                if (e.config.callback != 'undefined' && e.config.callback != null) {
	                  var r = {
	                    statue: "true",
	                    extra: e.config.extra
	                  };

	                  e.config.callback(r);
	                }
	                e.callEvent("onLike", [e.dataStore.Profiles_value.rows])
	              } else {
	                if (e.config.callback != 'undefined' && e.config.callback != null) {

	                  var r = {
	                    statue: "false",
	                    extra: e.config.extra
	                  };

	                  e.config.callback(r);
	                }
	                dhtmlx.message({
	                  type: "error",
	                  text: n.response
	                })
	              }
	            })
	          } else if (e.dataStore.Profiles_value.rows != 0 && e.dataStore.Profiles_value.profile_typoe != 4) {
	            dhtmlx.message({
	              type: "alert-error",
	              text: "Birth mothers can only like this page"
	            })
	          } else {
	            e.confirmWindow();
	          }
	        } else {
	          dhtmlx.message({
	            type: "error",
	            text: n.response
	          })
	        }
	      } catch (i) {
	        dhtmlx.message({
	          type: "error",
	          text: "Fatal error on server side: " + t.xmlDoc.responseText
	        });
	        console.log(i.stack)
	      }
	    })
	  },
	  init: function (e) {
	    var t = this;
	    if (!e) {
	      alert("Model is missing ...");
	      return
	    }
	    t.model = e
	  },
	  attachEvent: function (e, t) {
	    var n = this,
	      r = n.uid;
	    n.events[e] = t
	  },
	  detachEvent: function (e, t) {
	    var n = this,
	      r = n.uid;
	    delete n.events[e];
	    if (typeof t != "undefined") {
	      t()
	    }
	  },
	  callEvent: function (e, t) {
	    var n = this,
	      r = n.uid;
	    if (typeof n.events[e] != "undefined") {
	      n.events[e](t)
	    }
	  },
	  changeCss: function () {
	    //var all = document.getElementsByClassName('dhxform_obj_dhx_skyblue');
	    //for (var i = 0; i < all.length; i++) {
	    //  all[i].style.color = 'red'; 
	    //}
	    //var all = Array.filter( document.getElementsByClassName('dhxform_btn'), function(elem){
	    //    return elem.nodeName == 'DIV';
	    //});
	    //for (var i = 0; i < all.length; i++) {
	    //  all[i].style.color = 'red';
	    //}
	    //all = Array.filter( document.getElementsByClassName('btn_txt'), function(elem){
	    //    return elem.nodeName == 'DIV';
	    //});
	    //for (var i = 0; i < all.length; i++) {
	    //  all[i].style.color = 'red';
	    //}
	    //all = Array.filter( document.getElementsByClassName('btn_m'), function(elem){
	    //    return elem.nodeName == 'td';
	    //});
	    //for (var i = 0; i < all.length; i++) {
	    //  all[i].style.color = 'red';
	    //}
	  }
	};

	LikeComp_controller.init(LikeComp_Modle)