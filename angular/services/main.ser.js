/* angular/services/ -> main.ser.js file */

angular.module('jsonService', ['ngResource'])
.factory('JsonService', function($resource) {
  return $resource('http://localhost/base/cms/administrator/json/allpages.json');
});