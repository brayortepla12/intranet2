app.filter('toDate', function() {
  return function(items) {
    return new Date(items);
  };
});


