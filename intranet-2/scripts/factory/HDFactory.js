app.service('HDFactory',
  [function () {
    this.data = {
      HD: {},
      Sectores: null,
      Variables: null,
      HDId: null,
      TOTALC: 0
    }
    this.NUEVOSPACIENTES = 0

    this.reset = () => {
      this.NUEVOSPACIENTES = 0
      this.data = {
        HD: {},
        Sectores: null,
        Variables: null,
        HDId: null,
        TOTALC: 0
      }
    }
  }])