/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready( function() {
  $('#home').addClass('active');
});
function Element(data, html, events) {
  this.id = data.id;
  this.data = data;
  this.html = html;
  this.events = (events) ? events : Array();
  this.rendered = false;
}

Element.prototype = {   
  render: function(parentSelector, position) {
    if (typeof position === 'undefined') {
      position = false;
    }
    
    var el = Template(this.html, this);
    if (el.length > 0) {
      switch(position) {
        case "append":
          $('#' + parentSelector).append(el)
        case "after":
          $('#' + parentSelector).after(el);
          break;
        case "before":
          $('#' + parentSelector).before(el);
          before;
        case "insertAfter":
          $('#' + parentSelector).insertAfter(el);
          break;
        case "insertBefore": 
          $('#' + parentSelector).insertBefore(el);
          break;
        default:
          $('#' + parentSelector).html(el)
      }      
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
      var eventSelector = (event.eventSelector) ? event.eventSelector : this.id;
      $('#' + eventSelector).on(event.eventType, function(e) {
        event.eventMethod(e, self);
      });
    });
  },
  extend: function(fn)
  {
    this.prototype.super = fn;
  }
}