exports = typeof window === 'undefined' ? global : window;

exports.objectsAnswers = {
  alterContext: function(fn, obj) {
   return fn.call(obj)
  },

  alterObjects: function(constructor, greeting) {
     constructor.prototype.greeting = greeting
  },

  iterate: function(obj) {
    return Object.entries(obj).map((entry) => `${entry[0]}: ${entry[1]}`)
  }
};
