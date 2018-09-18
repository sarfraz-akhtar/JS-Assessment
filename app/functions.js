exports = typeof window === 'undefined' ? global : window;

exports.functionsAnswers = {
  argsAsArray: function(fn, arr) {
      return fn(...arr)
  },

  speak: function(fn, obj) {
    let func = fn.bind(obj)

    return func()
  },

  functionFunction: function(str) {
       return function(str1){
         return `${str}, ${str1}`
       }
  },

  makeClosures: function(arr, fn) {
    return arr.map(item => () => fn(item))

  },

  partial: function(fn, str1, str2) {
    return function(str3) {
      return fn(str1,str2,str3)
    }
  },

  useArguments: function(...args) {
         let total = 0

    args.forEach((arg) => {
      total +=arg;
    })
    return total;
  },

  callIt: function(fn,...args) {
   console.log(args)
    return fn.call(null,...args)
  },

  partialUsingArguments: function(fn,...args) {
     return function(...args1){
       return fn.call(null,...args,...args1)
      }
  },

  curryIt: function(fn) {
    return function(a){
      return function(b){
        return function(c){
          return fn(a,b,c)
        }
      }
    }
  }
};
