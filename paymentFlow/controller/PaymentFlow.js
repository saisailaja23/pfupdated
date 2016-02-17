var PaymentFlow = {
  model: null,
  configuration: [],
  uid: null,
  window_manager: null,
  windows: [],
  status_bar: [],
  toolbar: [],
  form: [],
  grid: [],
  layout: [],
  gateways_allowed: [],
  invoiceID: [],
  customerID: [],
  form_processor: [],
  toolbar_echeck: [],
  toolbar_signature: [],
  tabbar_paypal: [],
  windows_signature: [],
  status_bar_signature: [],
  windows_signature_pre: [],
  layouts_signature_pre: [],
  status_bar_signature_pre: [],
  toolbar_select: [],
  data_view: [],
  windows_printinvoice: [],
  windows_help: [],
  layouts_signature: [],
  formFields: [],
  formFields_tofill: [],
  formFields_filled: [],
  total: [],
  sub_total: [],
  amount: 0,
  total: 0,
  fees: [],
  fee_paypal: [],
  fee_wiretransfer: [],
  fee_check: [],
  fee_echeck: [],
  fee_Visa: [],
  fee_MasterCard: [],
  fee_AmericanExpress: [],
  fee_Discover: [],
  currency: [], // CAD
  currency_prefix: [],
  timezone: [],
  paid_status: [],
  paid_gateway: [],
  paid_transactionID: [],
  paid_date: [],
  paid_token: [],

  signature_file_name: [],
  signature_file_path: [],

  selected_gateway: {
    brand: "",
    /* options */ // Paypal, authorize.net, wire_transfer, check, e - check,
    token: [], // for paypal payments
    transactionID: [], // for paypal payments
    avs_code: [], // for authorize.net payments
    cvv2_response: [], // for authorize.net payments
    cavv_response: [] // for authorize.net payments
  },
  _getSelector: function (id) {
    try {
      return document.getElementById(id);
    } catch (e) {
      return false;
    }
  },
  _window_manager: function () {
    var self = this;
    self.window_manager = new dhtmlXWindows();
    self.window_manager.enableAutoViewport(self.model.conf_window.enableAutoViewport);
    self.window_manager.attachViewportTo(document.body); // self.model.viewport
    self.window_manager.setImagePath(self.model.conf_window.image_path);
  },
  _window: function (c) {
    var self = this,
      uid = self.uid;
    try {
      if (self.window_manager === null) {
        self._window_manager();
      }
      if (self.window_manager.isWindow(uid)) {
        self.windows[uid].show();
        self.windows[uid].bringToTop();
        return;
      }
      self.windows[uid] = self.window_manager.createWindow(uid, self.model.conf_window.left, self.model.conf_window.top, self.model.conf_window.width, self.model.conf_window.height);
      self.windows[uid].setText("Payment - Customer name: " + c.customer_lastName + ", " + c.customer_firstName + ". Customer ID: " + c.customer_id + " | Invoice ID: " + c.invoice_id);
      self.windows[uid].setIcon(self.model.conf_window.icon, self.model.conf_window.icon_dis);
      self.windows[uid].center();
      self.windows[uid].setModal(true);
      self.status_bar[uid] = self.windows[uid].attachStatusBar();
      self.windows[uid].attachEvent("onClose", function (win) {
        self.windows[uid].setModal(false);
        win.hide();
        try {
          var elem = document.getElementById("sign_wrap_" + uid);
          elem.parentNode.removeChild(elem);
          //console.log("removeu");
        } catch (e) {
          //console.log(e.stack);
        }
        //"sign_wrap_"+ uid +"
      });

    } catch (e) {
      //(console) ? //console.log("     error on create window" + "\n" + e.stack) : "";
    }
  },
  _layout: function () {
    var self = this,
      uid = self.uid;
    self.layout[uid] = self.windows[uid].attachLayout(self.model.conf_layout.pattern);
    self.layout[uid].cells("a").hideHeader();
    self.layout[uid].cells("a").setHeight(self.model.conf_layout.height_main_cell);
    self.layout[uid].cells("b").collapse();
    self.layout[uid].cells("b").setText(self.model.conf_layout.text_processor_cell);
  },
  _toolbar: function () {
    var self = this,
      uid = self.uid;
    console.log(self.model.conf_toolbar);
    self.toolbar[uid] = self.windows[uid].attachToolbar(self.model.conf_toolbar);
    self.toolbar[uid].setIconSize(32);
    self.toolbar[uid].attachEvent("onClick", function (id) {

      if (id === "make_payment") {
        self._makePayment(uid);
      } else if (id === "cancel") {
        self._cancelPayment(uid);
      } else if (id === "print") {
        if (self.paid_status[uid] == "Paid") {
          self._printReceipt(uid);
        } else {
          self._printInvoice(uid);
        }
      } else if (id === "print_invoice") {
        self._printInvoice(uid);
      } else if (id === "print_receipt") {
        if (self.paid_status[uid] == "Paid") {
          self._printReceipt(uid);
        } else {
          dhtmlx.alert({
            type: "confirm",
            text: "Not paid. You can not print a receipt."
          });
        }
      } else if (id === "help") {
        self._showHelp(uid);
      }
    });
  },
  _form: function (j, c) {
    var self = this,
      uid = self.uid;
    try {
      self.form[uid] = self.layout[uid].cells("a").attachForm(j);
      self.form[uid].attachEvent("onChange", function (id, value) {
        if (typeof (self.paid_status[uid]) !== 'undefined') {
          if (self.paid_status[uid] == "Paid") {
            dhtmlx.alert({
              type: "confirm",
              text: "You already paid. You can not pay again"
            });
            self.form[uid].uncheckItem("payment_type", self.form[uid].getItemValue("selected_type"));
            return;
          }
        }
        (value === "paypal") ? self._setPayPal(uid, c) : "";
        (value === "wire_transfer") ? self._setWireTransfer(uid, c) : "";
        (value === "e-check") ? self._setECheck(uid, c) : self.layout[uid].cells("b").detachToolbar();
        (value === "check") ? self._setCheck(uid, c) : "";
        self.toolbar[uid].disableItem("make_payment");
        (value === "Visa") ? self._setVisa(uid, c) : "";
        (value === "MasterCard") ? self._setMasterCard(uid, c) : "";
        (value === "American Express") ? self._setAmericanExpress(uid, c) : "";
        (value === "Discover") ? self._setDiscover(uid, c) : "";
      });
      //self._gatewaysDisableAll();
    } catch (e) {
      //(console) ? //console.log("     error on create form" + "\n" + e.stack) : "";
    }
  },
  _grid: function () {
    var self = this,
      uid = self.uid;
    self.grid[uid] = new dhtmlXGridObject(self.form[uid].getContainer(self.model.conf_grid.container));
    self.grid[uid].setHeader(self.model.conf_grid.headers);
    self.grid[uid].setInitWidths(self.model.conf_grid.widths);
    self.grid[uid].setColAlign(self.model.conf_grid.colaligns);
    self.grid[uid].setColTypes(self.model.conf_grid.coltypes);
    self.grid[uid].setColSorting(self.model.conf_grid.colsorting);

    self.grid[uid].init();
  },
  _makePayment: function (uid) {
    var self = this,
      selected_type = self.form[uid].getItemValue("selected_type");
    if (typeof (self.paid_status[uid]) !== 'undefined') {
      if (self.paid_status[uid] == "Paid") {
        dhtmlx.alert({
          type: "confirm",
          text: "You already paid. You can not pay again"
        });
        return;
      }
    }
    switch (selected_type) {
    case "Visa":
      if (self._validateFormProcessor(uid)) {
        if (self._isValidVisa(self.form_processor[uid].getItemValue("card_number"))) {
          self.toolbar[uid].disableItem("make_payment");
          self._processFormCard(uid, selected_type);
        } else {
          self._setFormCardInputInvalid(self.form_processor[uid].getInput("card_number"), uid);
          dhtmlx.message({
            type: "confirm",
            text: "Visa number not valid"
          });
        }
      }
      break;
    case "MasterCard":
      if (self._validateFormProcessor(uid)) {
        if (self._isValidMasterCard(self.form_processor[uid].getItemValue("card_number"))) {
          self.toolbar[uid].disableItem("make_payment");
          self._processFormCard(uid, selected_type);
        } else {
          self._setFormCardInputInvalid(self.form_processor[uid].getInput("card_number"), uid);
          dhtmlx.message({
            type: "confirm",
            text: "MasterCard card number not valid"
          });
        }
      }
      break;
    case "American Express":
      if (self._validateFormProcessor(uid)) {
        if (self._isValidAmericanExpress(self.form_processor[uid].getItemValue("card_number"))) {
          self.toolbar[uid].disableItem("make_payment");
          self._processFormCard(uid, selected_type);
        } else {
          self._setFormCardInputInvalid(self.form_processor[uid].getInput("card_number"), uid);
          dhtmlx.message({
            type: "confirm",
            text: "American Express card number not valid"
          });
        }
      }
      break;
    case "Discover":
      if (self._validateFormProcessor(uid)) {
        if (self._isValidDiscover(self.form_processor[uid].getItemValue("card_number"))) {
          self.toolbar[uid].disableItem("make_payment");
          self._processFormCard(uid, selected_type);
        } else {
          self._setFormCardInputInvalid(self.form_processor[uid].getInput("card_number"), uid);
          dhtmlx.message({
            type: "confirm",
            text: "Discover card number not valid"
          });
        }
      }
      break;
    case "e-check":
      if (self._validateFormProcessor(uid)) {
        self.toolbar[uid].disableItem("make_payment");
        self._processFormEcheck(uid, selected_type);
      }
      break;
    case "check":
      if (self._validateFormProcessor(uid)) {
        self.toolbar[uid].disableItem("make_payment");
        self._processFormCheck(uid, selected_type);
      }
      break;
    case "wire_transfer":
      if (self._validateFormProcessor(uid)) {
        self.toolbar[uid].disableItem("make_payment");
        self._processFormWireTransfer(uid, selected_type);
      }
      break;
    case "":
      dhtmlx.message({
        type: "confirm",
        text: self.model.text_labels.payment_select_type
      });
      break;
    default:
      dhtmlx.message({
        type: "confirm",
        text: self.model.text_labels.not_done
      });
    }
  },
  _showHelp: function (uid) {
    var self = this,
      nrows = 0;
    if (self.window_manager.isWindow("help_window_" + uid)) {
      self.windows_help[uid].show();
      self.windows_help[uid].bringToTop();
      return;
    }
    self.windows_help[uid] = self.window_manager.createWindow("help_window_" + uid, self.model.conf_window.left + 10, self.model.conf_window.top + 10, 700, 400);
    self.windows_help[uid].setText("End user manual");
    self.windows_help[uid].setIcon("help.png", "help_dis.png");
    self.windows_help[uid].attachURL(self.configuration[self.uid].application_url + "docs/end_user_manual/index.html");
  },
  _getPrintPdfParam: function (uid) {
    var self = this,
      params, nrows = 0,
      dt = new Date();

    params = "";
    params = params + "uid=" + uid + "&";
    params = params + "invoice_id=" + self.invoiceID[uid] + "&";
    params = params + "timezone=" + self.timezone[uid] + "&";
    // params = params + "agency_name=" + encodeURIComponent(self.configuration[uid].agency_name) + "&";
    // params = params + "agency_address1=" + encodeURIComponent(self.configuration[uid].agency_address1) + "&";
    // params = params + "agency_city=" + encodeURIComponent(self.configuration[uid].agency_city) + "&";
    // params = params + "agency_state=" + encodeURIComponent(self.configuration[uid].agency_state) + "&";
    // params = params + "agency_zipcode=" + encodeURIComponent(self.configuration[uid].agency_zipcode) + "&";
    // params = params + "agency_country=" + encodeURIComponent(self.configuration[uid].agency_country) + "&";
    // params = params + "agency_phonenumber=" + encodeURIComponent(self.configuration[uid].agency_phonenumber) + "&";
    // params = params + "agency_address2 =" + encodeURIComponent(self.configuration[uid].agency_address2) + "&";
    // params = params + "agency_faxnumber=" + encodeURIComponent(self.configuration[uid].agency_faxnumber) + "&";
    params = params + "customer_firstName=" + encodeURIComponent(self.configuration[uid].customer_firstName) + "&";
    params = params + "customer_lastName=" + encodeURIComponent(self.configuration[uid].customer_lastName) + "&";
    params = params + "customer_email=" + encodeURIComponent(self.configuration[uid].customer_email) + "&";
    params = params + "customer_address1=" + encodeURIComponent(self.configuration[uid].customer_address1) + "&";
    (self.configuration[uid].customer_address2) ? "" : self.configuration[uid].customer_address2 = "";
    (self.configuration[uid].customer_city) ? "" : self.configuration[uid].customer_city = "";
    (self.configuration[uid].customer_state) ? "" : self.configuration[uid].customer_state = "";
    (self.configuration[uid].customer_zipcode) ? "" : self.configuration[uid].customer_zipcode = "";
    (self.configuration[uid].customer_country) ? "" : self.configuration[uid].customer_country = "";
    (self.configuration[uid].customer_phonenumber) ? "" : self.configuration[uid].customer_phonenumber = "";
    (self.configuration[uid].customer_mobilenumber) ? "" : self.configuration[uid].customer_mobilenumber = "";
    params = params + "customer_address2=" + encodeURIComponent(self.configuration[uid].customer_address2) + "&";
    params = params + "customer_city=" + encodeURIComponent(self.configuration[uid].customer_city) + "&";
    params = params + "customer_state=" + encodeURIComponent(self.configuration[uid].customer_state) + "&";
    params = params + "customer_zipcode=" + encodeURIComponent(self.configuration[uid].customer_zipcode) + "&";
    params = params + "customer_country=" + encodeURIComponent(self.configuration[uid].customer_country) + "&";
    params = params + "customer_phonenumber=" + encodeURIComponent(self.configuration[uid].customer_phonenumber) + "&";
    params = params + "customer_mobilenumber=" + encodeURIComponent(self.configuration[uid].customer_mobilenumber) + "&";
    params = params + "terms_and_conditions=" + encodeURIComponent(self.configuration[uid].terms_and_conditions) + "&";
    if (typeof self.configuration[uid].agency_logo !== "undefined") {
      params = params + "agency_logo=" + self.configuration[uid].agency_logo + "&";
    }
    if (isNaN(self.fees[uid])) {
      self.fees[uid] = 0;
    }
    params = params + "currency=" + self.currency[uid] + "&";
    params = params + "pay_for_desc=" + self.configuration[uid].invoice_pay_for_desc + "&";
    params = params + "pdf_saving_path=" + self.configuration[uid].pdf_saving_path + "&";
    params = params + "fees=" + self.amount + "&";
    params = params + "subtotal=" + self.amount + "&";
    params = params + "total=" + self.amount + "&";
    for (var x = 0; x < self.configuration[uid].pay_for_items.length; x++) {
      var product = self.configuration[uid].pay_for_items[x];
      //{invoice_item_id : 1, values : ["ABC 1234 ","ABC 1234 ", "01", "0.05", "0.05"]},
      var item, price, description, price, amount;
      item = encodeURIComponent(product.values[0]);
      description = encodeURIComponent(product.values[1]);
      amount = encodeURIComponent(product.values[2]);
      params = params + "itemsarray[]=" + item + " | " + description + " | " + amount + "&";
      nrows = nrows + 1;
    }
    params = params + "nrows=" + nrows + "&";
    if (typeof self.paid_status[uid] === "undefined") {
      self.paid_status[uid] = "unpaid";
    }
    if (typeof self.paid_transactionID[uid] === "undefined") {
      self.paid_transactionID[uid] = "";
    }
    if (typeof self.paid_token[uid] === "undefined") {
      self.paid_token[uid] = "";
    }
    if (typeof self.paid_gateway[uid] === "undefined" || self.paid_gateway[uid] === "") {
      self.paid_gateway[uid] = "none selected&";
    }

    if (typeof self.paid_date[uid] === "undefined" || self.paid_date[uid] === "") {
      self.paid_date[uid] = "";
    }
    params = params + "paid_date=" + encodeURIComponent((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear()) + "&";
    params = params + "payment_status=" + self.paid_status[uid] + "&";
    params = params + "paid_gateway=" + self.form[uid].getItemValue("selected_type") + "&";
    params = params + "paid_transactionID=" + self.paid_transactionID[uid] + "&";
    params = params + "paid_token=" + self.paid_token[uid] + "&";
    return params;
  },
  _printInvoice: function (uid) {
    var self = this;
    if (self.window_manager.isWindow("print_invoice_" + uid)) {
      self.windows_printinvoice[uid].show();
      self.windows_printinvoice[uid].bringToTop();
      return;
    }
    self.windows_printinvoice[uid] = self.window_manager.createWindow("print_invoice_" + uid, self.model.conf_window.left + 40, self.model.conf_window.top + 40, 700, 400);
    self.windows_printinvoice[uid].setText("Print invoice");
    self.windows_printinvoice[uid].setIcon("print.gif", "print_dis.gif");
    var params = this._getPrintPdfParam(uid);
    self.windows_printinvoice[uid].attachURL(self.configuration[uid].application_url + "processors/html2pdf/processor/invoice.php?" + params);
  },
  _printReceipt: function (uid) {
    var self = this;
    if (self.window_manager.isWindow("print_receipt_" + uid)) {
      self.windows_printinvoice[uid].show();
      self.windows_printinvoice[uid].bringToTop();
      return;
    }
    self.windows_printinvoice[uid] = self.window_manager.createWindow("print_receipt_" + uid, self.model.conf_window.left + 40, self.model.conf_window.top + 40, 700, 400);
    self.windows_printinvoice[uid].setText("Print receipt");
    self.windows_printinvoice[uid].setIcon("print.gif", "print_dis.gif");
    var params = this._getPrintPdfParam(uid);
    console.log(params);
    self.windows_printinvoice[uid].attachURL(self.configuration[uid].application_url + "processors/html2pdf/processor/receipt.php?" + params);
  },
  _cancelPayment: function (uid) {
    var self = this;
    if (typeof (self.paid_status[uid]) !== 'undefined') {
      if (self.paid_status[uid] == "Paid") {
        dhtmlx.alert({
          type: "confirm",
          text: "You already paid. You can not cancel."
        });
        self.form[uid].uncheckItem("payment_type", self.form[uid].getItemValue("selected_type"));
        return;
      }
    }

    if (typeof (self.toolbar_echeck[uid]) !== 'undefined') {
      try {
        self.layout[uid].cells("b").detachToolbar();
        //self.toolbar_echeck[ uid ].unload();
        //self.toolbar_echeck[ uid ] = null;
        //self.toolbar_echeck[ uid ] = false;
      } catch (e) {

      }
    }

    self.layout[uid].cells("b").attachHTMLString("");
    self.form[uid].uncheckItem("payment_type", self.form[uid].getItemValue("selected_type"));
    self.layout[uid].cells("a").expand();
    self.layout[uid].cells("b").collapse();
    self.toolbar[uid].disableItem("make_payment");
    self._callCallBack(self._setObjResponse({
      uid: uid, // unique ID variable. used internally in PaymentFlow component
      action: "cancelled", // paid, cancelled
      status: "cancelled", // success, err, cancelled
      message: "Payment cancelled by customer", // empty, or gateway response
      error: {
        type: "processor error", // server error, processor error
        message: "Payment cancelled by customer" // server error message or gateway error message
      },
      customer_id: self.customerID[uid], // customer ID
      invoice_id: self.invoiceID[uid] // invoice ID
    }));
  },
  _addRow: function (rows) {
    if (!rows) {
      return;
    }
    var self = this,
      uid = self.uid;
    for (var x = 0; x < rows.length; x++) {
      var row = rows[x];
      self.grid[uid].addRow(row.invoice_item_id, [row.values[0], row.values[1], self.currency_prefix[uid] + row.values[2]]); // , row.ind       
      var item_price = row.values[2];
      if (!isNaN(item_price)) {
        console.log(item_price);
      }
      self.grid[uid].setSizes(); // call setSizes to reset sizes of grid elements.
    }
  },
  _setCurrency: function (currency, uid) {
    var self = this;
    if (currency == "USD") {
      self.currency[uid] = "USD";
      self.currency_prefix[uid] = "USD ";
    } else if (currency == "CAD") {
      self.currency[uid] = "CAD";
      self.currency_prefix[uid] = "CAD ";
    } else {
      self.currency[uid] = currency;
      self.currency_prefix[uid] = currency + " ";
    }
  },
  _hide_container: function (container) {
    var self = this,
      uid = self.uid;
    self._getSelector(self.form[uid].getContainer(container).id).parentNode.parentNode.style.display = "none";
  },
  _show_container: function (container) {
    var self = this,
      uid = self.uid;
    self._getSelector(self.form[uid].getContainer(container).id).parentNode.parentNode.style.display = "block";
  },
  _setFormWireTransfer: function (uid, configuration) {
    var self = this,
      combo_countries_receiving, combo_countries_recipient, template = "",
      gateway;
    self.layout[uid].cells("b").attachHTMLString("");
    self.form_processor[uid] = self.layout[uid].cells("b").attachForm(self.model.conf_wire_transfer.template);
    self._getSelector(self.form_processor[uid].getContainer("pay_now").id).innerHTML = "<img src='" + self.configuration[uid].application_url + "imgs/make_payment.png' id='make_payment'  class='make_payment' align='middle'/>";

    template = template + "<div style='padding: 5px;'>";
    template = template + "Bank name: " + self._gatewayGetOption(uid, "wire_transfer").bank_name + " <br />";
    template = template + "ABA number: " + self._gatewayGetOption(uid, "wire_transfer").ABA_number + "<br />";
    template = template + "Account #: " + self._gatewayGetOption(uid, "wire_transfer").account_number + "<br />";
    template = template + "Additional info: " + self._gatewayGetOption(uid, "wire_transfer").additional_info + " <br />";
    template = template + "</div>";

    self._getSelector(self.form_processor[uid].getContainer("agency_information").id).innerHTML = template;
    self.form_processor[uid].getCalendar("billing_datetransfer").hideTime();
    self.formFields[uid] = []; // clean the array of formFields
    self.formFields_tofill[uid] = 0;
    self._setFormFieldsToBind(self.model.conf_wire_transfer.template, uid);
    self._setFormMasks(uid);
    self.form_processor[uid].attachEvent("onChange", function (id, value) {
      self._checkFormStatus(uid);
    });
  },
  _setFormECheck: function (uid, configuration) {
    var self = this;
    self.layout[uid].cells("b").attachHTMLString("");
    self.form_processor[uid] = self.layout[uid].cells("b").attachForm(self.model.conf_form_echeck.template);

    self._setToolbartECheck(uid, configuration);

    (configuration.customer_firstName) ? self.form_processor[uid].setItemValue("billing_name", configuration.customer_firstName) : "";
    (configuration.customer_lastName) ? self.form_processor[uid].setItemValue("billing_name", self.form_processor[uid].getItemValue("billing_name") + " " + configuration.customer_lastName) : "";
    (configuration.customer_address1) ? self.form_processor[uid].setItemValue("billing_address1", configuration.customer_address1) : "";
    //(configuration.customer_address2) ? self.form_processor[ uid ].setItemValue("billing_address2", configuration.customer_address2) : "";
    (configuration.customer_city) ? self.form_processor[uid].setItemValue("billing_city", configuration.customer_city) : "";
    (configuration.customer_state) ? self.form_processor[uid].setItemValue("billing_state", configuration.customer_state) : "";
    (configuration.customer_zipcode) ? self.form_processor[uid].setItemValue("billing_zipcode", configuration.customer_zipcode) : "";
    //(configuration.customer_country) ? combo_countries.selectOption(  combo_countries.getIndexByValue(configuration.customer_country) , false, true) : "";
    (configuration.customer_phonenumber) ? self.form_processor[uid].setItemValue("billing_phonenumber", configuration.customer_phonenumber) : "";
    //(configuration.customer_mobilenumber) ? self.form_processor[ uid ].setItemValue("billing_mobilenumber", configuration.customer_mobilenumber) : "";
    //(configuration.customer_company) ? self.form_processor[ uid ].setItemValue("billing_companyname", configuration.customer_company) : "";
    (configuration.customer_account_number) ? self.form_processor[uid].setItemValue("billing_account_number", configuration.customer_account_number) : "";
    (configuration.customer_routing_number) ? self.form_processor[uid].setItemValue("billing_routingnumber", configuration.customer_routing_number) : "";

    self._getSelector(self.form_processor[uid].getContainer("sign_container").id).innerHTML = "<img src='" + self.configuration[uid].application_url + "imgs/create_signature.png' id='create_payment_signature' class='create_signature' align='middle'/>";
    self._getSelector(self.form_processor[uid].getContainer("pay_now").id).innerHTML = "<img src='" + self.configuration[uid].application_url + "imgs/make_payment.png' id='make_payment' class='make_payment' align='middle'/>";

    self.formFields[uid] = []; // clean the array of formFields

    self.formFields_tofill[uid] = 0;

    self._setFormFieldsToBind(self.model.conf_form_echeck.template, uid);

    self._setFormMasks(uid);

    self.form_processor[uid].attachEvent("onChange", function (id, value) {
      self._checkFormStatus(uid);
    });
  },
  _setFormCheck: function (uid, configuration) {
    var self = this,
      template = "";
    self.layout[uid].cells("b").attachHTMLString("");
    self.form_processor[uid] = self.layout[uid].cells("b").attachForm(self.model.conf_form_check.template);

    self._getSelector(self.form_processor[uid].getContainer("pay_now").id).innerHTML = "<img src='" + self.configuration[uid].application_url + "imgs/make_payment.png' id='make_payment' class='check_button' align='middle'/>";

    self._getSelector(self.form_processor[uid].getContainer("invoice_generate").id).innerHTML = "<img src='" + self.configuration[uid].application_url + "imgs/print_invoice.png' onclick='PaymentFlow._printInvoice( PaymentFlow.uid );' class='check_button' align='middle'/>";

    template = template + "<div style='padding: 10px;'>";

    template = template + self.configuration[uid].agency_name + " <br />";
    template = template + "Attn: " + self.configuration[uid].agency_responsible + "<br />";
    template = template + self.configuration[uid].agency_address1 + "<br />";
    template = template + self.configuration[uid].agency_city + ", " + self.configuration[uid].agency_state + " <br />";
    template = template + "zip code: " + self.configuration[uid].agency_zipcode + " -  " + self.configuration[uid].agency_country + " <br />";


    template = template + "</div>";

    self._getSelector(self.form_processor[uid].getContainer("agency_address").id).innerHTML = template;

    //self.form_processor[ uid ].getCalendar("billing_checkdate").hideTime();


    self.formFields[uid] = []; // clean the array of formFields

    self.formFields_tofill[uid] = 0;

    self._setFormFieldsToBind(self.model.conf_form_check.template, uid);

    self._setFormMasks(uid);

    self.form_processor[uid].attachEvent("onChange", function (id, value) {
      self._checkFormStatus(uid);
    });
  },
  _signatureOpen: function (uid, c) {
    var self = this,
      ieVer, tpl_signature;
    if (self.window_manager.isWindow("signature_expand_" + uid)) {
      self.windows_signature[uid].show();
      self.windows_signature[uid].bringToTop();
      return;
    }
    self.windows_signature[uid] = self.window_manager.createWindow("signature_expand_" + uid, self.model.conf_window.left + 10, self.model.conf_window.top + 10, 700, 400);
    self.windows_signature[uid].setText(self.model.text_labels.sign_window_text);
    self.windows_signature[uid].setIcon(self.model.conf_form_echeck.signature.window.icon, self.model.conf_form_echeck.signature.window.icon_dis);
    self.windows_signature[uid].denyResize();
    var sign_wrap = document.createElement("DIV");
    try {
      sign_wrap.id = "sign_wrap_" + uid + "";
    } catch (e) {
      sign_wrap.setAttribute("id", "sign_wrap_" + uid + "");
    }
    document.body.appendChild(sign_wrap);
    //self._getSelector( self.form_processor[ uid ].getContainer( "signature_container" ).id ).parentNode.parentNode.style.backgroundColor = "#ccc";
    ieVer = getInternetExplorerVersion();
    if (isIE) {
      if (ieVer >= 9.0)
        isIE = false;
    }

    if (isIE) {
      tpl_signature = " \
                <div id='ctlSignature_Container' style='width:680px;height:380px'>  \
                    <div ID='ctlSignature' width='680' height='380'></div>  \
                </div>\
            ";
    } else {
      tpl_signature = " \
                <div id='ctlSignature_Container' style='width:680px;height:380px'>  \
                    <canvas ID='ctlSignature' width='680' height='380'></canvas>    \
                </div>\
        ";
    }

    sign_wrap.innerHTML = tpl_signature;

    //self._getSelector( self.form_processor[ uid ].getContainer( "signature_container" ).id ).innerHTML = tpl_signature;
    //self._getSelector( self.form_processor[ uid ].getContainer( "signature_container" ).id ).style.overflow = "hidden";

    //signObjects = new Array('ctlSignature','ctlSignature2')

    objctlSignature = new SuperSignature({
      SignObject: "ctlSignature",
      SignWidth: "680",
      SignHeight: "380",
      BorderStyle: "Dashed",
      BorderWidth: "1px",
      BorderColor: "#CCCCCC",
      PenColor: "#000000",
      PenSize: 3,
      RequiredPoints: "15",
      ClearImage: self.configuration[uid].application_url + +"signature/refresh.png",
      PenCursor: self.configuration[uid].application_url + self.model.conf_form_echeck.signature.PenCursor || self.configuration[uid].application_url + "signature/pencil.cur",
      BackImageUrl: self.configuration[uid].application_url + self.model.conf_form_echeck.signature.BackImageUrl || "",
      Visible: "true",
      SuccessMessage: "Cool Signature!"
    });
    objctlSignature.Init();

    SignatureStatusBar('ctlSignature', false);

    //self.windows_signature[ uid ].attachObject( "sign_wrap_"+ uid +"" );

    self.layouts_signature[uid] = self.windows_signature[uid].attachLayout("1C");
    //self.layouts_signature[ uid ].cells("a").hideHeader();
    self.layouts_signature[uid].cells("a").setText(c.customer_firstName + " " + c.customer_lastName + " please create your signature");
    self.layouts_signature[uid].cells("a").attachObject("sign_wrap_" + uid + "");

    self.windows_signature[uid].attachEvent("onClose", function (win) {
      if (self.form_processor[uid].getItemValue("signature_saved") == "unsaved") {
        dhtmlx.message({
          title: "close without saving",
          type: "confirm-error",
          text: "Do you really want to close without saving the signature?",
          callback: function (ok) {
            if (ok) {
              win.hide();
            } else {
              win.show();
              win.bringToTop();
            }
          }
        });
      } else {
        win.hide();
      }
      return false;
    });

    self.status_bar_signature[uid] = self.windows_signature[uid].attachStatusBar();
    self.status_bar_signature[uid].setText("Draw your signature and press 'Save signature' when completed");

    self._setToolbartSign(uid, c);
  },
  _signatureSave: function (uid, c) {
    var self = this;
    self.toolbar_signature[uid].disableItem("signature_save");
    self.windows_signature[uid].progressOn();
    self.layouts_signature[uid].cells("a").progressOn();
    self.layouts_signature[uid].cells("a").setText("Saving signature");
    var image_base64data = self._getSelector("ctlSignature_data").value;
    var image_file_name = c.customer_firstName + "_" + self.customerID[uid] + "_signature_" + self.invoiceID[uid] + ".jpg";
    var params = "ctlSignature_data=" + escape(image_base64data) + "";
    params = params + "&ctlSignature_file=" + encodeURI(image_file_name) + "";
    params = params + "&saving_image_path=" + c.signature_saving_path + "";
    dhtmlxAjax.post(c.application_url + "signature/super-signature.php", params, function (loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self.form_processor[uid].setItemValue("signature_saved", "saved");
          self.form_processor[uid].setItemValue("signature_image_name_file", image_file_name);
          dhtmlx.message({
            text: "Signature saved"
          });
          self.layouts_signature[uid].cells("a").setText("Signature saved");
          self._getSelector(self.form_processor[uid].getContainer("sign_container").id).innerHTML = "";
          self._getSelector(self.form_processor[uid].getContainer("sign_container").id).innerHTML = "<img src='" + c.file_path + image_file_name + "?id=" + (new Date).getTime() + "' width='300' align='middle'/>";

          self.signature_file_name[uid] = image_file_name;
          self.signature_file_path[uid] = c.signature_saving_path;


          var close = window.setTimeout(function () {
            self.layouts_signature[uid].cells("a").setText("Closing window in 2 seconds.");
          }, 1000);
          close = window.setTimeout(function () {
            self.layouts_signature[uid].cells("a").setText("Document signed");
            self.windows_signature[uid].hide();
          }, 3000);
          self.toolbar_signature[uid].enableItem("signature_save");
          self._checkFormStatus(uid);
          self.windows_signature[uid].progressOff();
          self.layouts_signature[uid].cells("a").progressOff();
        } else {
          dhtmlx.message({
            type: "confirm",
            text: json.response
          });
          self.layouts_signature[uid].cells("a").setText(json.response);
          self.toolbar_signature[uid].enableItem("signature_save");
          self.windows_signature[uid].progressOff();
          self.layouts_signature[uid].cells("a").progressOff();
        }
      } catch (e) {
        dhtmlx.message({
          type: "confirm",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
        self.layouts_signature[uid].cells("a").setText("Fatal error on server side");
        self.toolbar_signature[uid].enableItem("signature_save");
        self.windows_signature[uid].progressOff();

        self.layouts_signature[uid].cells("a").progressOff();
      }
    });
  },
  _selectPreCreatedSignature: function (uid, imagename) {
    var self = this;
    self.form_processor[uid].setItemValue("signature_saved", "saved");
    self.form_processor[uid].setItemValue("signature_image_name_file", imagename);
    dhtmlx.message({
      text: "Signature saved"
    });
    self.layouts_signature[uid].cells("a").setText("Signature saved");
    self._getSelector(self.form_processor[uid].getContainer("sign_container").id).innerHTML = "";
    self._getSelector(self.form_processor[uid].getContainer("sign_container").id).innerHTML = "<img src='" + self.configuration[uid].file_path + imagename + "?id=" + (new Date).getTime() + "' width='300' align='middle'/>";

    self.signature_file_name[uid] = imagename;
    self.signature_file_path[uid] = self.configuration[uid].signature_saving_path;

    /*======== ========*/
    self.toolbar_select[uid].disableItem("signature_select");
    //self.signed[ uid ] = true;
    self.windows_signature_pre[uid].hide();

    /*======== ========*/


    var close = window.setTimeout(function () {
      self.layouts_signature[uid].cells("a").setText("Closing window in 2 seconds.");

      close = window.setTimeout(function () {
        self.layouts_signature[uid].cells("a").setText("Document signed by " + self.configuration[uid].customer_firstName + " " + self.configuration[uid].customer_lastName);
        self.windows_signature[uid].hide();
      }, 2000);

    }, 1000);

    self.toolbar_signature[uid].enableItem("signature_save");

    self._checkFormStatus(uid);

    self.windows_signature[uid].progressOff();
    self.layouts_signature[uid].cells("a").progressOff();
  },
  _setToolbartSign: function (uid, c) {
    var self = this;
    self.toolbar_signature[uid] = self.windows_signature[uid].attachToolbar(self.model.conf_toolbar_pleasesign);
    self.toolbar_signature[uid].attachEvent("onClick", function (id) {
      if (id == "signature_save") {
        if (ValidateSignature('ctlSignature')) {
          self._signatureSave(uid, c);
        } else {
          dhtmlx.message({
            type: "confirm",
            text: self.model.text_labels.sign_not_valid_again
          }); //    
        }
      } else if (id == "signature_select_pre") {

        self._showPreCreateSignatures(uid);
      } else if (id == "signature_validate") {
        if (ValidateSignature('ctlSignature')) {
          dhtmlx.message({
            text: self.model.text_labels.sign_valid
          }); //    
        } else {
          dhtmlx.message({
            type: "confirm",
            text: self.model.text_labels.sign_not_valid
          }); //    
        }
      } else if (id == "signature_clear") {
        ClearSignature('ctlSignature');
        self.layouts_signature[uid].cells("a").setText(c.customer_firstName + " " + c.customer_lastName + " please create your signature");
      } else if (id == "signature_color_red") {
        SignatureColor('ctlSignature', '#D24747'); // set pen color
      } else if (id == "signature_color_blue") {
        SignatureColor('ctlSignature', '#4754d2'); // set pen color
      } else if (id == "signature_color_black") {
        SignatureColor('ctlSignature', '#000000'); // set pen color
      } else if (id.indexOf("signature_pen_") != 1) {
        var pen_width = id.split("signature_pen_")[1];
        SignaturePen('ctlSignature', pen_width); // set pen width
      }
    });
  },
  _showPreCreateSignatures: function (uid) {
    var self = this,
      nrows = 0;

    if (self.window_manager.isWindow("pre_created_signatures_window_" + uid)) {
      self.windows_signature_pre[uid].show();
      self.windows_signature_pre[uid].bringToTop();
      return;
    }


    self.windows_signature_pre[uid] = self.window_manager.createWindow("pre_created_signatures_window_" + uid, self.model.conf_window.left + 10, self.model.conf_window.top + 100, 500, 400);
    self.windows_signature_pre[uid].setText("Pre created signatures.");
    self.windows_signature_pre[uid].setIcon("open_file.png", "open_file_dis.png");

    self.windows_signature_pre[uid].attachEvent("onClose", function (win) {
      win.hide();
    });

    self._setToolbartSelectSignature(uid);


    self.layouts_signature_pre[uid] = self.windows_signature_pre[uid].attachLayout("1C");
    self.layouts_signature_pre[uid].setImagePath(self.model.globalImgPath);
    self.layouts_signature_pre[uid].cells("a").setText("Please select you signature. Give double click on the desired signature image");

    self.status_bar_signature_pre[uid] = self.windows_signature_pre[uid].attachStatusBar();
    self.status_bar_signature_pre[uid].setText("Please select you signature. Give double click on the desired signature image.");

    self.windows_signature_pre[uid].progressOn();
    self.layouts_signature_pre[uid].cells("a").progressOn();

    //self.layouts_signature_pre[ uid ].cells("a").attachHTMLString('<div id="pre_sign_wrap_'+ uid +'" style="border:1px solid #A4BED4;width:auto;height:278px;"></div>');      

    self.data_view[uid] = self.layouts_signature_pre[uid].cells("a").attachDataView({
      type: {
        template: "<img src='#web_path##name#' height='100' />",
        height: 100
      },
      drag: true
    });

    self.data_view[uid].attachEvent("onItemClick", function (id, context, e) {
      self.toolbar_select[uid].enableItem("signature_select");
    });


    self.data_view[uid].attachEvent("onItemDblClick", function (id, context, e) {
      self._selectPreCreatedSignature(uid, id);

      //_signatureSave
    });

    self.data_view[uid].attachEvent("onAfterDrop", function (id, context, e) {

      //console.log(context.target);
      return false; //block default processing
    });

    var caller_name = self.configuration[uid].customer_firstName + " " + self.configuration[uid].customer_lastName;
    var holder_name = self.form_processor[uid].getItemValue("billing_name");

    self.configuration[uid].file_path = self.configuration[uid].file_path.replace(new RegExp(caller_name, "g"), holder_name);
    self.configuration[uid].signature_saving_path = self.configuration[uid].signature_saving_path.replace(new RegExp(caller_name, "g"), holder_name);


    var params = "web_path=" + encodeURI(self.configuration[uid].file_path) + "";
    params = params + "&file_path_abs=" + encodeURI(self.configuration[uid].signature_saving_path) + "";
    params = params + "&user_name=" + encodeURI(holder_name) + "";

    //console.log( self.configuration[ uid ].file_path );
    //console.log( self.configuration[ uid ].signature_saving_path );
    //console.log( holder_name );

    dhtmlxAjax.post(self.configuration[uid].application_url + "processors/read_signatures.pl", params, function (loader) {
      try {
        var json = eval('(' + loader.xmlDoc.responseText + ')');


        if (json.status == "err") {
          dhtmlx.message({
            type: "confirm",
            text: json.response
          });
        } else {
          self.data_view[uid].parse(json, "json");

          self.windows_signature_pre[uid].progressOff();
          self.layouts_signature_pre[uid].cells("a").progressOff();
        }


      } catch (e) {
        dhtmlx.message({
          type: "confirm",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
        self.layouts_signature_pre[uid].cells("a").setText("Fatal error on server side");

        self.windows_signature_pre[uid].progressOff();
        self.layouts_signature_pre[uid].cells("a").progressOff();
      }
    });
  },
  _setToolbartSelectSignature: function (uid) {
    var self = this;
    self.toolbar_select[uid] = self.windows_signature_pre[uid].attachToolbar(self.model.conf_toolbar_select);
    self.toolbar_select[uid].attachEvent("onClick", function (id) {
      if (id == "signature_select") {
        self._selectPreCreatedSignature(uid, self.data_view[uid].getSelected());
      }
    });
  },
  _setToolbartECheck: function (uid, c) {
    var self = this;
    //console.log(uid);
    self.toolbar_echeck[uid] = self.layout[uid].cells("b").attachToolbar(self.model.conf_toolbar_signature);
    self.toolbar_echeck[uid].attachEvent("onClick", function (id) {
      if (id == "signature_expand") {
        self._signatureOpen(uid, c);
      }
    });
  },
  _setFormCard: function (uid, configuration) {
    var self = this;

    self.layout[uid].cells("b").attachHTMLString("");
    self.form_processor[uid] = self.layout[uid].cells("b").attachForm(self.model.conf_form_card.template);

    var combo_countries = self.form_processor[uid].getCombo("billing_country");
    for (country in countries) {
      combo_countries.addOption(country, countries[country]);
    }
    combo_countries.enableFilteringMode(true, null, false, true);

    (configuration.customer_firstName) ? self.form_processor[uid].setItemValue("card_firstname", configuration.customer_firstName) : "";
    (configuration.customer_firstName) ? self.form_processor[uid].setItemValue("billing_firstname", configuration.customer_firstName) : "";
    (configuration.customer_lastName) ? self.form_processor[uid].setItemValue("card_lastname", configuration.customer_lastName) : "";
    (configuration.customer_lastName) ? self.form_processor[uid].setItemValue("billing_lastname", configuration.customer_lastName) : "";
    (configuration.customer_address1) ? self.form_processor[uid].setItemValue("billing_address1", configuration.customer_address1) : "";
    (configuration.customer_address2) ? self.form_processor[uid].setItemValue("billing_address2", configuration.customer_address2) : "";
    (configuration.customer_city) ? self.form_processor[uid].setItemValue("billing_city", configuration.customer_city) : "";
    (configuration.customer_state) ? self.form_processor[uid].setItemValue("billing_state", configuration.customer_state) : "";
    (configuration.customer_zipcode) ? self.form_processor[uid].setItemValue("billing_zipcode", configuration.customer_zipcode) : "";
    (configuration.customer_country) ? combo_countries.selectOption(combo_countries.getIndexByValue(configuration.customer_country), false, true) : "";
    (configuration.customer_phonenumber) ? self.form_processor[uid].setItemValue("billing_phonenumber", configuration.customer_phonenumber) : "";
    (configuration.customer_mobilenumber) ? self.form_processor[uid].setItemValue("billing_mobilenumber", configuration.customer_mobilenumber) : "";
    (configuration.customer_company) ? self.form_processor[uid].setItemValue("billing_companyname", configuration.customer_company) : "";

    (configuration.invoice_pay_for_desc) ? self.form_processor[uid].setItemValue("pay_for_desc", configuration.invoice_pay_for_desc) : "";

    self.formFields[uid] = []; // clean the array of formFields

    self.formFields_tofill[uid] = 0;

    self._setFormFieldsToBind(self.model.conf_form_card.template, uid);
    self._setFormMasks(uid);
    self.form_processor[uid].attachEvent("onChange", function (id, value) {
      self._checkFormStatus(uid);
    });
  },
  _setFormFieldsToBind: function (json, uid, appended_on_the_fly) {
    var self = this;
    // iterates over all items of the form's JSON
    for (var x = 0; x < json.length; x++) {
      var formField = json[x];
      // catch the type of the item
      var type = formField.type;
      // if the item has one of each following type, we'll discard it
      if (type == "newcolumn" || type == "settings" || type == "button" || type == "fieldset") {
        continue; // discard the item
      }

      // if the item has a "block" type, we need to catch the items inside of the list property of the block
      if (type == "block") {
        if (appended_on_the_fly) {
          self._setFormFieldsToBind(formField.list, uid, true); // use this same function to catch the items inside of the list
        } else {
          self._setFormFieldsToBind(formField.list, uid); // use this same function to catch the items inside of the list
        }

      } else if (type == "label") {
        if (formField.list) {
          if (appended_on_the_fly) {
            self._setFormFieldsToBind(formField.list, uid, true); // use this same function to catch the items inside of the list
          } else {
            self._setFormFieldsToBind(formField.list, uid); // use this same function to catch the items inside of the list
          }
        }
      }
      // if not, we push the formfield into the self.formFields[ uid ] array
      else {
        if (!self.formFields[uid]) {
          self.formFields[uid] = [];
        }

        if (appended_on_the_fly) {
          self.formFields[uid].unshift(formField);
          //console.log("unshift")
        } else {
          self.formFields[uid].push(formField);
          //console.log("push")
        }

        self.formFields_tofill[uid] = self.formFields_tofill[uid] + 1;

        //formFields_tofill[]

        //console.log(self.formFields_tofill[uid]);
      }
    }
  },
  _getFormCardItem: function (name, uid) {
    var self = this;

    //console.log(uid);

    //console.log(self.formFields[ uid ]);

    if (self.formFields[uid] === undefined) {
      return false;
    }

    for (var x = 0; x < self.formFields[uid].length; x++) {
      var field = self.formFields[uid][x];
      if (field.name == name) {
        return field;
      }
    }
    return false;
  },
  _validateFormProcessor: function (uid) {
    var self = this,
      hash;
    hash = self.form_processor[uid].getFormData();
    for (fieldname in hash) {
      //console.log(self.form_processor[ uid ].getForm())
      // check if the item has a name. Lets assume that all the fields which should be validate has a name

      var field = self._getFormCardItem(fieldname, uid);

      if (!field) {
        continue;
      }

      if (field.name) {
        var name, type, value, validate, label;
        name = field.name;
        type = field.type || "";
        label = field.label || "";
        value = hash[fieldname] || "";
        validate = field.validate || "";
        //console.log(validate);
        //==== DO the validations 
        // if the value is not valid, the function will returns, terminating the execution      
        //==== NotEmpty validation
        var NotEmpty = validate.toString().match("NotEmpty");
        if (NotEmpty == "NotEmpty") {
          // if the value have not a lenght > 0
          if (!value.toString().length > 0) {
            self._setFormCardInputHighlighted(field, uid);
            dhtmlx.message({
              type: "confirm",
              text: self.model.text_labels.validation_notEmpty(label)
            }); // 
            return;
          }
        }

        var Empty = validate.toString().match("Empty");
        if (Empty == "Empty" && NotEmpty != "NotEmpty") {
          // if the value have not a lenght > 0
          if (value.toString().length > 0) {
            self._setFormCardInputHighlighted(field, uid);

            dhtmlx.message({
              type: "confirm",
              text: self.model.text_labels.validation_Empty(label)
            });
            return;
          }
        }

        var ValidEmail = validate.toString().match("ValidEmail");
        if (ValidEmail == "ValidEmail") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (!/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(value)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidEmail(label)
              });
              return;
            }
          }
        }

        var ValidInteger = validate.toString().match("ValidInteger");
        if (ValidInteger == "ValidInteger") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (!value.match(/^\d+$/)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidInteger(label)
              });
              return;
            }
          }
        }

        var ValidFloat = validate.toString().match("ValidFloat");
        if (ValidFloat == "ValidFloat") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (!value.match(/^\d+\.\d+$/)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidFloat(label)
              });
              return;
            }
          }
        }

        var ValidNumeric = validate.toString().match("ValidNumeric");
        if (ValidNumeric == "ValidNumeric") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (isNaN(value)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidNumeric(label)
              });
              return;
            }
          }
        }

        var ValidAplhaNumeric = validate.toString().match("ValidAplhaNumeric");
        if (ValidAplhaNumeric == "ValidAplhaNumeric") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (!value.match(/^[0-9a-z]+$/)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidAplhaNumeric(label)
              });
              return;
            }
          }
        }

        var ValidDatetime = validate.toString().match("ValidDatetime");
        if (ValidDatetime == "ValidDatetime") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (isNaN(Date.parse(value))) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidDatetime(label)
              });
              return;
            }
          }
        }

        var ValidDate = validate.toString().match("ValidDate");
        if (ValidDate == "ValidDate") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (isNaN(Date.parse(value))) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidDate(label)
              });
              return;
            }
          }
        }

        var ValidTime = validate.toString().match("ValidTime");
        if (ValidTime == "ValidTime") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            var matchArray = value.match(/^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/);
            if (matchArray == null) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidTime(label)
              });
              return;
            }

            if (value.toString().toLowerCase().match("am") == "am" || value.toString().toLowerCase().match("pm") == "pm") {
              if (value.split(":")[0] > 12 || (value.split(":")[1]).split(" ")[0] > 59) {
                self._setFormCardInputHighlighted(field, uid);
                dhtmlx.message({
                  type: "confirm",
                  text: self.model.text_labels.validation_ValidTime(label)
                });
                return;
              }
            } else {
              if (value.split(":")[0] > 23 || value.split(":")[1] > 59) {
                self._setFormCardInputHighlighted(field, uid);
                dhtmlx.message({
                  type: "confirm",
                  text: self.model.text_labels.validation_ValidTime(label)
                });
                return;
              }
            }

          }
        }

        var ValidCurrency = validate.toString().match("ValidCurrency");
        if (ValidCurrency == "ValidCurrency") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (!/^\d+(?:\.\d{0,2})$/.test(value)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidCurrency(label)
              });
              return;
            }
          }
        }

        var ValidSSN = validate.toString().match("ValidSSN");
        if (ValidSSN == "ValidSSN") {
          // if the value have not a lenght > 0
          if (value.length > 0) {
            if (!value.match(/^\d{3}-\d{2}-\d{4}$/)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidSSN(label)
              });
              return;
            }
          }
        }


        var ValidExpirationdate = validate.toString().match("ValidExpirationdate");
        if (ValidExpirationdate == "ValidExpirationdate") {
          // if the value have not a lenght > 0  00/00
          if (value.length > 0) {
            if (value.length != 5) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidExpirationdate(label)
              });
              return;
            } else {
              var month = value.split("/")[0];
              var year = value.split("/")[1];

              if (isNaN(month) || isNaN(year)) {
                self._setFormCardInputHighlighted(field, uid);
                dhtmlx.message({
                  type: "confirm",
                  text: self.model.text_labels.validation_ValidExpirationdate(label)
                });
                return;
              }

              if (!(month > 0 && month < 13)) {
                self._setFormCardInputHighlighted(field, uid);
                dhtmlx.message({
                  type: "confirm",
                  text: self.model.text_labels.validation_ValidExpirationdate(label)
                });
                return;
              }

              if (!(year > 0 && year < 99)) {
                self._setFormCardInputHighlighted(field, uid);
                dhtmlx.message({
                  type: "confirm",
                  text: self.model.text_labels.validation_ValidExpirationdate(label)
                });
                return;
              }
            }
          }
        }
        var ValidAlphaChar = validate.toString().match("ValidAlphaChar");
        if (ValidAlphaChar == "ValidAlphaChar") {
          if (value.length > 0) {
            if (!value.match(/^[a-zA-Z-& ]*$/)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidAlphaChar(label)
              });
              return;
            }
          } else {
            self._setFormCardInputHighlighted(field, uid);
            dhtmlx.message({
              type: "confirm",
              text: self.model.text_labels.validation_notEmpty(label)
            });
            return;
          }
        }
        var ValidZIP = validate.toString().match("ValidZIP");
        if (ValidZIP == "ValidZIP") {
          if (value.length > 0) {
            if (!value.match(/^[0-9]{0,5}$/)) {
              self._setFormCardInputHighlighted(field, uid);
              dhtmlx.message({
                type: "confirm",
                text: self.model.text_labels.validation_ValidZIP(label)
              });
              return;
            }
          } else {
            self._setFormCardInputHighlighted(field, uid);
            dhtmlx.message({
              type: "confirm",
              text: self.model.text_labels.validation_ValidZIP(label)
            });
            return;
          }
        }

      } // end if have name
    } // end for
    return true;
  },
  _setFormCardInputInvalid: function (objInput) {
    objInput.style.backgroundColor = "#fdafa3";

    objInput.focus();

    objInput.onclick = function () {
      objInput.style.backgroundColor = "#fff";
    }
    objInput.onchange = function () {
      objInput.style.backgroundColor = "#fff";
    }
    objInput.onkeydown = function () {
      objInput.style.backgroundColor = "#fff";
    }
  },
  _setFormCardInputHighlighted: function (field, uid) {
    //console.log( self.form_processor[ uid ].getForm() )
    var self = this;

    var name = field.name;
    var type = field.type;

    var associated_label = field.associated_label || false;
    // these if / else is just for highlightning the formfield which should be filled
    if (type == "combo") {
      var fcombo = self.form_processor[uid].getCombo(name);
      fcombo.openSelect();
    } else if (type == "editor") {
      //var feditor = self.form_processor[ uid ].getEditor(name);
    } else if (type == "multiselect") {
      var finput = self.form_processor[uid].getSelect(name);
      self._setFormCardInputInvalid(finput, uid);
    } else if (type == "select") {
      var finput = self.form_processor[uid].getSelect(name);
      self._setFormCardInputInvalid(finput, uid);
    } else {
      var finput = self.form_processor[uid].getInput(name);
      self._setFormCardInputInvalid(finput);
    }
  },
  _checkFormStatus: function (uid) {
    var self = this;
    var hash = self.form_processor[uid].getFormData();
    for (fieldname in hash) {
      if (hash[fieldname] == "" || hash[fieldname] == null) {
        //console.log( fieldname );
        return;
      }
    }
    if (self.form[uid].getItemValue("selected_type") === 'e-check') {
      if (self.form_processor[uid].getItemValue("signature_saved") === 'unsaved') {
        //console.log( self.form_processor[ uid ].getItemValue("signature_saved") );
        return;
      }
    }
    self.toolbar[uid].enableItem("make_payment");
    dhtmlx.alert({
      text: "To complete the transaction select 'Make Payment' button located at the top of this window"
    });
    self._setOverButton("make_payment.png");
  },
  _setFormMasks: function (uid) {
    var self = this;

    //console.log(self.formFields[ uid ]);

    for (var x = 0; x < self.formFields[uid].length; x++) {
      var field = self.formFields[uid][x];
      // check if the item has a name. Lets assume that all the fields which should be validate has a name
      if (field.name) {
        var mask_to_use, name, type;
        mask_to_use = field.mask_to_use || "";
        type = field.type || "";
        name = field.name || "";
        //formFields_filled
        if (mask_to_use == "currency") {
          var id = null;
          try {
            id = self.form_processor[uid].getInput(name).id;
          } catch (e) {
            id = self.form_processor[uid].getInput(name).getAttribute("id");
          }
          $("#" + id).priceFormat({
            prefix: 'USD '
          });

        } else if (mask_to_use == "can_currency") {
          var id = null;
          try {
            id = self.form_processor[uid].getInput(name).id;
          } catch (e) {
            id = self.form_processor[uid].getInput(name).getAttribute("id");
          }
          $("#" + id).priceFormat({
            prefix: 'CAN '
          });
        } else if (mask_to_use == "integer") {
          self.form_processor[uid].getInput(name).onkeydown = function (event) {
            only_integer(this);
          };
        } else if (mask_to_use == "us_phone") {
          self.form_processor[uid].getInput(name).onkeypress = function (event) {
            phone_mask(this);
          };
          self.form_processor[uid].getInput(name).maxLength = "13";
        } else if (mask_to_use == "expiration_date") {
          self.form_processor[uid].getInput(name).onkeypress = function (event) {
            expiration_date(this);
          };
          self.form_processor[uid].getInput(name).maxLength = "5";
        } else if (mask_to_use == "cvv") {
          self.form_processor[uid].getInput(name).onkeydown = function (event) {
            only_integer(this);
          };
          self.form_processor[uid].getInput(name).maxLength = "4";
        } else if (mask_to_use == "credit_card") {
          self.form_processor[uid].getInput(name).onkeydown = function (event) {
            only_integer(this);
          };
          self.form_processor[uid].getInput(name).maxLength = "16";
        } else if (mask_to_use == "time") {
          //console.log("time mask")
          self.form_processor[uid].getInput(name).onkeydown = function (event) {
            time_mask(this, event);
          };
          self.form_processor[uid].getInput(name).maxLength = "8";
        } else if (mask_to_use == "SSN") {
          self.form_processor[uid].getInput(name).onkeypress = function (event) {
            ssn_mask(this);
          };
          self.form_processor[uid].getInput(name).maxLength = "11";
        }

      } // END - check if the item has a name. 

    } // END FOR
  },
  _setOverButton: function (regex_identifier) {

    var divs = document.getElementsByTagName("div");
    for (var x = 0; x < divs.length; x++) {
      if (divs[x].className == "dhx_toolbar_btn def") // dhx_toolbar_btn def  dhx_toolbar_btn dis
      {
        if (divs[x].innerHTML.match(new RegExp(regex_identifier, 'g')))
          divs[x].className = "dhx_toolbar_btn over";
        //return;
      } else if (divs[x].className == "dhx_toolbar_btn dis") // dhx_toolbar_btn def  dhx_toolbar_btn dis
      {
        //console.log(regex_identifier);
        if (divs[x].innerHTML.match(new RegExp(regex_identifier, 'g')))
          divs[x].className = "dhx_toolbar_btn over";
        //return;
      }
    }
  },
  processPaypal: function (json) {
    var self = this;
    self.layout[this.uid].cells("a").expand();
    self.layout[this.uid].cells("b").collapse();
    if (json.status == "success") {
      dhtmlx.message({
        text: "Payment Successful with paypal.."
      });
      self._callCallBack(self._setObjResponse({
        uid: self.uid, // mandatory
        action: "paid", // mandatory paid,   cancelled
        status: json.status, // mandatory - success, err, cancelled
        message: json.response, // not mandatory, default empty ""
        date: json.date,
        time: json.time
      }));
      self._progressOff(self.uid);
    } else {
      self._callCallBack(self._setObjResponse({
        uid: self.uid, // mandatory
        action: "cancelled", // mandatory paid, cancelled
        status: json.status, // mandatory - success, err, cancelled
        message: json.response, // not mandatory, default empty ""
        error: {
          type: "processor error", // server error, processor error
          message: json.response // line of error   
        },
        date: json.date,
        time: json.time
      }));
      self._progressOff(self.uid);
    }
    return;
  },
  _processFormEcheck: function (uid, selected_type) {
    var self = this;
    var params = "";
    var account_number = self.form_processor[uid].getItemValue("billing_account_number");
    var account_number_confirm = self.form_processor[uid].getItemValue("billing_account_number_confirm");
    if (account_number != account_number_confirm) {
      dhtmlx.message({
        text: "The fields 'Checking account' and 'Confirm account' does not have the same value.",
        type: "confirm"
      });
      self._setFormCardInputHighlighted(self.form_processor[uid].getInput("billing_account_number"), uid);
      self._setFormCardInputHighlighted(self.form_processor[uid].getInput("billing_account_number_confirm"), uid);
      return;
    }
    if (self.form_processor[uid].getItemValue("signature_saved") == "unsaved") {
      dhtmlx.message({
        text: "You did not signed the e-check yet.",
        type: "confirm"
      });
      self.toolbar[uid].enableItem("make_payment");
      self._setOverButton("sign.png");
      return;
    }
    var hash = self.form_processor[uid].getFormData();
    for (var formfield in hash) {
      params = params + formfield + "=" + hash[formfield] + "&";
    }
    //params = params + "card_type=" + selected_type + "&";
    params = params + "invoice_id=" + self.invoiceID[uid] + "&";
    params = params + "timezone=" + self.timezone[uid] + "&";
    params = params + "currency=" + self.currency[uid] + "&";
    params = params + "invoice_totalpay=" + ((new Number(self.total[uid])) + (new Number(self.fees[uid]))).toFixed(2) + "&";
    params = params + "extra=" + JSON.stringify(self.configuration[uid].extra) + "&";
    params = params + "application_url=" + encodeURI(self.configuration[uid].application_url) + "&";
    params = params + "save_on_database=" + self.configuration[uid].saveondatabase + "&";
    params = params + "customer_id=" + self.customerID[uid];
    self._progressOn(uid);
    dhtmlxAjax.post(self.configuration[uid].application_url + "processors/echeck.php", encodeURI(params), function (loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          dhtmlx.alert("Check sent");
          self._callCallBack(self._setObjResponse({
            uid: uid, // mandatory
            action: "paid", // mandatory paid,   cancelled
            status: json.status, // mandatory - success, err, cancelled
            message: json.response // not mandatory, default empty ""
            //date : json.date,
            //time : json.time
          }));

          self.toolbar_echeck[uid].disableItem("signature_expand");

          self._progressOff(uid);
        } else {
          //dhtmlx.message( {type : "error", text : json.response} );

          self._callCallBack(self._setObjResponse({
            uid: uid, // mandatory
            action: "paid", // mandatory paid, cancelled
            status: json.status, // mandatory - success, err, cancelled
            message: json.response, // not mandatory, default empty ""
            error: {
              type: "processor error", // server error, processor error
              message: json.response // line of error   
            }
            //date : json.date,
            //time : json.time
          }));

          self._progressOff(uid);
        }
      } catch (e) {
        dhtmlx.message({
          type: "confirm",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });

        self._callCallBack(self._setObjResponse({
          uid: uid, // mandatory
          action: "paid", // mandatory paid, cancelled
          status: "err", // mandatory - success, err, cancelled
          message: "error on server side", // not mandatory, default empty ""
          error: {
            type: "server error,", // server error, processor error
            message: "error on server side" // line of error    
          }

        }));

        self._progressOff(uid);
      }
    });
  },
  _processFormCheck: function (uid, selected_type) {
    var self = this;
    var params = "";
    var hash = self.form_processor[uid].getFormData();
    for (var formfield in hash) {
      params = params + formfield + "=" + hash[formfield] + "&";
    }
    //params = params + "card_type=" + selected_type + "&";
    params = params + "invoice_id=" + self.invoiceID[uid] + "&";
    params = params + "timezone=" + self.timezone[uid] + "&";
    params = params + "currency=" + self.currency[uid] + "&";
    params = params + "invoice_totalpay=" + ((new Number(self.total[uid])) + (new Number(self.fees[uid]))).toFixed(2) + "&";
    params = params + "application_url=" + encodeURI(self.configuration[uid].application_url) + "&";
    params = params + "extra=" + JSON.stringify(self.configuration[uid].extra) + "&";
    params = params + "save_on_database=" + self.configuration[uid].saveondatabase + "&";
    params = params + "customer_id=" + self.customerID[uid];
    self._progressOn(uid);
    dhtmlxAjax.post(self.configuration[uid].application_url + "processors/check.php", encodeURI(params), function (loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {
          self._callCallBack(self._setObjResponse({
            uid: uid, // mandatory
            action: "paid", // mandatory paid,   cancelled
            status: json.status, // mandatory - success, err, cancelled
            message: json.response // not mandatory, default empty ""
            //date : json.date,
            //time : json.time
          }));

          self._progressOff(uid);
        } else {
          //dhtmlx.message( {type : "error", text : json.response} );

          self._callCallBack(self._setObjResponse({
            uid: uid, // mandatory
            action: "paid", // mandatory paid, cancelled
            status: json.status, // mandatory - success, err, cancelled
            message: json.response, // not mandatory, default empty ""
            error: {
              type: "processor error", // server error, processor error
              message: json.response // line of error   
            }
            //date : json.date,
            //time : json.time
          }));
          self._progressOff(uid);
        }
      } catch (e) {
        dhtmlx.message({
          type: "confirm",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });
        self._callCallBack(self._setObjResponse({
          uid: uid, // mandatory
          action: "paid", // mandatory paid, cancelled
          status: "err", // mandatory - success, err, cancelled
          message: "error on server side", // not mandatory, default empty ""
          error: {
            type: "server error,", // server error, processor error
            message: "error on server side" // line of error    
          }
        }));
        self._progressOff(uid);
      }
    });
  },
  _processFormWireTransfer: function (uid, selected_type) {
    var self = this,
      params = "",
      hash = self.form_processor[uid].getFormData();
    for (var formfield in hash) {
      params = params + formfield + "=" + hash[formfield] + "&";
    }

    params = params + "bank_name=" + self._gatewayGetOption(uid, "wire_transfer").bank_name + "&";
    params = params + "ABA_number=" + self._gatewayGetOption(uid, "wire_transfer").ABA_number + "&";
    params = params + "account_number=" + self._gatewayGetOption(uid, "wire_transfer").account_number + "&";
    params = params + "additional_info=" + self._gatewayGetOption(uid, "wire_transfer").additional_info + "&";

    //params = params + "card_type=" + selected_type + "&";
    params = params + "invoice_id=" + self.invoiceID[uid] + "&";
    params = params + "timezone=" + self.timezone[uid] + "&";
    params = params + "currency=" + self.currency[uid] + "&";
    params = params + "invoice_totalpay=" + ((new Number(self.total[uid])) + (new Number(self.fees[uid]))).toFixed(2) + "&";
    params = params + "application_url=" + encodeURI(self.configuration[uid].application_url) + "&";
    params = params + "extra=" + JSON.stringify(self.configuration[uid].extra) + "&";
    params = params + "save_on_database=" + self.configuration[uid].saveondatabase + "&";
    params = params + "customer_id=" + self.customerID[uid];

    self._progressOn(uid);

    dhtmlxAjax.post(self.configuration[uid].application_url + "processors/wire_transfer.php", encodeURI(params), function (loader) {
      try {
        var json = JSON.parse(loader.xmlDoc.responseText);
        if (json.status == "success") {

          self._callCallBack(self._setObjResponse({
            uid: uid, // mandatory
            action: "paid", // mandatory paid,   cancelled
            status: json.status, // mandatory - success, err, cancelled
            message: json.response // not mandatory, default empty ""
            //date : json.date,
            //time : json.time
          }));

          self._progressOff(uid);
        } else {
          //dhtmlx.message( {type : "error", text : json.response} );

          self._callCallBack(self._setObjResponse({
            uid: uid, // mandatory
            action: "paid", // mandatory paid, cancelled
            status: json.status, // mandatory - success, err, cancelled
            message: json.response, // not mandatory, default empty ""
            error: {
              type: "processor error", // server error, processor error
              message: json.response // line of error   
            }
            //date : json.date,
            //time : json.time
          }));

          self._progressOff(uid);
        }
      } catch (e) {
        dhtmlx.message({
          type: "confirm",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });

        self._callCallBack(self._setObjResponse({
          uid: uid, // mandatory
          action: "paid", // mandatory paid, cancelled
          status: "err", // mandatory - success, err, cancelled
          message: "error on server side", // not mandatory, default empty ""
          error: {
            type: "server error,", // server error, processor error
            message: "error on server side" // line of error    
          }

        }));

        self._progressOff(uid);
      }
    });
  },
  _callCallBack: function (r) {
    var self = this,
      msgbox = "",
      text_button_print = 'print invoice';

    if (r.status == "err") {
      dhtmlx.message({
        type: "confirm",
        text: r.message
      });
    } else {
      self.layout[r.uid].cells("b").showHeader();
      self.layout[r.uid].cells("b").setText("Status: " + r.status + " - " + r.message);
      dhtmlx.message(r.message);
    }
    (typeof self.configuration[r.uid].extra !== 'undefined') ? r.extra = self.configuration[r.uid].extra : "";
    //console.log("r.extra " + r.extra);

    self.status_bar[r.uid].setText("Status: " + r.status + " - " + r.message);
    //console.log("r.status " + r.status);

    if (r.status == "success") {
      self.paid_status[r.uid] = "Paid";
      //self.form[ r.uid ].lock();
      r.paymentType = self.form[r.uid].getItemValue("selected_type");
      r.amount = self.amount;
      if (self.form[r.uid].getItemValue("selected_type") != "paypal") {
        self.form_processor[r.uid].lock()
      }

      msgbox = "Payment processed<br />";
      text_button_print = 'print receipt';

      //save the pdf files for invoice and receipt

      setTimeout(function () {

        self._printReceipt(r.uid);
        self._printInvoice(r.uid);

      }, 3000);
    } else if (r.status == "cancelled") {
      self.paid_status[r.uid] = "Cancelled";
      msgbox = "Payment cancelled<br>";
    } else if (r.status == "err") {
      self.paid_status[r.uid] = "Error";
      msgbox = "Error - The payment could not be processed<br>";
    } else {
      // dhtmlx.message({
      //   type: "confirm",
      //   text: "Do you want to print receipt",
      //   ok: "Yes",
      //   cancel: "No",
      //   callback: function(id) {
      //     if (id === "_true_") {
      //       self._printReceipt(r.uid);
      //     }
      //   }
      // });
      // dhtmlx.message({
      //   type: "confirm",
      //   text: "Do you want to print invoice",
      //   ok: "Yes",
      //   cancel: "No",
      //   callback: function(id) {
      //     if (id === "_true_") {
      //       self._printInvoice(r.uid);
      //     }
      //   }
      // });

    }

    self.paid_gateway[r.uid] = r.gateway.brand;
    self.paid_transactionID[r.uid] = r.gateway.transactionID;
    self.paid_token[r.uid] = r.gateway.token;
    self.paid_date[r.uid] = r.date + " " + r.time;
    delete r.extra;
    delete r.gateway;
    (self.configuration[r.uid].paymentCallBack) ? self.configuration[r.uid].paymentCallBack(r) : "";
    dhtmlx.message({
      type: "confirm",
      text: msgbox,
      ok: "ok"
    });
  },
  _setObjResponse: function (response) {
    var self = this,
      error = false,
      uid, message, date, time, amount;
    if (typeof self.signature_file_name[uid] === 'undefined') {
      self.signature_file_name[uid] = "not set";
    }
    if (self.signature_file_path[uid] === 'undefined') {
      self.signature_file_path[uid] = "not set";
    }
    if (!response.uid) {
      dhtmlx.message({
        text: "Response needs to have an uid",
        type: "confirm"
      });
      return;
    } else {
      uid = response.uid;
    }
    if (!response.action) {
      dhtmlx.message({
        text: "Response needs to have an action",
        type: "confirm"
      });
      return;
    }
    if (!response.status) {
      dhtmlx.message({
        text: "Response needs to have an status",
        type: "confirm"
      });
      return;
    }
    message = response.message || "";
    if (response.error) {
      error = {};
      error.type = response.error.type || "no error";
      error.message = response.error.message || "no error";
    }

    var dt = new Date();
    date = response.date || (dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear();
    time = response.time || dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
    // amount = response.amount || ((new Number(self.total[uid])) + (new Number(self.fees[uid]))).toFixed(2);
    amount = response.amount;
    if (!self.selected_gateway.token[uid])
      self.selected_gateway.token[uid] = ""; // paypal payment
    if (!self.selected_gateway.transactionID[uid])
      self.selected_gateway.transactionID[uid] = ""; // paypal payment
    if (!self.selected_gateway.avs_code[uid])
      self.selected_gateway.avs_code[uid] = ""; // authorize.net payment
    if (!self.selected_gateway.cvv2_response[uid])
      self.selected_gateway.cvv2_response[uid] = ""; // authorize.net payment
    if (!self.selected_gateway.cavv_response[uid])
      self.selected_gateway.cavv_response[uid] = ""; // authorize.net payment
    return {
      uid: response.uid,
      action: response.action,
      /* options paid,   cancelled */
      gateway: {
        brand: self.form[uid].getItemValue("selected_type"),
        /* options */ // Paypal, authorize.net, wire_transfer, check, e - check, "" (empty)
        token: self.selected_gateway.token[uid],
        transactionID: self.selected_gateway.transactionID[uid],
        avs_code: self.selected_gateway.avs_code[uid], // for authorize.net payments
        cvv2_response: self.selected_gateway.cvv2_response[uid], // for authorize.net payments
        cavv_response: self.selected_gateway.cavv_response[uid] // for authorize.net payments
      },
      status: response.status, // success, err, cancelled
      message: message,
      error: error, // || false         
      date: date, // payment date
      time: time, // payment time
      timezone: self.timezone[uid], // not mandatory, default America/New_York
      amount: amount, // amount paid
      currency: self.currency[uid], // amount paid
      customer_id: self.customerID[uid],
      invoice_id: self.invoiceID[uid],
      signature_file_name: self.signature_file_name[uid],
      signature_file_path: self.signature_file_path[uid]
    };
  },
  _processFormCard: function (uid, selected_type) {
    var self = this;
    var params = "";
    var hash = self.form_processor[uid].getFormData();

    for (var formfield in hash) {
      params = params + formfield + "=" + hash[formfield] + "&";
    }
    params = params + "card_type=" + selected_type + "&";
    params = params + "invoice_id=" + self.invoiceID[uid] + "&";
    params = params + "timezone=" + self.timezone[uid] + "&";
    params = params + "currency=" + self.currency[uid] + "&";
    params = params + "invoice_totalpay=" + self.amount + "&";
    params = params + "application_url=" + encodeURI(self.configuration[uid].application_url) + "&";
    //params = params + "extra=" + JSON.stringify(self.configuration[uid].extra) + "&";
    params = params + "save_on_database=" + self.configuration[uid].saveondatabase + "&";
    params = params + "customer_id=" + self.customerID[uid];
    self._progressOn(uid);
    dhtmlxAjax.post(self.configuration[uid].application_url + "processors/authorizenet.php", encodeURI(params), function (loader) {

      try {
        var json = JSON.parse(loader.xmlDoc.responseText);

        if (json.status == "success") {
          dhtmlx.message({
            text: "Payment Success With Authorize.net"
          });

          self.selected_gateway.avs_code[uid] = json.avs_code;
          self.selected_gateway.cvv2_response[uid] = json.cvv2_response;
          self.selected_gateway.cavv_response[uid] = json.cavv_response;
          self.selected_gateway.transactionID[uid] = json.authorization;
          self._callCallBack(self._setObjResponse({
            uid: uid, // mandatory
            action: "paid", // mandatory paid,   cancelled
            status: json.status, // mandatory - success, err, cancelled
            message: json.response, // not mandatory, default empty ""
            date: json.date,
            time: json.time
          }));
          self._progressOff(uid);
        } else {
          if (json.response_reason_text == 'The credit card has expired.') {
            self.toolbar[uid].enableItem("make_payment");
            dhtmlx.alert({
              title: "Alert",
              text: json.response_reason_text
            });
          } else {
            //dhtmlx.message( {type : "error", text : json.response} );
            self.selected_gateway.avs_code[uid] = json.avs_code;
            self.selected_gateway.cvv2_response[uid] = json.cvv2_response;
            self.selected_gateway.cavv_response[uid] = json.cavv_response;
            dhtmlx.alert({
              title: "Alert",
              text: json.response_reason_text
            });
            // self._callCallBack(self._setObjResponse({
            //   uid: uid, // mandatory
            //   action: "paid", // mandatory paid, cancelled
            //   status: json.status, // mandatory - success, err, cancelled
            //   message: json.response, // not mandatory, default empty ""
            //   error: {
            //     type: "processor error", // server error, processor error
            //     message: json.response_reason_text // line of error   
            //   },
            //   date: json.date,
            //   time: json.time
            // }));
          }
          self._progressOff(uid);
        }
      } catch (e) {
        dhtmlx.message({
          type: "confirm",
          text: "Fatal error on server side: " + loader.xmlDoc.responseText
        });

        self._callCallBack(self._setObjResponse({
          uid: uid, // mandatory
          action: "paid", // mandatory paid, cancelled
          status: "err", // mandatory - success, err, cancelled
          message: "error on server side", // not mandatory, default empty ""
          error: {
            type: "server error,", // server error, processor error
            message: "error on server side" // line of error    
          }

        }));

        self._progressOff(uid);
      }
    });
  },
  _setVisa: function (uid, c) {
    var self = this;
    self.layout[uid].cells("b").expand();
    self.form[uid].setItemValue("selected_type", "Visa");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_visa);
    self.formFields[uid] = []; // clean the array of formFields
    self._setFormCard(uid, c);
    // self._setFees(self.fee_Visa[uid], uid, self.configuration[uid].charge_percentage_Visa);
    //self._setTotal(uid, self.configuration[uid].charge_percentage_Visa);
    //console.log(self.configuration[ uid ].charge_percentage_Visa);
    self.toolbar[uid].enableItem("make_payment");
  },
  _setMasterCard: function (uid, c) {
    var self = this;
    self.layout[uid].cells("b").expand();
    self.form[uid].setItemValue("selected_type", "MasterCard");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_master);
    self.formFields[uid] = []; // clean the array of formFields
    self._setFormCard(uid, c);
    // self._setFees(self.fee_MasterCard[uid], uid, self.configuration[uid].charge_percentage_MasterCard);
    //self._setTotal(uid, self.configuration[uid].charge_percentage_MasterCard);
    self.toolbar[uid].enableItem("make_payment");
  },
  _setAmericanExpress: function (uid, c) {
    var self = this;
    self.layout[uid].cells("b").expand();
    self.form[uid].setItemValue("selected_type", "American Express");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_AmericanExpress);
    self.formFields[uid] = []; // clean the array of formFields
    self._setFormCard(uid, c);
    // self._setFees(self.fee_AmericanExpress[uid], uid, self.configuration[uid].charge_percentage_AmericanExpress);
    //self._setTotal(uid, self.configuration[uid].charge_percentage_AmericanExpress);
    self.toolbar[uid].enableItem("make_payment");
  },
  _setDiscover: function (uid, c) {
    var self = this;
    self.layout[uid].cells("b").expand();
    self.form[uid].setItemValue("selected_type", "Discover");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_discover);
    self.formFields[uid] = []; // clean the array of formFields
    self._setFormCard(uid, c);
    // self._setFees(self.fee_Discover[uid], uid, self.configuration[uid].charge_percentage_Discover);
    //self._setTotal(uid, self.configuration[uid].charge_percentage_Discover);
    self.toolbar[uid].enableItem("make_payment");
  },
  _setWireTransfer: function (uid, c) {
    var self = this;
    self.layout[uid].cells("b").expand();
    self.form[uid].setItemValue("selected_type", "wire_transfer");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_wire_transfer);
    self._setFormWireTransfer(uid, c);
    self.layout[uid].cells("b").attachHTMLString("wire transfer");
    //self._setFees(self.fee_wiretransfer[uid], uid, self.configuration[uid].charge_percentage_wiretransfer);
    //self._setTotal(uid, self.configuration[uid].charge_percentage_wiretransfer);
  },
  _setCheck: function (uid, c) {
    var self = this;
    self.layout[uid].cells("b").expand();
    self.form[uid].setItemValue("selected_type", "check");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_check);
    self._setFormCheck(uid, c);
    self._setFees(self.fee_check[uid], uid, self.configuration[uid].charge_percentage_check);
    self._setTotal(uid, self.configuration[uid].charge_percentage_check);
    //console.log("_setCheck " + self.configuration[ uid ].charge_percentage_check);
  },
  _setECheck: function (uid, c) {
    var self = this;

    self.layout[uid].cells("b").expand();

    self.form[uid].setItemValue("selected_type", "e-check");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_echeck);

    self._setFormECheck(uid, c);

    //self._setFormCard( uid, c );

    self._setFees(self.fee_echeck[uid], uid, self.configuration[uid].charge_percentage_echeck);
    self._setTotal(uid, self.configuration[uid].charge_percentage_echeck);
  },
  // _setTotal: function(uid, self.configuration[uid].charge_percentage_echeck) {

  // },
  _setPayPal: function (uid, c, isCard) {
    var self = this;
    self.layout[uid].cells("b").expand();
    self.form[uid].setItemValue("selected_type", "paypal");
    self.layout[uid].cells("b").setText(self.model.text_labels.processor_header_paypal);
    self.layout[uid].cells("b").attachHTMLString("<b>Loading..</b>");
    self.layout[uid].cells("a").collapse();
    self._progressOn(self.uid);
    dhtmlxAjax.get(self.configuration[self.uid].application_url + "processors/paypal/paypal.php?amount=" + self.amount, function (loader) {
      console.log(loader.xmlDoc.responseText);
      var json = JSON.parse(loader.xmlDoc.responseText);
      console.log(json);
      if (json.status == "success") {
        console.log(loader.xmlDoc.responseText);
        var json = JSON.parse(loader.xmlDoc.responseText);
        setTimeout(function () {
          self._progressOff(self.uid);
        }, 5000);

        self.layout[uid].cells("b").attachURL(json.url);
        var paypalFrame = self.layout[uid].cells("b").getFrame();
      }


      // try {
      //   var json = JSON.parse(loader.xmlDoc.responseText);
      //   console.log(json);
      //   if (json.status == "success") {
      //     console.log(loader.xmlDoc.responseText);
      //     var json = JSON.parse(loader.xmlDoc.responseText);
      //     console.log(loader.xmlDoc.responseText);
      //     self.layout[uid].cells("b").attachURL(json.url);
      //     var payplFrame = self.layout[uid].cells("b").getFrame();
      //     paypalFrame.attachEvent("onload", function () {
      //       console.log("loading...");
      //     });
      //   } else {
      //     dhtmlx.message({
      //       type: "confirm",
      //       text: json.response
      //     });
      //   }
      // } catch (e) {
      //   dhtmlx.message({
      //     type: "confirm",
      //     text: "Fatal error on server side: " + loader.xmlDoc.responseText
      //   });
      // }
    });
  },
  _luhnCheckSum: function (sCardNum) {
    var iOddSum = 0;
    var iEvenSum = 0;
    var bIsOdd = true;
    for (var i = sCardNum.length - 1; i >= 0; i--) {
      var iNum = parseInt(sCardNum.charAt(i));
      if (bIsOdd) {
        iOddSum += iNum;
      } else {
        iNum = iNum * 2;
        if (iNum > 9) {
          iNum = eval(iNum.toString().split("").join("+"));
        }
        iEvenSum += iNum;
      }
      bIsOdd = !bIsOdd;
    }
    return ((iEvenSum + iOddSum) % 10 == 0);
  },
  _isValidMasterCard: function (sText) {
    var self = this;
    var reMasterCard = /^(5[1-5]\d{2})[\s\-]?(\d{4})[\s\-]?(\d{4})[\s\-]?(\d{4})$/;
    if (reMasterCard.test(sText)) {
      var sCardNum = RegExp.$1 + RegExp.$2 + RegExp.$3 + RegExp.$4;
      return self._luhnCheckSum(sCardNum);
    } else {
      return false;
    }
  },
  _isValidAmericanExpress: function (sText) {
    var self = this;
    var cardno = /^(?:3[47][0-9]{13})$/;
    if (sText.match(cardno)) {
      return true;
    } else {
      return false;
    }
  },
  _isValidDiscover: function (sText) {
    var self = this;
    var cardno = /^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;
    if (sText.match(cardno)) {
      return true;
    } else {
      return false;
    }
  },
  _isValidVisa: function (sText) {
    var self = this;
    var reVisa = /^(4\d{12}(?:\d{3})?)$/;
    if (reVisa.test(sText)) {
      return self._luhnCheckSum(RegExp.$1);
    } else {
      return false;
    }
  },
  _gatewayGetOption: function (uid, option) {
    var self = this;
    for (var x = 0; x < self.gateways_allowed[uid].length; x++) {
      var gateway = self.gateways_allowed[uid][x];
      var subObj = "gateway." + option;
      if (eval(subObj)) {
        return gateway;
      }
    }
    return false;
  },
  _gatewaysSetAllowed: function (cArray) {
    var self = this,
      uid = self.uid;

    self.gateways_allowed[uid] = [];
    //console.log(cArray);
    for (var x = 0; x < cArray.length; x++) {
      var gateway = cArray[x];
      if (gateway.paypal || gateway.authorize_net) {
        (typeof gateway.Visa === 'undefined') ? gateway.Visa = true : "";
        (typeof gateway.MasterCard === 'undefined') ? gateway.MasterCard = true : "";
        (typeof gateway.AmericanExpress === 'undefined') ? gateway.AmericanExpress = true : "";
        (typeof gateway.Discover === 'undefined') ? gateway.Discover = true : "";

      }
      if (gateway.paypal) {
        self.form[uid].enableItem("payment_type", "paypal");
        self.gateways_allowed[uid].push(gateway);
      } else if (gateway.paypal == false) {
        self.form[uid].disableItem("payment_type", "paypal");

      }

      if (gateway.authorize_net) {
        self.form[uid].enableItem("payment_type", "Visa");
        self.form[uid].enableItem("payment_type", "MasterCard");
        self.form[uid].enableItem("payment_type", "American Express");
        self.form[uid].enableItem("payment_type", "Discover");
        (gateway.Visa == false) ? self.form[uid].disableItem("payment_type", "Visa") : "";
        (gateway.MasterCard == false) ? self.form[uid].disableItem("payment_type", "MasterCard") : "";
        (gateway.AmericanExpress == false) ? self.form[uid].disableItem("payment_type", "American Express") : "";
        (gateway.Discover == false) ? self.form[uid].disableItem("payment_type", "Discover") : "";
        self.gateways_allowed[uid].push(gateway);
      } else if (gateway.authorize_net == false) {
        self.form[uid].disableItem("payment_type", "Visa");
        self.form[uid].disableItem("payment_type", "MasterCard");
        self.form[uid].disableItem("payment_type", "American Express");
        self.form[uid].disableItem("payment_type", "Discover");
        //self.form[ uid ].disableItem("authorizenet");
      }
      if (gateway.wire_transfer) {
        self.form[uid].enableItem("payment_type", "wire_transfer");
        self.gateways_allowed[uid].push(gateway);
      }

      // else if (gateway.wire_transfer == false) {
      //   self.form[uid].removeItem("payment_type", "wire_transfer");
      //   self.form[uid].disableItem("payment_type", "wire_transfer");
      // }
      // if (gateway.echeck) {
      //   self.form[uid].enableItem("payment_type", "e-check");
      //   self.gateways_allowed[uid].push(gateway);
      // } else if (gateway.echeck == false) {
      //   self.form[uid].removeItem("payment_type", "e-check");
      //   self.form[uid].disableItem("payment_type", "e-check");
      // } else if (gateway.check) {
      //   self.form[uid].enableItem("payment_type", "check");
      //   self.gateways_allowed[uid].push(gateway);
      // } else if (gateway.check == false) {
      //   self.form[uid].removeItem("payment_type", "check");
      //   self.form[uid].disableItem("payment_type", "check");
      // }

      // if (gateway.paypal == false && gateway.authorize_net == false) {
      //     self.form[uid].removeItem("payment_type", "cards_separator");
      // }

      // if (gateway.paypal == false && gateway.wire_transfer == false) {
      //     self.form[uid].removeItem("payment_type", "second_separator");
      // }
    }
  },
  _gatewaysDisableAll: function () {
    var self = this,
      uid = self.uid;
    self.form[uid].disableItem("payment_type", "Visa");
    self.form[uid].disableItem("payment_type", "MasterCard");
    self.form[uid].disableItem("payment_type", "American Express");
    self.form[uid].disableItem("payment_type", "Discover");
    self.form[uid].disableItem("payment_type", "paypal");
    self.form[uid].disableItem("payment_type", "wire_transfer");
    self.form[uid].disableItem("payment_type", "check");
    self.form[uid].disableItem("payment_type", "e-check");
  },
  _progressOn: function (uid) {
    var self = this;
    self.windows[uid].progressOn();
    //self.layout[ uid ].cells("a").progressOn();
    self.layout[uid].cells("b").progressOn();
  },
  _progressOff: function (uid) {
    var self = this;
    self.windows[uid].progressOff();
    //self.layout[ uid ].cells("a").progressOff();
    self.layout[uid].cells("b").progressOff();
  },
  init: function (model) {
    var self = this;
    if (!model) {
      alert("Model is missing ...");
      return;
    }
    self.model = model;
    Number.prototype.formatMoney = function (c, d, t) {
      var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "," : d,
        t = t == undefined ? "." : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
      return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };
  },
  newFlow: function (c) {

    var self = this;
    if ((!c.invoice_id) || c.invoice_id == "") {
      dhtmlx.message({
        type: "confirm",
        text: "invoice_id is missing"
      });
      return;
    }
    if ((!c.invoice_pay_for_desc) || c.invoice_pay_for_desc == "") {
      dhtmlx.message({
        type: "confirm",
        text: "payment description is missing"
      });
      return;
    }
    //customer checking
    if ((!c.customer_id) || c.invoice_id == "") {
      dhtmlx.message({
        type: "confirm",
        text: "customer_id is missing"
      });
      return;
    }
    if ((!c.customer_firstName) || c.customer_firstName == "") {
      dhtmlx.message({
        type: "confirm",
        text: "customer_firstName is missing"
      });
      return;
    }
    if ((!c.customer_lastName) || c.customer_lastName == "") {
      dhtmlx.message({
        type: "confirm",
        text: "customer_lastName is missing"
      });
      return;
    }
    if ((!c.customer_email) || c.customer_email == "") {
      dhtmlx.message({
        type: "confirm",
        text: "customer_email is missing"
      });
      return;
    }
    if ((!c.customer_address1) || c.customer_address1 == "") {
      dhtmlx.message({
        type: "confirm",
        text: "customer_address1 is missing"
      });
      return;
    }
    if ((!c.signature_saving_path) || c.signature_saving_path == "") {
      dhtmlx.message({
        type: "confirm",
        text: "signature_saving_path is missing"
      });
      return;
    }
    if ((!c.application_url) || c.application_url == "") {
      dhtmlx.message({
        type: "confirm",
        text: "application_url is missing"
      });
      return;
    }
    if ((typeof c.dhtmlx_codebase_path === 'undefined') || (c.application_url == "")) {
      dhtmlx.message({
        type: "confirm",
        text: "dhtmlx_codebase_path is missing ..."
      });
      return;
    }
    /* NEW 10/OCT/2013 */
    if ((typeof c.saveondatabase === 'undefined') || (c.saveondatabase == "")) {
      c.saveondatabase = 0;
    }


    if ((typeof c.pdf_saving_path === 'undefined') || c.pdf_saving_path == "") {
      dhtmlx.message({
        type: "confirm",
        text: "pdf_saving_path is missing"
      });
      return;
    }
    (typeof c.agency_logo === "undefined") ? c.agency_logo = c.application_url + "asserts/logos/cairs_logo.jpg" : "";
    ((typeof c.terms_and_conditions === "undefined") || (c.terms_and_conditions === "")) ? c.terms_and_conditions = "" : "";
    (typeof c.extra === 'undefined') ? c.extra = {} : "";

    var uid = c.customer_id + "_" + c.invoice_id;
    self.uid = uid;
    self.configuration[uid] = c;
    self.model.globalImgPath = c.dhtmlx_codebase_path + "imgs/";
    self.model.globalSkin = "dhx_skyblue";
    window.dhx_globalImgPath = c.dhtmlx_codebase_path + "imgs/";
    dhtmlx.skin = "dhx_skyblue";
    self.model.conf_window.image_path = c.icons_path;
    self.model.conf_toolbar_select.icon_path = c.icons_path;
    self.model.conf_toolbar_signature.icon_path = c.icons_path;
    self.model.conf_toolbar_pleasesign.icon_path = c.icons_path;
    self.model.conf_toolbar.icon_path = c.icons_path + "32px/",
    self.invoiceID[uid] = c.invoice_id;
    self.customerID[uid] = c.customer_id;
    (c.currency) ? self._setCurrency(c.currency, uid) : self._setCurrency("USD", uid);
    (c.timezone) ? self.timezone[uid] = c.timezone : self.timezone[uid] = "America/New_York";
    self.formFields_tofill[uid] = 0;
    self._window(c);
    self._toolbar();
    self._layout();
    self._form(self.model.conf_form.template, c);
    self._grid();
    // self.amount = c.pay_for_items[0].values[2];
    self.amount = (function () {
      var total = 0,
        i = 0;
      for (var i in c.pay_for_items) {
        total += c.pay_for_items[i].values[2];
        i++;
      }
      console.log("total:" + total);
      return total;
    }());
    (c.pay_for_items) ? self._addRow(c.pay_for_items) : "";
    self.grid[uid].attachFooter("Total:,#cspan,USD " + self.amount, ["height:20px;line-height:20px", "text-align:center"]);
    self._gatewaysSetAllowed(c.gateways_allowed);
  },
  $: function (c) {
    var self = this;
    self.newFlow(c);
  }
};

PaymentFlow.init(Model);