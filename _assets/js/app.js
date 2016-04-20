
var WindowStatsModel = require('./models/window_stats_model');
var WindowStatsView = require('./views/window_stats_view');

var MainNavModel = require('./models/main_nav_model');
var MainNavView = require('./views/main_nav_view');

var domReady = require('domready');


module.exports = {
  // this is the the whole app initter
  blastoff: function () {
    var self = window.app = this;



    // wait for document ready to render our main view
    // this ensures the document has a body, etc.
    domReady(function () {

      app.windowStatus = new WindowStatsModel();
      app.windowStatusView = new WindowStatsView({ model : app.windowStatus });

      app.mainNavModel = new MainNavModel();
      app.mainNav = new MainNavView({ model: app.mainNavModel });

    });

  }
}

// run it
module.exports.blastoff();
