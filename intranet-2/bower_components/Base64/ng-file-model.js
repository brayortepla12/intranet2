(function () {
    'use strict';
    angular.module('ng-file-model', [])
            .directive("ngFileModel", [function () {
                    return {
                        scope: {
                            ngFileModel: "="
                        },
                        link: function (scope, element, attributes) {
                            var isMultiple = attributes.multiple;
                            element.bind("change", function (changeEvent) {
//                    var reader = new FileReader();
//                    reader.onload = function (loadEvent) {
//                        scope.$apply(function () {
//                            scope.ngFileModel = {
//                                lastModified: changeEvent.target.files[0].lastModified,
//                                lastModifiedDate: changeEvent.target.files[0].lastModifiedDate,
//                                name: changeEvent.target.files[0].name,
//                                size: changeEvent.target.files[0].size,
//                                type: changeEvent.target.files[0].type,
//                                data: loadEvent.target.result
//                            };
//                        });
//                    }
//                    reader.readAsDataURL(changeEvent.target.files[0]);

                                if (isMultiple) {
                                    scope.ngFileModel = [];
                                } else {
                                    scope.ngFileModel = {};
                                }
                                angular.forEach(element[0].files, function (item, index) {
                                    var reader = new FileReader();
                                    reader.onload = function (loadEvent) {
                                        scope.$apply(function () {
                                            var data = {
                                                lastModified: changeEvent.target.files[index].lastModified,
                                                lastModifiedDate: changeEvent.target.files[index].lastModifiedDate,
                                                name: changeEvent.target.files[index].name,
                                                size: changeEvent.target.files[index].size,
                                                type: changeEvent.target.files[index].type,
                                                data: loadEvent.target.result
                                            }
                                            if (typeof (scope.callback) == 'function') {
                                                scope.callback(data, scope.data);
                                            }
                                            if (isMultiple) {
                                                scope.ngFileModel.push(data);
                                            } else {
                                                scope.ngFileModel = data;
                                            }
                                        });
                                    }
                                    reader.readAsDataURL(item);
                                });
                            });
                        }
                    }
                }]);
    if (typeof exports !== 'undefined') {
        exports['default'] = angular.module('ng-file-model');
        module.exports = exports['default'];
    }
})();
