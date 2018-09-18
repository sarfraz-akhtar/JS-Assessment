exports = typeof window === 'undefined' ? global : window;

exports.asyncAnswers = {
  async: function(value) {
    return new Promise((resolve,reject) => resolve(value))
  },

  manipulateRemoteData: function(url) {
   return fetch(url)
       .then((response) => {
         
   })

  }
};
