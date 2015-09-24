
/**
 *
 * @param {type} data
 * @param {type} el
 * @param {type} events
 * @returns {Category}
 */
function Category(data, el, events) {
  this.id = data.id;
  this.name = data.category;
  this.selector = 'category_' + this.id;
  this.eventSelector = 'event_' + this.id;
  this.el = el;
  this.setEvents(events);

  if (data.subCategories) { // != null && typeof(data.subCategories) !== 'undefined') {
    this.subCategories = Array();
    var self = this;
    $.each(data.subCategories, function (idx, subCategory) {
      self.subCategories.push(new Category(subCategory, el, self.events));
    });
  } else {
    this.subCategories = null;
  }
}
/**
 *
 * @type Category
 */
Category.prototype = {
  setEvents: function(events) {
    this.events = (events) ? events : Array();
  },
  render: function(parentSelector, el) {
    el = Template(el, this);
    this.rendered = true;
    if (el.length > 0) {
      $('#' + parentSelector).append(el);
    } else {
      this.rendered = false;
    }
    if (this.events.length > 0) {
      this._bindEvents();
    }
  },
  _bindEvents: function() {
    var self = this;
    $.each(this.events, function(idx, event) {
      $('#' + self.eventSelector).on(event.eventType, function(e) {
        event.method(e, self);
      });
    });
  }

};

function RecursiveIterator(hierarchy, iterationProperty) {
  this.hierarchy = hierarchy;
  this.iterationProperty = (typeof iterationProperty !== "undefined") ? iterationProperty : null;
  this.currentIteration = null;
  this.lastIteration = null;
  this.parentIteration = null;
  this.currentDepth = null;
  this.transform = Array();
}

RecursiveIterator.prototype = {
  iterate: function(callback, current, depth, parent) {
    depth   = typeof depth   === 'undefined' ? 0 : depth;
    current = typeof current === 'undefined' ? this.hierarchy : current;
    parent  = (typeof parent === 'undefined' || !parent) ? null : parent;
    var Iterator = this;
    $.each(current, function(idx, currentIterator) {
      Iterator.currentIteration = currentIterator;
      Iterator.currentDepth = depth;
      Iterator.parentIteration = parent;
      callback(Iterator);
      if (currentIterator[Iterator.iterationProperty]) {
        Iterator.lastIteration = currentIterator;
        Iterator.iterate(callback, Iterator.currentIteration[Iterator.iterationProperty], depth + 1, currentIterator);
      } else {
        Iterator.lastIteration = currentIterator;
      }
    });
    Iterator.parentIteration = null;
  }
};

function Post(data, el, events) {
  this.id = data.id;
  this.categoryId = (data.categoryId) ? data.categoryId : null;
  this.headline = (data.headline) ? data.headline : null;
  this.postDate = (data.postDate) ? new Date(data.postDate): null;
  this.online = (parseInt(data.online) > 0) ? true : false;
  this.categoryName = (data.categoryName) ? data.categoryName : null;
  this.body = (data.body) ? data.body : null;
  this.el = el;
  this.selector = (data.selector) ? data.selector : 'post_' + this.id;
  this.eventSelector = 'event_' + this.id;
  this.href = "post.php?pid=" + this.id;
  this.setEvents(events);
}
Post.prototype = {
  setEvents: function(events) {
    this.events = (events) ? events : Array();
  },
  _bindEvents: function() {
    var self = this;
    $.each(this.events, function(idx, event) {
      $('#' + self.eventSelector).on(event.eventType, function(e) {
        event.method(e, self);
      });
    });
  },
  render: function(parentSelector, el, position) {
    el = Template(el, this);
    this.rendered = true;
    if (el.length > 0) {
      if (typeof position === 'undefined') {
        $('#' + parentSelector).after(el);
      } else {
        var escapedEl = el.replace(/'+/g, "\\'");
        var call = "$('#" + parentSelector + "')." + position + "('" + escapedEl + "')";
        eval(call);
      }
    } else {
      this.rendered = false;
    }
    if (this.events.length > 0) {
      this._bindEvents();
    }
  }

};

RegExp.quote = function(str) {
     return str.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1");
 };

function Template(temp, ObjectInstance) {
  $.each(ObjectInstance, function(key, value) {
    regexString = RegExp.quote('{this.' + key + '}');
    regex = RegExp(regexString, "g");
    temp = temp.replace(regex, value);
  });
  return temp;
}

function ErrorObject(xhrResponseText) {
  this.Error = JSON.parse(xhrResponseText);
  this.el = '';
}

ErrorObject.prototype = {
  render: function (div) {
    tab = "&nbsp;&nbsp;&nbsp;&nbsp;";
    this.el = "<pre>";
    this.el = this.el + "<b>Error:</b><br \>";
    this.el = this.el + "Caused By: " + this.Error.caller + "<br \>";
    this.el = this.el + "Msg: " + this.Error.errMsg + "<br \>";
    this.el = this.el + "Stack Trace:" + "<br \>";
    this.parseTrace(this.Error.stackTrace);
    $(div).append(this.el);
  },
  parseTrace: function (obj, sp) {
    sp = typeof sp !== 'undefined' ? sp : "";
    var self = this;
    if (typeof(obj) === 'undefined' || obj === null) {
      return;
    }
    $.each(obj, function(key, value) {
      if (typeof(value) === "object") {
        self.el = self.el + sp + key + " {<br />";
        self.parseTrace(value, sp + " ");
      } else {
        self.el = self.el + sp + key + " : " + value + "<br />";
      }
    });
    self.el += sp + "}<br />";
  }
};



