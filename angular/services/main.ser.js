/* angular/services/ -> main.ser.js file */

angular.module('jsonService', ['ngResource'])
.factory('JsonService', function($resource) {
    return $resource(location.origin+'/base/cms/administrator/json/allpages.json');
});