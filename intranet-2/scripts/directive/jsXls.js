app.directive('jsXls', function () {
    return {
      restrict: 'E',
      template: '<input type="file" />',
      replace: true,
      link: function (scope, element, attrs) {

        function handleSelect() {
          var files = this.files;
          for (var i = 0, f = files[i]; i != files.length; ++i) {
            var reader = new FileReader();
            var name = f.name;
            reader.onload = function (e) {
              if (!e) {
                var data = reader.content;
              } else {
                var data = e.target.result;
              }

              /* if binary string, read with type 'binary' */
              try {
                var workbook = XLS.read(data, { type: 'binary', cellDates:true, raw: false});
                var d = [];
                for (var i in workbook.SheetNames) {
//                    console.log(workbook.Sheets[workbook.SheetNames[i]])
//                    workbook.Sheets[workbook.SheetNames[i]]["FECHA INGRESO"] = XLSX.utils.format_cell(workbook.Sheets[workbook.SheetNames[i]]["FECHA INGRESO"], null, {dateNF:"YYYY-MM-DD"});
                    d = d.concat(XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]]));//{header:1, range: 1, dateNF: 'DD/MM/YYYY', cellText: false, raw: false}
                }
                if (attrs.onread) {
                  var handleRead = scope[attrs.onread];
                  if (typeof handleRead === "function") {
                    handleRead(d);
                  }
                }
              } catch (e) {
                if (attrs.onerror) {
                  var handleError = scope[attrs.onerror];
                  if (typeof handleError === "function") {
                    handleError(e);
                  }
                }
              }

              // Clear input file
              element.val('');
            };

            //extend FileReader
            if (!FileReader.prototype.readAsBinaryString) {
              FileReader.prototype.readAsBinaryString = function (fileData) {
                var binary = "";
                var pt = this;
                var reader = new FileReader();
                reader.onload = function (e) {
                  var bytes = new Uint8Array(reader.result);
                  var length = bytes.byteLength;
                  for (var i = 0; i < length; i++) {
                    binary += String.fromCharCode(bytes[i]);
                  }
                  //pt.result  - readonly so assign binary
                  pt.content = binary;
                  $(pt).trigger('onload');
                }
                reader.readAsArrayBuffer(fileData);
              }
            }

            reader.readAsBinaryString(f);

          }
        }

        element.on('change', handleSelect);
      }
    };
  });

