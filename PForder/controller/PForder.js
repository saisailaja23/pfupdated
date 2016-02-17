/***********************************************************************
 * Name:    Eswar N
 * Date:    17/07/2014
 * Purpose: Creating a order list page
 ***********************************************************************/
var PForder = {
  uid: null,
  window_manager: null,
  window: [],
  layout: [],
  books_list: [],
  books_selected: [],
  configuration: [],
  _window_manager: function () {
    var self = this;
    self.window_manager = new dhtmlXWindows();
    self.window_manager.setImagePath(self.model.conf_window.image_path);
  },
  _window: function (uid) {
    var self = this;
    if (self.window_manager === null)
      self._window_manager();
    if (self.window_manager.isWindow("window_PForder_" + uid)) {
      self.window[uid].show();
      self.window[uid].bringToTop();
      return;
    }
    self.window[uid] = self.window_manager.createWindow("window_PForder_" + uid, self.model.conf_window.left + 10,
      self.model.conf_window.top + 10, self.model.conf_window.width, self.model.conf_window.height);
    self.window[uid].setText(self.model.text_labels.main_window_title);
    self.window[uid].setIcon(self.model.conf_window.icon, self.model.conf_window.icon_dis);
    self.window[uid].attachEvent("onClose", function (win) {
      return true;
    });
    self.status_bar[uid] = self.window[uid].attachStatusBar();
  },
  _layout: function (uid) {
    var self = this;
    self.layout[uid] = self.window[uid].attachLayout(self.model.conf_layout.pattern);
    self.layout[uid].cells("a").hideHeader();
  },
  dataview: function (uid) {
    var self = this;
    self.books_list = new dhtmlXDataView({
      container: "books_list_cont",
      type: {
        template: "html->books_list_template",
        height: 160,
        width: 400

      }
    });
    self.books_list.load(self.configuration[uid].application_path + "processors/result.php", "json");

    self.books_selected = new dhtmlXDataView({
      container: "books_selected_data",
      type: {
        template: "html->books_selected_template",
        height: 60,
        width: 331,
      },
      height: "auto",
      select: false
    });
    self.books_selected.load(self.configuration[uid].application_path + "processors/selected.php?task=list", "json", function () {
      //callback
      self.total_count(uid);
    });

    self.books_selected.attachEvent("onItemDblClick", function (id, ev, html) {
      var book = self.books_selected.get(id);
      book.item_quantity--;
      if (book.item_quantity == 0) {
        self.books_selected.remove(id);
        dhtmlxAjax.post(self.configuration[uid].application_path + 'processors/selected.php?task=delete&complete=yes&id=' + id, '', '');
      } else {
        self.books_selected.refresh(id);
        dhtmlxAjax.post(self.configuration[uid].application_path + 'processors/selected.php?task=delete&complete=no&id=' + id, '', '');
      }
      self.total_count(uid);
    });
    self.books_list.attachEvent("onItemClick", function (id, ev, html) {
      console.log(id);
      var is_added = self.books_selected.exists(id);
      if (!is_added) {
        dhtmlxAjax.post(self.configuration[uid].application_path + 'processors/selected.php?task=insert&new=yes&id=' + id, '', '');
        self.books_list.copy(id, 0, self.books_selected, id);
        self.books_selected.get(id).item_quantity = 1;
        self.books_selected.refresh();
      } else {
        dhtmlxAjax.post(self.configuration[uid].application_path + 'processors/selected.php?task=insert&new=no&id=' + id, '', '');
        self.books_selected.get(id).item_quantity++;
      }
      self.books_list.select(id);
      self.books_selected.select(id);
      self.books_selected.refresh(id);
      self.total_count(uid);
      return true;
    });
  },
  total_count: function (uid) {
    var self = this;
    var price = 0;
    self.books_selected.data.each(function (book) {
      price += book.item_quantity * book.price;
    });
    document.getElementById("total").innerHTML = price;
    self.featuredCost = price;
  },
  order_selected: function () {
    var self = PForder;
    var ids = [];
    if (self.books_selected.dataCount() > 0) {
      self.books_selected.data.each(function (book) {
        ids.push(book.id + "=" + book.item_quantity);
      });
      var data = {};
      data.memtype = "Featured-New";
      data.cost = self.featuredCost;
      data.user = self.users;
      self.paymentflow(self.uid, data);

    } else {
      dhtmlx.message({
        type: "error",
        text: "Select at least one order"
      });
    }
  },
  clear_selected: function () {
    var self = PForder;
    dhtmlxAjax.post(self.configuration[self.uid].application_path + 'processors/selected.php?task=clear', '', '');
    self.books_selected.clearAll();
    self.total_count();
  },
  _loadData: function (uid) {
    var self = this;
    var postStr = "";
    dhtmlxAjax.post("Member_vocher_validate.php?user=true", postStr, function (loader) {
      self.users = JSON.parse(loader.xmlDoc.responseText);
      self.dataview(uid);
    });
  },

  paymentflow: function (uid, data) {
    var self = this;
    var userlist = data;
    var list = [];
    var payappPath = siteUrl + 'paymentFlow/';
    self.books_selected.data.each(function (book) {
      list.push({
        invoice_item_id: book.id,
        values: [book.itemname, book.description, book.item_quantity * book.price]
      });
    });
    console.log(list);
    PaymentFlow.newFlow({
      invoice_id: (new Date().getTime()), // mandatory, must to be unique
      invoice_pay_for_desc: "Adoption Portal", // mandatory. payment description
      invoice_custom_message: "thank you guy...",
      customer_id: userlist.user.id, // mandatory
      customer_firstName: userlist.user.fname, // mandatory
      customer_lastName: userlist.user.lname, // mandatory
      customer_email: userlist.user.email, // mandatory
      customer_address1: " ", // mandatory
      module: 'Cart', //

      //App settings
      signature_saving_path: payappPath + 'signatures/',
      application_url: payappPath,
      dhtmlx_codebase_path: siteUrl + "plugins/dhtmlx/",
      icons_path: payappPath + 'asserts/icons/',
      imgs_path: payappPath + 'asserts/imgs/',
      pdf_saving_path: payappPath + "/pdfs/",
      //Payment settings
      pay_for_items: list,
      gateways_allowed: [{
        paypal: true
      }, {
        authorize_net: true,
        Visa: true,
        MasterCard: true,
        AmericanExpress: true,
        Discover: true
      }, {
        wire_transfer: false
      }, {
        echeck: false
      }, {
        check: false
      }],
      paymentCallBack: function (res) {
        self.paymentCallBack(res);
      }
    });
  },
  paymentCallBack: function (res) {
    var self = PForder;
    var postStr = res.params;
    if (!res.error) {
      dhtmlxAjax.post(application_path + "processors/orderlist_process.php?", postStr, function (loader) {
        self.clear_selected();
        dhtmlx.alert({
          title: "Alert",
          text: "Payment Process is success. Click ok to continue."
        });
      });
    } else {
      dhtmlxAjax.post(application_path + "processors/orderlist_process.php?", postStr, function (loader) {
        dhtmlx.alert({
          title: "Alert",
          type: "confirm",
          text: res.error.message
        });
      });
    }


  },
  init: function (model) {
    var self = this;
    self.model = model;
  },
  start: function (configuration) {
    var self = this;
    self.uid = configuration.uid;
    if ((typeof configuration.uid === 'undefined') || (configuration.uid.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "uid is missing"
      });
      return;
    }
    if ((typeof configuration.application_path === 'undefined') || (configuration.application_path.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "application_path is missing"
      });
      return;
    }
    if ((typeof configuration.dhtmlx_codebase_path === 'undefined') || (configuration.dhtmlx_codebase_path.length === 0)) {
      dhtmlx.message({
        type: "error",
        text: "dhtmlx_codebase_path is missing"
      });
      return;
    }
    window.dhx_globalImgPath = configuration.dhtmlx_codebase_path + "imgs/";
    dhtmlx.skin = self.model.globalSkin || "dhx_skyblue";
    configuration["icons_path"] = "icons/";
    self.configuration[self.uid] = configuration;
    self.model.conf_window.image_path = configuration.application_path + configuration.icons_path;
    self.model.conf_toolbar.icon_path = configuration.application_path + configuration.icons_path;
    self._loadData(self.uid, function () {
      self._dataview(self.uid);
    });
  }
}
PForder.init(PForder_Model);