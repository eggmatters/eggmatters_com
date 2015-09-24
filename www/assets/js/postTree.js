/**
 *
 * @type type
 */


 $(document).ready(function() {
   $('#tree').addClass('active');
   categoriesCollection = setCategories(AllCategories);
   postCollection = setPosts(AllPosts);
   attachPostsToCategories(postCollection, categoriesCollection);
   categoriesIterator = new RecursiveIterator(categoriesCollection, "subCategories");
   categoriesIterator.iterate(iteratorCallback);

 });

function setCategories(categoryObjects) {
  categoriesCollection = Array();

  el = '<div id="category_{this.id}" class="collapsible">'
       + '{this.spacer}<a href="#" id="event_{this.id}">'
       + '<i class="icon-plus" id="icon_{this.id}"></i>&nbsp;{this.name}</a>'
       + '</div>';
  $.each(categoryObjects, function(index, categoryObject) {
    category = new Category(categoryObject, el);
    categoriesCollection.push(category);
  });
  return categoriesCollection;
}

function setPosts(postObjects) {
  postCollection = Array();
  el = '<div id="post_{this.id}" class="collapsible">'
       + '{this.spacer}<a href="{this.href}" id="post_event_{this.id}">'
       + '<span style="color:green;">{this.headline}</span></a>'
       + '</div>';
  $.each(postObjects, function(idx, postObject) {
    post = new Post(postObject, el);
    postCollection.push(post);
  });
  return postCollection;

}

function iteratorCallback(Iterator) {
  events = [{ 'eventType': 'click', 'method': toggleCollabsible }];
  spacer = '';
  for (i = 0; i < Iterator.currentDepth; i++) {
    spacer = spacer + '&nbsp;&nbsp;&nbsp;&nbsp;';
  }
  Iterator.currentIteration.spacer = spacer;
  Iterator.currentIteration.setEvents(events);
  if (Iterator.parentIteration) {
    Iterator.currentIteration.render(Iterator.parentIteration.selector, Iterator.currentIteration.el);
    $('#' + Iterator.currentIteration.selector).collapse('hide');
  } else {
    Iterator.currentIteration.render('category-tree', Iterator.currentIteration.el);
  }
  if (!Iterator.currentIteration[Iterator.iterationProperty] &&
          typeof Iterator.currentIteration.postArray === 'undefined') {
    $('#icon_' + Iterator.currentIteration.id).removeClass("icon-plus").addClass("icon-minus");
  }
  if ( typeof Iterator.currentIteration.postArray !== 'undefined') {
    $.each(Iterator.currentIteration.postArray, function(idx, post) {
      post.spacer = spacer + '&nbsp;&nbsp;&nbsp;&nbsp;';
      post.render('event_' + Iterator.currentIteration.id, post.el);
      $('#' + post.selector).collapse('hide');
    });
  }

}

function toggleCollabsible(e, obj) {
  e.preventDefault();
  var iconClass = $('#icon_' + obj.id).attr('class');
  if (iconClass === "icon-plus") {
    $('#icon_' + obj.id).removeClass("icon-plus").addClass("icon-minus");
  } else {
    $('#icon_' + obj.id).removeClass("icon-minus").addClass("icon-plus");
  }
  $('#' + obj.selector).children(".collapsible").collapse( 'toggle');
}

function attachPostsToCategories(postsCollection, categoriesCollection) {
  categoriesIterator = new RecursiveIterator(categoriesCollection, "subCategories");
  categoriesIterator.iterate(function (Iterator) {
    category = Iterator.currentIteration;
    postArray = findPostsByCategoryId(postsCollection, category.id);
    category.postArray = postArray;
  });
}


function findPostsByCategoryId(postsCollection, categoryId) {
  retArray = Array();
  for (i in postsCollection) {
    if (postsCollection[i].categoryId === categoryId) {
      retArray.push(postsCollection[i]);
    }
  }
  return retArray;
}