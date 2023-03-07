app.service('SOLFactory',
  [function () {
    this.data = {
      Sol: {},
      PREFIJO: ""
    }

    this.reset = () => {
      this.data = {
        Sol: {},
        PREFIJO: ""
      }
    }
  }])