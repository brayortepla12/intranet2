app.service('SesionFactory',
  [function () {
    this.Usuario = {}

    this.reset = () => {
      this.Usuario = {}
    }
  }])