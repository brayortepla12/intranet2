app.config(["$httpProvider",function ($httpProvider) {
    $httpProvider.interceptors.push('DATAHttpInterceptor');
}]);

