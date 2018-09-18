exports = typeof window === 'undefined' ? global : window;

exports.asyncAnswers = {
  async: function(value) {
    return new Promise((resolve,reject) => resolve(value))
  },

  manipulateRemoteData: function(url) {
    return fetch(url)
        .then((response) => response.json())
        .then((data) => {
      let sortedData = data.people.sort((a,b) => {
        let nameA = a.name.toUpperCase()
          let nameB = b.name.toUpperCase()
          if(nameA < nameB) return -1;
          if(nameA > nameB) return 1;
          return 0;
      })

      return sortedData.map((item) => item.name)

          })

  }
};
