/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 $(document).ready(function() {
   categoriesCollection = setCategories(categories);
   postsCollection = setPosts(posts);
   attachPostsToCategories(postsCollection, categoriesCollection);
   categoriesIterator = new RecursiveIterator(categoriesCollection, "subCategories");
   categoriesIterator.iterate(iteratorCallback);
 });

 function setCategories(categoryObjects) {
   categoriesCollection = Array();
   if (categoryObjects.length <= 0) {
     return categoriesCollection;
   }
   el = '<div class="span{this.span}" id="category_{this.id}">'
        +  '  <legend>{this.name}</legend>'
        +  '    <div id="post-list_{this.id}"></div>'
        +  '</div>';
  $.each(categoryObjects, function(idx, categoryObject) {
    category = new Category(categoryObject, el);
    categoriesCollection.push(category);
  });
  return categoriesCollection;
 }

 function setPosts(postObjects) {
   postsCollection = Array();
   if (postObjects.length <= 0) {
     return postsCollection;
   }
   el = '<div id="post_{this.id}">'
       + '  <a href="{this.href}" id="post_event_{this.id}">'
       + '  <span style="color:green;">{this.headline}</span></a>';
   $.each(postObjects, function(idx, postObject) {
     post = new Post(postObject, el);
     postsCollection.push(post);
   });
   return postsCollection;
 }

 function iteratorCallback(Iterator) {
   spanLength = 12 - parseInt(Iterator.currentDepth);
   spanLength = (spanLength <= 4) ? 4 : spanLength;
   current = Iterator.currentIteration;
   current.span = spanLength;
   if (Iterator.parentIteration) {
     current.render(Iterator.parentIteration.selector, current.el);
   } else {
     current.render('category', current.el);
   }
   if (current.postArray.length > 0) {
     $.each(current.postArray, function (idx, currentPost) {
       currentPost.render('post-list_' + currentPost.categoryId, currentPost.el);
     });
   }

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