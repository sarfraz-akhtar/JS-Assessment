exports = typeof window === 'undefined' ? global : window;

exports.arraysAnswers = {
  indexOf: function(arr, item) {
    return arr.indexOf(item);
  },

  sum: function(arr) {
    var total = 0;

    arr.forEach(function(item){
      total = Number(total) + Number(item);
    });
    return total;
  },

  remove: function(arr, item) {
    return arr.filter(element => element !== item);
  },


  removeWithoutCopy: function(arr, item) {
    return arr.filter(el => el !== item)
  },

  append: function(arr, item) {
   return arr.concat(item)
  },

  truncate: function(arr) {
   arr.pop()
      return arr
  },

  prepend: function(arr, item) {
     arr.unshift(item)
      return arr
  },

  curtail: function(arr) {
      arr.shift()
      return arr
  },

  concat: function(arr1, arr2) {
         return arr1.concat(arr2)
  },

  insert: function(arr, item, index) {
       arr.splice(index,0,item)
     return arr
  },

  count: function(arr, item) {

    let count = 0;

    arr.forEach((val) => {
      if(val === item)
        ++count
    })

      return count



  },

  duplicates: function(arr) {

      let object = {}
      let result = []

        arr.forEach((item) => {
         if(!object[item]){
           object[item] = 1
      }
      else{
           ++object[item]
      }
        })
      console.log(object)
      for(let key in object ){
          if(object[key] > 1){
            result.push(parseInt(key))
          }
      }
      console.log(result)
      return result
      //return object((val,key) => {if(val > 1) return key})
  },

  square: function(arr) {
      return arr.map((item) => item * item)
  },

  findAllOccurrences: function(arr, target) {
    let keys = []
     arr.forEach((item,key) => {
       if(item === target) {
         keys.push(key)
      }
     })
      return keys
  }
};
