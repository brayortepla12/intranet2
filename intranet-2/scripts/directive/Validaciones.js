app.directive("limitToMax", function() {
  return {
    link: function(scope, element, attributes) {
      element.on("keydown keyup", function(e) {
    if (Number(element.val()) > Number(attributes.max) &&
          e.keyCode != 46 // delete
          &&
          e.keyCode != 8 // backspace
        ) {
          e.preventDefault();
          element.val(attributes.max);
        }
      });
    }
  };
});

app.directive('validateAlpha', function () {
    // return {
    //   require: 'ngModel',
    //   restrict: 'A',
    //   link: function(scope, elem, attr, ngModel) {

    //     var validator = function(value) {
    //       if (/^[A-Za-z _]*[A-Za-z][A-Za-z _]*$/.test(value)) {
    //         ngModel.$setValidity('alphanumeric', true);
    //         return value;
    //       } else {
    //         ngModel.$setValidity('alphanumeric', false);
    //         return undefined;
    //       }
    //     };
    //     ngModel.$parsers.unshift(validator);
    //     ngModel.$formatters.unshift(validator);
    //   }
    // };
    function link(scope, elem, attrs, ngModel) {
        ngModel.$parsers.push(function (viewValue) {
            var reg = /^[A-Za-z _]*[A-Za-z][A-Za-z _]*$/;
            // if view values matches regexp, update model value
            if (viewValue.match(reg)) {
                return viewValue;
            }
            // keep the model value as it is
            var transformedValue = ngModel.$modelValue;
            ngModel.$setViewValue(transformedValue);
            ngModel.$render();
            return transformedValue;
        });
    }

    return {
        restrict: 'A',
        require: 'ngModel',
        link: link
    };
});

app.directive('validateAlphaNumeric', function () {
    // return {
    //   require: 'ngModel',
    //   restrict: 'A',
    //   link: function(scope, elem, attr, ngModel) {

    //     var validator = function(value) {
    //       if (/^[A-Za-z _]*[A-Za-z][A-Za-z _]*$/.test(value)) {
    //         ngModel.$setValidity('alphanumeric', true);
    //         return value;
    //       } else {
    //         ngModel.$setValidity('alphanumeric', false);
    //         return undefined;
    //       }
    //     };
    //     ngModel.$parsers.unshift(validator);
    //     ngModel.$formatters.unshift(validator);
    //   }
    // };[A-Za-z0-9? ,_-]+
    function link(scope, elem, attrs, ngModel) {
        ngModel.$parsers.push(function (viewValue) {
            var reg = /^[A-Za-z0-9 _]*$/;
            // if view values matches regexp, update model value
            if (viewValue.match(reg)) {
                return viewValue;
            }
            // keep the model value as it is
            var transformedValue = ngModel.$modelValue;
            ngModel.$setViewValue(transformedValue);
            ngModel.$render();
            return transformedValue;
        });
    }

    return {
        restrict: 'A',
        require: 'ngModel',
        link: link
    };
});

app.directive('capitalize', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, modelCtrl) {
            var capitalize = function (inputValue) {
                if (inputValue == undefined)
                    inputValue = '';
                var capitalized = inputValue.toUpperCase();
                if (capitalized !== inputValue) {
                    modelCtrl.$setViewValue(capitalized);
                    modelCtrl.$render();
                }
                return capitalized;
            }
            modelCtrl.$parsers.push(capitalize);
            capitalize(scope[attrs.ngModel]); // capitalize initial value
        }
    };
});

app.directive('onlyDigitsNormal', function () {

    return {
        restrict: 'A',
        require: '?ngModel',
        link: function (scope, element, attrs, modelCtrl) {
            modelCtrl.$parsers.push(function (inputValue) {
                if (inputValue == undefined)
                    return '';
                var transformedInput = inputValue.replace(/[^0-9]/g, '');
                if (transformedInput !== inputValue) {
                    modelCtrl.$setViewValue(transformedInput);
                    modelCtrl.$render();
                }
                return transformedInput;
            });
        }
    };
});

app.directive("onlyDigits", function () {
    return {
        require: 'ngModel',
        link: function (scope, ele, attr, ctrl) {

            ctrl.$parsers.push(function (inputValue) {
                var pattern = new RegExp("(^[0-9]{1,9})+(\.[0-9]{1,4})?$", "g");
                if (inputValue == '')
                    return '';
                var dotPattern = /^[.]*$/;

                if (dotPattern.test(inputValue)) {
                    ctrl.$setViewValue('');
                    ctrl.$render();
                    return '';
                }

                var newInput = inputValue.replace(/[^0-9.]/g, '');
                // newInput=inputValue.replace(/.+/g,'.');

                if (newInput != inputValue) {
                    ctrl.$setViewValue(newInput);
                    ctrl.$render();
                }
                //******************************************
                //***************Note***********************
                /*** If a same function call made twice,****
                 *** erroneous result is to be expected ****/
                //******************************************
                //******************************************

                var result;
                var dotCount;
                var newInputLength = newInput.length;
                if (result = (pattern.test(newInput))) {
                    dotCount = newInput.split(".").length - 1; // count of dots present
                    if (dotCount == 0 && newInputLength > 9) { //condition to restrict "integer part" to 9 digit count
                        newInput = newInput.slice(0, newInputLength - 1);
                        ctrl.$setViewValue(newInput);
                        ctrl.$render();
                    }
                } else { //pattern failed
                    // console.log(newInput.length);

                    dotCount = newInput.split(".").length - 1; // count of dots present
                    if (newInputLength > 0 && dotCount > 1) { //condition to accept min of 1 dot
                        newInput = newInput.slice(0, newInputLength - 1);
                    }
                    if ((newInput.slice(newInput.indexOf(".") + 1).length) > 4) { //condition to restrict "fraction part" to 4 digit count only.
                        newInput = newInput.slice(0, newInputLength - 1);
                    }
                    ctrl.$setViewValue(newInput);
                    ctrl.$render();
                }

                return newInput;
            });
        }
    };
});

