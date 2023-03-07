app.config(['$cryptoProvider', function ($cryptoProvider) {
  $cryptoProvider.setCryptographyKey('ABCD123');
}]);