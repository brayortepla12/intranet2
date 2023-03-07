app.directive('pageTitle', [
        '$rootScope', '$timeout',
        function($rootScope, $timeout) {
            return {
                link: function(scope, element) {

                    var listener = function(event, toState) {
                        var title = 'Default Title';
                        if (toState.ncyBreadcrumb && toState.ncyBreadcrumb.label) title = toState.ncyBreadcrumb.label;
                        $timeout(function() {
                            element.text(title);
                        }, 0, false);
                    };
                    $rootScope.$on('$stateChangeSuccess', listener);
                }
            };
        }
    ]);

