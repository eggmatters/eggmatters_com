jQuery.cachedScript = function( url, options ) {
  // Allow user to set any option except for dataType, cache, and url
  options = $.extend( options || {}, {
  dataType: "script",
  cache: true,
  url: url
  });
  // Use $.ajax() since it is more flexible than $.getScript
  // Return the jqXHR object so we can chain callbacks
  return jQuery.ajax( options );
};

function parseForSyntax(post) {
  var match = post.match(/.*class="(.[^"]+)/);
  if (!match) {
    return false;
  }
  var attrs = match[1].split(' ');
  if (attrs[0] !== 'syntax') {
    return false;
  }
  var brush = attrs[1];
  var syntaxHighliter = new SyntaxHighlighting(brush, post, 'syntax');
  syntaxHighliter.go();


}

function SyntaxHighlighting(brush, data, parent) {
  this.syntax = brush;
  this.script = this.setScript();
  this.data = data;
  this.parent = parent;
}

SyntaxHighlighting.prototype = {
  go: function() {
    this.render();
    var self = this;
    $.getScript( "../assets/js/syntx/shCore.js")
     .done(function( script, textStatus ) {
       self.setBrush();
     })
     .fail( function(JQXHRObject, responseText) {
       console.log("failed %o, %o", JQXHRObject, responseText);
     });
  },
  setBrush: function() {
    var self = this;
    $.getScript( "../assets/js/syntx/" + this.script)
     .done(function(script) {
       SyntaxHighlighter.all();
     })
     .fail( function(JQXHRObject, responseText) {
       console.log("failed %o, %o", JQXHRObject, responseText);
     });
  },
  render: function() {
    $('#' + this.parent).html(this.data);
    var mehHtml = $('.syntax').html();
    var rendering = '<script type="syntaxhighlighter" class="brush: ' + this.syntax + '"><![CDATA[' + mehHtml + ']]</script>';
    $('.syntax').html(rendering);
  },
  setScript: function() {
    switch (this.syntax) {
      case "js":
        return "shBrushJScript.js";
        break;
      case "cpp":
        return "shBrushCpp.js";
        break;
      case "php":
        return "shBrushPhp.js";
        break;
      case "bash":
        return "shBrushBash.js";
        break;
      case "ruby":
        return "shBrushRuby.js";
        break;
      case "sql":
        return "shBrushSql.js";
        break;
    }
  }
};