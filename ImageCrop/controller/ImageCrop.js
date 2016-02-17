window.ImageCrop = window.ImageCrop || (function () {
  var
    com_name = "ImageCrop",
    config = {},
    win = {},
    grid = {},
    layout = {},
    form = {},
    status_bar = {},
    tool_bar = {},
    window_manager = null;

  return {

    build_window: function (_name, opt) {
      var self = this;
      _name = _name || com_name;
      //set default values form model if not opt exists
      var opt = opt || {};
      opt.left = opt.left || self.Model.defaults.window.left;
      opt.top = opt.top || self.Model.defaults.window.top;
      opt.width = opt.width || self.Model.defaults.window.width;
      opt.height = opt.height || self.Model.defaults.window.height;
      opt.title = opt.title || self.Model.defaults.window.title;
      opt.layout_pattern = opt.layout_pattern || self.Model.defaults.window.layout_pattern;
      // opt.icon = opt.icon || self.Model.defaults.window.icon;
      // opt.icon_dis = opt.icon_dis || self.Model.defaults.window.icon_dis;

      //check if the window manager object on config
      if (config.window_manager_obj) {
        window_manager = config.window_manager_obj;
      } else {
        if (window_manager === null) {
          window_manager = new dhtmlXWindows();
        }
      }

      window_manager.setImagePath(self.Model.conf_window.image_path);

      if (window_manager.isWindow(_name)) {
        win[_name].show();
        win[_name].bringToTop();
        win[_name].center();

        return;
      }
      win[_name] = window_manager.createWindow(_name, opt.left + 10, opt.top + 10, opt.width, opt.height);
      win[_name].setText(opt.title);
      win[_name].setIcon(opt.icon, opt.icon_dis);
      win[_name].center();
      win[_name].denyPark();
      win[_name].denyResize();

      win[_name].button('park').hide();
      win[_name].button('minmax1').hide();

      // Events on Windows
      win[_name].attachEvent("onClose", function (win) {
        return true;
      });
      status_bar[_name] = win[_name].attachStatusBar();
      layout[_name] = win[_name].attachLayout(opt.layout_pattern);
      layout[_name].cells("a").hideHeader();
      layout[_name].progressOn();
    },
    /**
     * [Build dhtmlx grid]
     * @param  {string} _name [name of the gird]
     * @param  {[type]} opt   [grid json]
     */
    build_grid: function (_name, opt) {
      var self = this;
      _name = _name || com_name;
      grid[_name] = layout[_name].cells("a").attachGrid(opt);
      grid[_name].setHeader(opt.headers);
      grid[_name].setColumnIds(opt.ids);
      grid[_name].setInitWidths(opt.widths);
      grid[_name].setColAlign(opt.colaligns);
      grid[_name].setColTypes(opt.coltypes);
      grid[_name].setColSorting(opt.colsorting);
      grid[_name].selMultiRows = false;
      grid[_name].enableAutoWidth(true);
      grid[_name].enableMultiselect(true);
      grid[_name].enableMultiline(true);
      grid[_name].enableAlterCss("even", "uneven");
      grid[_name].setDateFormat("%m-%d-%Y");
      grid[_name].setColumnsVisibility(opt.visibility);
      grid[_name].init();
    },
    _grid: function (_name) {

      var self = this;
      _name = _name || com_name;
      this.build_grid(_name, self.Model.conf_grid);
      grid[_name].load(config.application_path + "processors/list_img.php?physical_path=" + config.physical_site_path + "modules/boonex/avatar/data/avatarphotos/", 'json');
      layout[_name].progressOff();
      grid[_name].attachEvent("onRowDblClicked", function (rowId, cellIndex) {
        var img = grid[_name].cells(rowId, 1).getValue();
        var profile_id = grid[_name].cells(rowId, 0).getValue();

        var url =
          config.application_path + 'processors/crop.php?' +
          'profile_id=' + profile_id +
          '&img=' + config.site_url + 'modules/boonex/avatar/data/avatarphotos/' + img +
          '&iframe=crop&width=600px&height=500px';
        console.log(url);


        $.fn.prettyPhoto();
        $.prettyPhoto.open(url);

      });
    },
    start: function (c) {
      var self = this;
      config = c;
      console.log(c);
      var dependency = [
        config.dhtmlx_codebase_path + "dhtmlx_base.css",
        config.application_path + "asserts/css/style.css",
        config.dhtmlx_codebase_path + "dhtmlx.js",
        config.dhtmlx_codebase_path + "dhtmlxForm/codebase/ext/dhtmlxform_item_upload.js",
        config.dhtmlx_codebase_path + "dhtmlxForm/codebase/ext/swfobject.js",
        config.application_path + "lib/jquery.js",
        // config.application_path + "lib/PrettyPhoto/jquery.prettyPhoto.js",
        config.application_path + "lib/PrettyPhoto/prettyPhoto.css",

        config.site_url + "components/album/js/jquery.plugins.js",
        config.application_path + "lib/imgareaselect/jquery.imgareaselect.js",
        config.application_path + "lib/imgareaselect/css/imgareaselect-animated.css",
        config.application_path + "model/Model.js"
      ];

      CAIRS.onDemand.load(dependency, function () {
        self.build_window();
        self._grid();
      });
    }
  }
}());