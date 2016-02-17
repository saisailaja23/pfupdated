var self = this,
  proto = location.protocol,
  siteUrl = proto + "//" + location.host + "/",
  appPath = siteUrl + 'paymentFlow/';
var Model = {
  globalImgPath: "../codebase3.5/imgs/", // not mandatory, default "../codebase3.5/imgs/"

  globalSkin: "dhx_skyblue", // not mandatory, default "dhx_skyblue"
  text_labels: {
    payment_select_type: "Select a payment type first",
    payment_fee_updated: "Fee updated",
    not_done: "not done yet, try other option",
    details_sub_total: "Sub total",
    details_fees: "Surcharge Fee",
    details_total: "Total",
    sign_window_text: "Signature",
    sign_not_valid_again: "Your signature is not valid. Please sign again.",
    sign_not_valid: "Your signature is not valid.",
    sign_valid: "Your signature is valid. You can save it now.",

    processor_header_visa: "Visa selected - fill the form below",
    processor_header_master: "MasterCard selected - fill the form below",
    processor_header_AmericanExpress: "American Express selected - fill the form below",
    processor_header_discover: "Discover selected - fill the form below",
    processor_header_check: "Check selected",
    processor_header_echeck: "E-check selected - fill the form below",
    processor_header_wire_transfer: "Wire transfer selected",
    processor_header_paypal: "Paypal selected",

    // validation text labels section
    validation_notEmpty: function (label) {
      return "The '" + label + "' field's value can not be empty";
    },
    validation_Empty: function (label) {
      return "The '" + label + "' field's value should be empty";
    },
    validation_ValidEmail: function (label) {
      return "The " + label + " field 's value is not a valid e-mail";
    },
    validation_ValidInteger: function (label) {
      return "The " + label + " field 's should be a valid integer value";
    },
    validation_ValidFloat: function (label) {
      return "The " + label + " field 's should be a valid float value";
    },
    validation_ValidNumeric: function (label) {
      return "The " + label + " field 's value should be a valid numeric value";
    },
    validation_ValidAplhaNumeric: function (label) {
      return "The " + label + " field 's value should be a valid alpha numeric value";
    },
    validation_ValidDatetime: function (label) {
      return "The " + label + " field 's value should be a valid date time value";
    },
    validation_ValidExpirationdate: function (label) {
      return "The " + label + " field 's value should be a valid expiration date";
    },
    validation_ValidDate: function (label) {
      return "The " + label + " field 's value should be a valid date value";
    },
    validation_ValidTime: function (label) {
      return "The " + label + " field 's value should be a valid time value";
    },
    validation_ValidCurrency: function (label) {
      return "The " + label + " field 's should be a valid currency value";
    },
    validation_ValidSSN: function (label) {
      return "The " + label + " field 's should be a valid social security number value";
    },
    validation_ValidAlphaChar: function (label) {
      return "The " + label + " field's should be a valid " + label;
    },
    validation_ValidZIP: function (label) {
      return "The " + label + " field's should be 5 characters maximum and numeric";
    }
  },

  conf_window: {
    image_path: "asserts/icons/",
    viewport: "body",
    left: 400,
    top: 100,
    width: 830,
    height: 770,
    enableAutoViewport: true,
    icon: "lock.png",
    icon_dis: "lock_dis.png",
    className: "paymentFlowWindow"
  },

  conf_layout: {
    pattern: "2E",
    height_main_cell: 322,
    text_processor_cell: "Payment process"
  },

  conf_toolbar: {
    icon_path: "asserts/icons/32px/",
    items: [{
        type: "button",
        id: "make_payment",
        text: "Make payment",
        img: "make_payment.png",
        img_disabled: "make_payment.png",
        disabled: true
      }, {
        type: "button",
        id: "cancel",
        text: "Cancel",
        //disabled: true,
        img: "cancel.png",
        img_disabled: "cancel.png"
      }, {
        type: "buttonSelect",
        id: "print",
        text: "Print",
        img: "print.png",
        img_disabled: "print.png",
        options: [{
          type: "obj",
          id: "print_invoice",
          text: "Print invoice",
          //disabled: true,
          img: "invoice.png",
          img_disabled: "invoice.png"
        }, {
          type: "obj",
          id: "print_receipt",
          text: "Print receipt",
          disabled: true,
          img: "receipt.png",
          img_disabled: "receipt.png"
        }]
      }, {
        type: "separator",
        id: "sep3"
      }

      // ,{
      //   type: "button",
      //   id: "help",
      //   text: "help",
      //   //disabled: true,
      //   img: "help.png",
      //   img_disabled: "help.png"
      // }

    ]
  },


  conf_toolbar_pleasesign: {
    icon_path: "asserts/icons/",
    items: [{
        type: "button",
        id: "signature_save",
        text: "Save signature",
        img: "save.gif",
        img_disabled: "save_dis.gif"
      }, {
        type: "button",
        id: "signature_clear",
        text: "Clear signature",
        //disabled: true,
        img: "clear.png",
        img_disabled: "clear.png"
      }, {
        type: "separator",
        id: "sep1"
      }, {
        type: "button",
        id: "signature_select_pre",
        text: "Choose signature",
        img: "open_file.png",
        img_disabled: "open_file_dis.png"
      }

    ]
  },


  conf_toolbar_signature: {
    icon_path: "asserts/icons/",
    items: [{
        type: "button",
        id: "signature_expand",
        text: "Create signature",
        img: "sign.png",
        img_disabled: "sign_dis.png"
      }
      /*,
      {
        type: "button",
        id: "echeck_print",
        text: "print e-check",
        img: "print.gif",
        img_disabled: "print_dis.gif",
        disabled : true
      }*/
    ]
  },

  conf_toolbar_select: {
    icon_path: "",
    items: [{
      type: "button",
      id: "signature_select",
      text: "Choose signature",
      img: "add.gif",
      img_disabled: "add_dis.gif",
      disabled: true
    }]
  },

  conf_grid: {
    container: "grid_cost",
    headers: "Item,Payment Description,Cost",
    widths: "200,300,*",
    colaligns: "left,left,right",
    coltypes: "ro,ro,ro",
    colsorting: "str,str,int"
  },

  conf_grid_total: {
    container: "grid_total",
    headers: "Total paying,#cspan",
    widths: "440,150",
    colaligns: "right,right",
    coltypes: "ro,ro,ro",
    colsorting: "str,int"
  },

  conf_form: {
    template: [{
        type: "settings",
        labelWidth: 50,
        offsetLeft: 10,

        position: "label-right",
        offsetTop: 0
      }, {
        type: "block",
        inputWidth: "auto",
        label: "Select a payment type",
        className: "dark-teal",
        list: [{
          type: "label",
          name: "selLabel",
          label: "Select a payment type",
          labelWidth: 200,
          list: [{
              type: "radio",
              name: "payment_type",
              value: "Visa",
              label: "<img src='" + appPath + "asserts/imgs/visa.png' />"
            }, {
              type: "newcolumn",
              offset: 20
            }, {
              type: "radio",
              name: "payment_type",
              value: "MasterCard",
              label: "<img src='" + appPath + "asserts/imgs/master.png' />"
            }, {
              type: "newcolumn",
              offset: 20
            }, {
              type: "radio",
              name: "payment_type",
              value: "American Express",
              label: "<img src='" + appPath + "asserts/imgs/amex.png' />"
            }, {
              type: "newcolumn",
              offset: 20
            }, {
              type: "radio",
              name: "payment_type",
              value: "Discover",
              label: "<img src='" + appPath + "asserts/imgs/discover.png' />"
            }, {
              type: "newcolumn",
              offset: 20
            }, {
              type: "radio",
              name: "payment_type",
              value: "paypal",
              label: "<img src='" + appPath + "asserts/imgs/paypal.png' />"
            }, {
              type: "newcolumn",
              offset: 20
            },

            // {
            //   type: "radio",
            //   name: "payment_type",
            //   value: "wire_transfer",
            //   label: "<img src='" + appPath + "asserts/imgs/wire.png' />"
            // }, {
            //   type: "newcolumn",
            //   offset: 20
            // }, {
            //   type: "radio",
            //   name: "payment_type",
            //   value: "e-check",
            //   label: "<img src='" + appPath + "asserts/imgs/e_check.png' />"
            // }, {
            //   type: "newcolumn",
            //   offset: 20
            // }, {
            //   type: "radio",
            //   name: "payment_type",
            //   value: "check",
            //   label: "<img src='" + appPath + "asserts/imgs/check.png' />"
            // },

            {
              type: "newcolumn",
              offset: 20
            }, {
              type: "hidden",
              name: "selected_type",
              value: ""
            }
          ]
        }]
      },

      {
        type: "block",
        width: 750,
        offsetLeft: 30,
        inputWidth: "auto",
        list: [{
          type: "settings",
          labelWidth: 100,
          offsetLeft: 0,
          inputWidth: 120,
          position: "label-top",
          offsetTop: 10
        }, {
          type: "container",
          name: "grid_cost",
          label: "",
          inputWidth: 620,
          inputHeight: 150
        }]
      }
    ]
  },

  conf_form_card: {
    template: [{
      type: "settings",
      labelWidth: 100,
      inputWidth: 120,
      position: "label-left",
      offsetTop: 5,
      offsetLeft: 5
    }, {
      type: "label",
      width: 240,
      label: "Credit Card information",
      labelWidth: 240,
      list: [{
          type: "input",
          name: "card_number",
          label: "Card number",
          tooltip: "type the card number",
          required: true,
          validate: 'NotEmpty',
          mask_to_use: "integer",
          maxLength: '16'
        }, {
          type: "input",
          name: "card_firstname",
          label: "First name",
          tooltip: "type the first name",
          required: true,
          validate: 'NotEmpty'
        },
        //{type: "newcolumn", /*offset : 50*/},
        {
          type: "input",
          name: "card_lastname",
          label: "Last name",
          tooltip: "type the last name",
          required: true,
          validate: 'NotEmpty'
        }, {
          type: "input",
          name: "card_expirationdate",
          label: "Expiration date",
          tooltip: "type the expiration date",
          required: true,
          mask_to_use: "expiration_date",
          validate: 'NotEmpty,ValidExpirationdate',
          note: {
            text: "mm/yy - only numbers"
          }
        }, {
          type: "input",
          name: "card_securitycode",
          label: "Security code",
          tooltip: "type the security code",
          required: true,
          mask_to_use: "integer",
          validate: 'NotEmpty',
          maxLength: '4'
        }
      ]
    }, {
      type: "newcolumn",
      offset: 20
    }, {
      type: "label",
      width: 450,
      label: "Billing address information",
      labelWidth: 350,
      list: [{
        type: "input",
        name: "billing_firstname",
        label: "First name",
        tooltip: "type the first name",
        required: true,
        validate: 'NotEmpty'
      }, {
        type: "input",
        name: "billing_lastname",
        label: "Last name",
        tooltip: "type the last name",
        required: true,
        validate: 'NotEmpty'
      }, {
        type: "input",
        name: "billing_companyname",
        label: "Company name",
        tooltip: "type the company name"
      }, {
        type: "input",
        name: "billing_address1",
        label: "Address 1",
        tooltip: "type the address 1",
        required: true,
        validate: 'NotEmpty'
      }, {
        type: "input",
        name: "billing_address2",
        label: "Address 2",
        tooltip: "type the address 2"
      }, {
        type: "input",
        name: "billing_city",
        label: "City",
        tooltip: "type the city",
        required: true,
        validate: 'ValidAlphaChar'
      }, {
        type: "newcolumn",
        offset: 10
      }, {
        type: "input",
        name: "billing_state",
        label: "State",
        tooltip: "select the state",
        required: true,
        validate: 'ValidAlphaChar'
      }, {
        type: "input",
        name: "billing_zipcode",
        label: "Zip code",
        tooltip: "type the zip code",
        required: true,
        validate: 'ValidZIP',
        maxLength: '5'
      }, {
        type: "combo",
        name: "billing_country",
        label: "Country",
        tooltip: "select the country",
        required: true,
        validate: 'NotEmpty',
        options: [{
          value: "US",
          text: "United States",
          selected: true
        }]
      }, {
        type: "input",
        name: "billing_phonenumber",
        label: "Phone number",
        tooltip: "type the phone number",
        mask_to_use: "us_phone"
      }, {
        type: "input",
        name: "billing_mobilenumber",
        label: "Mobile number",
        tooltip: "type the mobile number",
        mask_to_use: "us_phone"
      }, {
        type: "hidden",
        name: "pay_for_desc",
        value: '',
        validate: 'NotEmpty'
      }]
    }]
  },


  conf_form_echeck: {
    template: [{
        type: "settings",
        labelWidth: 204,
        inputWidth: 230,
        position: "label-top",
        offsetTop: 3,
        offsetLeft: 5
      },


      {
        type: "block",
        width: 430,
        list: [{
            type: "block",
            width: 300,
            list: [{
              type: "label",
              label: "<img src='asserts/imgs/step1.png' align='middle'/> Fill out the account information",
              labelWidth: 300,
              offsetTop: 0
            }, {
              type: "input",
              name: "billing_name",
              label: "Account holder's name",
              tooltip: "type the account holder's name",
              required: true,
              validate: 'NotEmpty'
            }, {
              type: "input",
              name: "billing_address1",
              label: "Your street address",
              tooltip: "type your street address",
              required: true,
              validate: 'NotEmpty'
            }, {
              type: "block",
              width: 340,
              position: "label-top",
              offsetLeft: 0,
              offsetTop: 0,
              list: [{
                type: "settings",
                labelWidth: 75,
                inputWidth: 75,
                position: "label-top",
                inputLeft: 0,
                offsetLeft: 0
              }, {
                type: "input",
                name: "billing_city",
                label: "City",
                tooltip: "type your city",
                required: true,
                validate: 'ValidAlphaChar'
              }, {
                type: "newcolumn",
                offset: 3
              }, {
                type: "input",
                name: "billing_state",
                label: "State",
                inputWidth: 100,
                tooltip: "type your street state",
                required: true,
                validate: 'ValidAlphaChar'
              }, {
                type: "newcolumn",
                offset: 3
              }, {
                type: "input",
                name: "billing_zipcode",
                inputWidth: 50,
                label: "Zip code",
                tooltip: "type zip code",
                required: true,
                validate: 'ValidZIP',
                maxLength: '5'
              }]
            }, {
              type: "input",
              name: "billing_phonenumber",
              label: "Your phone number",
              tooltip: "type your phone number",
              mask_to_use: "us_phone"
            }]
          },
          //{type: "newcolumn", offset : 1},
          {
            type: "block",
            width: 430,
            labelWidth: 350,
            list: [{
              type: "settings",
              labelWidth: 145,
              offsetLeft: 0,
              inputWidth: 145,
              offsetTop: 0
            }, {
              type: "label",
              label: "<img src='asserts/imgs/step2.png' align='middle'/> Fill in Router & Account # <span style='font-size:10px;color:red;'>(found on the bottom of the check)<span>",
              labelWidth: 430
            }, {
              type: "block",
              width: 470,
              labelWidth: 470,
              list: [{
                type: "input",
                name: "billing_routingnumber",
                label: "Routing #",
                tooltip: "type the Routing number",
                required: true,
                info: true,
                validate: 'NotEmpty',
                mask_to_use: "integer",
                labelWidth: 105,
                inputWidth: 105
              }, {
                type: "newcolumn",
                offset: 10
              }, {
                type: "input",
                name: "billing_account_number",
                label: "Checking account #",
                tooltip: "type the checking account",
                required: true,
                info: true,
                validate: 'NotEmpty',
                mask_to_use: "integer"
              }, {
                type: "newcolumn",
                offset: 10
              }, {
                type: "input",
                name: "billing_account_number_confirm",
                label: "Confirm account #",
                tooltip: "type the checking account again",
                required: true,
                info: true,
                validate: 'NotEmpty',
                mask_to_use: "integer"
              }, {
                type: "hidden",
                name: "signature_saved",
                value: "unsaved"
              }, {
                type: "hidden",
                name: "signature_image_name_file",
                value: "none"
              }]
            }]
          }
        ]
      },



      {
        type: "newcolumn",
        offset: 20
      },


      {
        type: "block",
        width: 300,
        list: [{
          type: "block",
          width: 280,
          list: [{
              type: "label",
              label: "<img src='asserts/imgs/step3.png' align='middle'/> Sign the Check",
              labelWidth: 280,
              offsetTop: 0
            }, {
              type: "block",
              width: 300,
              inputWidth: "auto",
              list: [{
                  type: "container",
                  name: "sign_container",
                  label: "",
                  inputWidth: 300,
                  inputHeight: 130
                }
                //{type: "button", name: "btn", value: "Create Signature"}
              ]
            }

          ]
        }, {
          type: "block",
          width: 300,
          list: [{
            type: "label",
            label: "<img src='asserts/imgs/step4.png' align='middle'/> Authorize and send payment",
            labelWidth: 300,
            offsetTop: 0
          }, {
            type: "block",
            width: 280,
            inputWidth: "auto",
            list: [{
              type: "container",
              name: "pay_now",
              label: "",
              inputWidth: 280,
              inputHeight: 20
            }]
          }]
        }]
      }



    ],

    signature: {
      BackImageUrl: "", // not mandatory, default empty
      PenCursor: "signature/pen.cur", // not mandatory, default "signature/pencil.cur"
      saving_image_path: '../signatures/',
      window: {
        icon: "sign.png",
        icon_dis: "sign.png"
      }
    }
  },


  conf_form_check: {
    template: [{
        type: "settings",
        labelWidth: 204,
        inputWidth: 230,
        position: "label-top",
        offsetTop: 3,
        offsetLeft: 0
      }, {
        type: "block",
        width: 430,
        offsetLeft: 20,
        list: [{
            type: "block",
            width: 300,
            list: [{
              type: "label",
              label: "<img src='asserts/imgs/step1.png' align='middle'/> Print out invoice to be included with check",
              labelWidth: 300,
              offsetTop: 0
            }, {
              type: "block",
              width: 300,
              inputWidth: "auto",
              list: [{
                  type: "container",
                  name: "invoice_generate",
                  label: "",
                  inputWidth: 300,
                  inputHeight: 100
                }
                //{type: "button", name: "btn", value: "Create Signature"}
              ]
            }]
          },
          //{type: "newcolumn", offset : 1},
          {
            type: "block",
            width: 430,
            labelWidth: 350,
            list: [{
              type: "settings",
              labelWidth: 145,
              offsetLeft: 0,
              inputWidth: 145,
              offsetTop: 0
            }, {
              type: "label",
              label: "<img src='asserts/imgs/step2.png' align='middle'/> Create envelope with following address",
              labelWidth: 430
            }, {
              type: "block",
              width: 300,
              inputWidth: "auto",
              list: [{
                  type: "container",
                  name: "agency_address",
                  label: "",
                  inputWidth: 300,
                  inputHeight: 100
                }
                //{type: "button", name: "btn", value: "Create Signature"}
              ]
            }]
          }
        ]
      },



      {
        type: "newcolumn",
        offset: 20
      },


      {
        type: "block",
        width: 300,
        list: [{
          type: "block",
          width: 280,
          list: [{
              type: "label",
              label: "<img src='asserts/imgs/step3.png' align='middle'/> Put check and invoice in envelope and enter information in",
              labelWidth: 280,
              offsetTop: 0
            }, {
              type: "block",
              width: 280,
              labelWidth: 280,
              list: [{
                  type: "settings",
                  offsetLeft: 10
                }, {
                  type: "input",
                  name: "billing_checknumber",
                  label: "Enter check number",
                  tooltip: "type the check number",
                  required: true,
                  validate: 'NotEmpty',
                  mask_to_use: "integer"
                },

                //{type: "input", name : "billing_checkamount", label: "Enter check amount", tooltip : "type the check amount", required: true, validate: 'NotEmpty'}
              ]
            }

          ]
        }, {
          type: "block",
          width: 300,
          offsetTop: 10,
          list: [{
            type: "label",
            label: "<img src='asserts/imgs/step4.png' align='middle'/> Press 'make payment' button and send payment in",
            labelWidth: 300,
            offsetTop: 0
          }, {
            type: "block",
            width: 280,
            inputWidth: "auto",
            list: [{
              type: "container",
              name: "pay_now",
              label: "",
              inputWidth: 280,
              inputHeight: 20
            }]
          }]
        }]
      }



    ]
  },


  conf_wire_transfer: {
    template: [{
        type: "settings",
        labelWidth: 204,
        inputWidth: 230,
        position: "label-top",
        offsetTop: 3,
        offsetLeft: 0
      }, {
        type: "block",
        width: 430,
        offsetLeft: 20,
        list: [{
            type: "block",
            width: 300,
            list: [{
              type: "label",
              label: "<img src='asserts/imgs/step1.png' align='middle'/> Create Wire Transfer with the following information",
              labelWidth: 300,
              offsetTop: 0
            }, {
              type: "block",
              width: 300,
              inputWidth: "auto",
              list: [{
                  type: "container",
                  name: "agency_information",
                  label: "",
                  inputWidth: 300,
                  inputHeight: 100
                }
                //{type: "button", name: "btn", value: "Create Signature"}
              ]
            }]
          },
          //{type: "newcolumn", offset : 1},
          {
            type: "block",
            width: 430,
            labelWidth: 350,
            list: [{
                type: "settings",
                labelWidth: 145,
                offsetLeft: 0,
                inputWidth: 260,
                offsetTop: 0
              }, {
                type: "label",
                label: "<img src='asserts/imgs/step2.png' align='middle'/> Enter in the following information",
                labelWidth: 430
              }, {
                type: "block",
                width: 280,
                labelWidth: 280,
                list: [{
                    type: "settings",
                    offsetLeft: 10,
                    labelWidth: 280
                  }, {
                    type: "calendar",
                    name: "billing_datetransfer",
                    label: "Date of wire transfer",
                    tooltip: "type the date of wire transferr",
                    required: true,
                    validate: 'NotEmpty',
                    calendarPosition: 'right',
                    enableTime: false,
                    readonly: true
                  },

                  {
                    type: "input",
                    name: "billing_transferamount",
                    label: "Amount in wire transfer",
                    tooltip: "type amount in wire transfer",
                    required: true,
                    validate: 'NotEmpty',
                    mask_to_use: "currency"
                  }
                ]
              }


            ]
          }
        ]
      },



      {
        type: "newcolumn",
        offset: 20
      },


      {
        type: "block",
        width: 300,
        list: [{
          type: "block",
          width: 280,
          list: [
            // {type: "label", label: "<img src='asserts/imgs/step3.png' align='middle'/> Press the 'make payment' button to notify about wire transfer", labelWidth: 280,offsetTop: 0},
            {
              type: "label",
              label: "<img src='asserts/imgs/step2.png' align='middle'/> Press the 'make payment' button to notify about wire transfer",
              labelWidth: 280,
              offsetTop: 0
            }, {
              type: "block",
              width: 280,
              inputWidth: "auto",
              list: [{
                type: "container",
                name: "pay_now",
                label: "",
                inputWidth: 280,
                inputHeight: 20
              }]
            }
          ]
        }]
      }
    ]
  },



  conf_paypal: {
    template: [{
      type: "settings",
      labelWidth: 100,
      inputWidth: 200,
      position: "label-left",
      offsetTop: 5,
      offsetLeft: 5
    }, {
      type: "label",
      width: 340,
      label: "Paypal login information",
      labelWidth: 340,
      list: [{
          type: "input",
          name: "plogin_email",
          label: "E-mail / user",
          tooltip: "type your e-mail or username for paypal",
          required: true,
          info: true,
          validate: 'NotEmpty,ValidEmail',
          value: ""
        }, {
          type: "password",
          name: "plogin_password",
          label: "Password",
          tooltip: "type your password",
          required: true,
          info: true,
          validate: 'NotEmpty',
          value: ""
        }, {
          type: "button",
          name: "make_payment_ballance",
          value: "make payment",
          width: 300
        },

        {
          type: "hidden",
          name: "invoice_number",
          value: ''
        }, {
          type: "hidden",
          name: "pay_for_desc",
          value: '',
          validate: 'NotEmpty'
        },
        //{type: "input", name : "amount", value : '45.00'},
        {
          type: "hidden",
          name: "currency",
          value: 'USD'
        }, {
          type: "hidden",
          name: "custom_message",
          value: 'thank you for your payment...'
        }


      ]
    }, {
      type: "newcolumn",
      offset: 20
    }, {
      type: "label",
      width: 400,
      label: "Paypal payment details",
      labelWidth: 400,
      list: [{
        type: "container",
        name: "paypal_status",
        label: "",
        inputWidth: 400,
        inputHeight: 180
      }]
    }]
  }
};