exports = typeof window === 'undefined' ? global : window;

exports.recursionAnswers = {
  listFiles: function(data, dirName='app') {
        return function(filename){
          let index = data.files.indexOf(filename)
              if(index > -1) return index
        }
  },

  permute: function(arr) {

  },

  fibonacci: function(n) {

  },

  validParentheses: function(n) {

  }
};
