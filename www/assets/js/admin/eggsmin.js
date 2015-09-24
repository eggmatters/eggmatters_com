
$(document).ready(function() {

  var categoriesCreate = new CategoriesDropdowns(0, 'create', 'categories-create');
  var categoriesEdit = new CategoriesDropdowns(0, 'edit', 'categories-edit');
  var categoriesPost = new CategoriesDropdowns(0, 'post', 'categories-post');
  getCategoriesSelect(categoriesCreate);
  getCategoriesSelect(categoriesEdit);
  getCategoriesSelect(categoriesPost);

  $('#category-submit').click(function() {
    $('.post-error').html("");
    setNewCategory(categoriesCreate);
  });

  $('#category-post-submit').click(function() {
    $('.post-error').html("");
    editExistingPost(categoriesEdit);
  });

  $('#post-create-submit').click(function() {
    $('.post-error').html("");
    setNewPost(categoriesPost);
  });

  $('#view-comments').click(function(e) {
    //e.preventDefault();
    getComments();
    $('#comments-approval').collapse('show');
  });

  $('#post-preview-dismiss').click(function() {
    $('#post-body').collapse('hide');
  });

  $('#post-dismiss').click(function() {
    $('.post-error').html("");
    clearPostPreview();
    $('#post-preview').collapse('hide');
  });


});

function getCategoriesSelect(CategoriesDropdown) {
  var url = '/routes.php';
  var data = {
    controller: "CategoriesController",
    method: "getSubCategories",
    "category-selection": CategoriesDropdown.lastSelectedValue
  };
  $.post(url, data)
    .success(function(data) {
      var categoriesData = JSON.parse(data);
      if (categoriesData.length > 0) {
        CategoriesDropdown.setCategories(categoriesData);
        CategoriesDropdown.renderCategories(CategoriesDropdown.lastSelectedValue);
      } else {
        return true;
      }
    })
    .fail(function(jqXHR, status, err, dataType) {

  });
}

function setNewCategory(categoriesCreate) {
  var categoryName = $('#category-name').val();
  var addSub = ($('#add-sub').prop("checked")) ? true : false;
  if (categoryName.length <= 0) {
    $('.category-create-error').html("<span style='color:red'>You haven't selected a category name.</span>");
    return;
  }
  if (addSub && !categoriesCreate.lastSelectedValue) {
    $('.category-create-error').html("<span style='color:red'>You haven't selected a category to add this to.</span>");
    return;
  }
  var url = '/routes.php';
  var data = {controller: "CategoriesController"
            , method: "setCategories"
            , category_name: categoryName
            , category_selection: categoriesCreate.lastSelectedValue
            , add_sub: addSub
            , token: document.cookie.replace(/(?:(?:^|.*;\s*)eggstok\s*\=\s*([^;]*).*$)|^.*$/, "$1")
  };
  $.post(url, data)
    .success(function(data) {
      var dataObj = JSON.parse(data);
      $('.category-create-error').html("<span style='color:green'>Successfully added: " + dataObj.name + "</span>");
    })
    .fail(function(jqXHR, status, err) {
      var ErrorObj = new ErrorObject(jqXHR.responseText);
      ErrorObj.render("#errordiv");
      return true;
  });
}

function editExistingPost(categoriesEdit) {
  var url = '/routes.php';
  var data = {
    controller: "PostController"
            , method: "getPostsByCategory"
            , categoryId: categoriesEdit.lastSelectedValue
  };

  $.post(url, data)
          .success(function(data) {
    var dataObj = JSON.parse(data);
    if (dataObj) {
      dataObj.categoryName = categoriesEdit.lastSelectedText;
      displayPostsForEdit(dataObj);
    }
  })
          .fail(function(jqXHR, status, err) {
    var ErrorObj = new ErrorObject(jqXHR.responseText);
    ErrorObj.render("#errordiv");
    return true;
  });

}

function setNewPost(categoriesPost) {
  emptyPost = {headline: "",
    body: "",
    categoryId: categoriesPost.lastSelectedValue,
    categoryName: categoriesPost.lastSelectedText
  };
  showPost(emptyPost, true);
}

function showPost(postObject, status) {
  $('.post-error').html("");
  var postHandler = new PostHandler(postObject, status);
  $('#post-body').collapse('show');
  $('#headline').val(postObject.headline);
  $('#post-category-label').html(postObject.categoryName);
  $('#post-text').val(postObject.body);
  $('#post-body').removeClass("hidden");
  postHandler.bindEvents();
}

function postPreview(e, postHandler) {
  e.preventDefault();
  clearPostPreview();
  $('#post-preview').collapse('show');
  $('#post-view-category').html($('#post-category-label').val());
  $('#post-view-headline').html($('#headline').val());
  $('#post-view-body').append('<p>' + $('#post-text').val() + '</p>');
  postHandler.postObject.body = $('#post-text').val();
  postHandler.postObject.headline = $('#headline').val();
  $('pre code').each(function(i, e) {hljs.highlightBlock(e)});
}

function clearPostPreview() {
  $('#post-view-body').html("");
  $('#post-view-category').html("");
  $('#post-view-headline').html("");
}

function displayPostsForEdit(postsArray) {
  var categoryName = postsArray.categoryName;
  $(".post-list").html("<ul id='unordered-post-list'></ul>");
  var el = '<li><a class="mehtracker" id="{this.eventSelector}" href="#">{this.headline}<a></li>';
  var events = [{'eventType': 'click', 'method': loadEditPost}];
  $.each(postsArray, function(idx, postData) {
    var post = new Post(postData, el, events);
    post.render('unordered-post-list', post.el, 'append');
    post.categoryName = categoryName;
  });
}

function loadEditPost(e, postObject) {
  e.preventDefault();
  showPost(postObject, false);
}

function savePost(e, postHandler) {
  e.preventDefault();
  if (postHandler.new ) {
    saveNewPost(postHandler);
  } else {
    updatePost(postHandler);
  }
}

function saveNewPost(postHandler) {
  var url = "/routes.php";
  var data = {
    controller: "PostController",
    method: "setNewPost",
    headline: postHandler.postObject.headline,
    body: postHandler.postObject.body,
    categoryId: postHandler.postObject.categoryId
  };
  $.post(url, data)
          .success(function(data) {
    var dataObj = JSON.parse(data)[0];
    $('.post-error').html("<span style='color:green'>Successfully added: " + dataObj.headline + "</span>");
    clearPostPreview();
    $('#post-preview').collapse('hide');
  })
          .fail(function(jqXHR, status, err) {
    $('.post-error').html("<span style='color:red'>There has been an error</span>");
    var ErrorObj = new ErrorObject(jqXHR.responseText);
    ErrorObj.render("#errordiv");
    return true;
  });
}

function updatePost(postHandler) {
  var url = "/routes.php";
  var data = {
    controller: "PostController",
    method: "updatePost",
    postId: postHandler.postObject.id,
    headline: postHandler.postObject.headline,
    body: postHandler.postObject.body
  };
  $.post(url, data)
          .success(function(data) {
    var dataObj = JSON.parse(data)[0];
    $('.post-error').html("<span style='color:green'>Successfully updated: " + dataObj.headline + "</span>");
    clearPostPreview();
    $('#post-preview').collapse('hide');
  })
  .fail(function(jqXHR, status, err) {
    $('.post-error').html("<span style='color:red'>There has been an error</span>");
    var ErrorObj = new ErrorObject(jqXHR.responseText);
    ErrorObj.render("#errordiv");
    return true;
  });
}

function getComments() {
  var url = "/routes.php";
  var data = { controller: "CommentsController", method: "getCommentsForApproval"};
  $.post(url, data)
    .success( function (data) {
      var dataObj = JSON.parse(data);
      showComments(dataObj);
    })
    .fail(function(jqXHR, status, err) {
    $('.post-error').html("<span style='color:red'>There has been an error</span>");
      var ErrorObj = new ErrorObject(jqXHR.responseText);
      ErrorObj.render("#errordiv");
      return true;
    });
}

function showComments(dataObj) {
  $.each(dataObj, function (idx, comment) {
    var commentRow = new Comment(comment);
    $('#comments-pending').append(commentRow.el);
    commentRow.bindEvents();
  });
}

function CategoriesDropdowns(parentId, type, parentDiv) {
  this.categoriesArray = null;
  this.categoriesData = null;
  this.type = type;
  this.parentId = parentId;
  this.parentDiv = parentDiv;
  this.lastSelectedValue = null;
  this.lastSelectedText = null;
}

CategoriesDropdowns.prototype = {
  setCategories: function(categoriesData) {
    this.categoriesData = categoriesData;
    this.categoriesArray = new Array();
    var el = "<option value={this.id}>{this.name}</option>";
    var initialCategory = new Category({id: 0, category: "--select--"}, el);
    this.categoriesArray.push(initialCategory);
    var self = this;
    $.each(this.categoriesData, function(idx, categoryData) {
      var category = new Category(categoryData, el);
      category.selector = 'category_' + this.type + '_' + this.category.id;
      category.eventSelector = 'event_' + this.type + '_' + this.category.id;
      self.categoriesArray.push(category);
    });
  },
  renderCategories: function(parentId) {
    //Prepare and render a select box:
    this.parentId = parentId;
    var categoryClass = "categories-" + this.type + "-dropdown";
    var categoryId = "categories-" + this.type + "-select_" + this.parentId;
    var categoriesSelect = '<select class="' + categoryClass + '" id="' + categoryId + '">';
    var categoriesSelector = "categories-" + this.type + "-select_" + this.parentId;
    $('#' + this.parentDiv).append(categoriesSelect);
    $.each(this.categoriesArray, function(idx, category) {
      category.render(categoriesSelector, category.el);
      $('#' + categoriesSelector).css("display", "block");
    });
    this._bindEvent(categoriesSelector, categoryClass);
  },
  _bindEvent: function(categoriesSelector, categoryClass) {
    var self = this;
    $('#' + categoriesSelector).on("change", function(e) {
      if ($('#' + categoriesSelector).val() > 0) {
        self.lastSelectedValue = $('#' + categoriesSelector).val();
        self.lastSelectedText = $('#' + categoriesSelector + " option:selected").text();
        self._removeLowerCategories(categoryClass, e.target.id);
        getCategoriesSelect(self);
      } else {
        self._removeLowerCategories(categoryClass, e.target.id);
      }
    });
  },
  _removeLowerCategories: function(categoryClass, categoryId) {
    var found = false;
    $.each($('.' + categoryClass), function(idx, current) {
      if (found) {
        current.remove();
      } else if (current.id === categoryId) {
        found = true;
      }
    });
  }
};

function PostHandler(postObject, status) {
  this.postObject = postObject;
  this.categoryId;
  this.new = status;
  this.postEvents = [{selector: "post-preview-submit"
              , event: "click"
              , method: postPreview},
              {selector: "post-publish-submit"
              , event: "click"
              , method: savePost}
  ];
}

PostHandler.prototype = {
  bindEvents: function() {
    var self = this;
    $.each(this.postEvents, function(idx, eventObject) {
      $('#' + eventObject.selector).on(eventObject.event, function(e) {
        eventObject.method(e, self);
      });
    });
  }
};

function Comment(commentObject) {
  this.commentObject = commentObject;
  this.viewSelector = "view_" + this.commentObject.id;
  this.approvalSelector = "approve_" + this.commentObject.id;
  this.el = "<tr id='comment_" + this.commentObject.id + "'>" +
          "<td>" + this.commentObject.id + '</td>' +
          "<td>" + this.commentObject.userName + '</td>' +
          "<td>" + this.commentObject.body + '</td>' +
          "<td><a class='view' href='post.php?pid=" + this.commentObject.postId + "' id='" + this.viewSelector + "'>View</a></td>" +
          "<td><input type='button' class='btn btn-small approve' id='" + this.approvalSelector + "' value='approve'></td></tr>";
}

Comment.prototype = {
  bindEvents: function() {
    var self = this;
    $('#' + this.approvalSelector).click( function () {
      self.approveComment();
      $('#' + self.approvalSelector).addClass("disabled");
    });
    $('#' + this.viewSelector).click( function () {
      console.log("Navigating to: " + $('#' + self.viewSelector).attr("href"));
      document.location.replace($('#' + self.viewSelector).attr("href"));
    });
  },
  approveComment: function () {
    var url = "/routes.php";
    var data = {
      token: document.cookie.replace(/(?:(?:^|.*;\s*)eggstok\s*\=\s*([^;]*).*$)|^.*$/, "$1"),
      controller: "CommentsController",
      method: "approveComment",
      commentId: this.commentObject.id
    };
    $.post(url, data)
      .success( function (data) {
        alert("comment approved");
      })
      .fail(function(jqXHR, status, err) {
        var ErrorObj = new ErrorObject(jqXHR.responseText);
        ErrorObj.render("#errordiv");
        return true;
      });
  }
}