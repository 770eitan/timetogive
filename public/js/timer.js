/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/timer.js":
/*!*******************************!*\
  !*** ./resources/js/timer.js ***!
  \*******************************/
/***/ (() => {

eval("window.utcToLocalTime = function utcToLocalTime(utcTimeString) {\n  var theTime = moment.utc(utcTimeString).toDate(); // moment date object in local time\n\n  var localTime = moment(theTime).format(\"llll\"); //format the moment time object to string\n\n  return localTime;\n}; // format time with moment\n\n\nwindow.addEventListener(\"load\", function () {\n  var collection = $(\".format-time\");\n  collection.each(function () {\n    try {\n      var dd = $(this).data(\"time\");\n      $(this).html(utcToLocalTime(dd));\n    } catch (error) {}\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdGltZXIuanM/MzMwZSJdLCJuYW1lcyI6WyJ3aW5kb3ciLCJ1dGNUb0xvY2FsVGltZSIsInV0Y1RpbWVTdHJpbmciLCJ0aGVUaW1lIiwibW9tZW50IiwidXRjIiwidG9EYXRlIiwibG9jYWxUaW1lIiwiZm9ybWF0IiwiYWRkRXZlbnRMaXN0ZW5lciIsImNvbGxlY3Rpb24iLCIkIiwiZWFjaCIsImRkIiwiZGF0YSIsImh0bWwiLCJlcnJvciJdLCJtYXBwaW5ncyI6IkFBQUFBLE1BQU0sQ0FBQ0MsY0FBUCxHQUF3QixTQUFTQSxjQUFULENBQXdCQyxhQUF4QixFQUF1QztBQUMzRCxNQUFJQyxPQUFPLEdBQUdDLE1BQU0sQ0FBQ0MsR0FBUCxDQUFXSCxhQUFYLEVBQTBCSSxNQUExQixFQUFkLENBRDJELENBQ1Q7O0FBQ2xELE1BQUlDLFNBQVMsR0FBR0gsTUFBTSxDQUFDRCxPQUFELENBQU4sQ0FBZ0JLLE1BQWhCLENBQXVCLE1BQXZCLENBQWhCLENBRjJELENBRVg7O0FBRWhELFNBQU9ELFNBQVA7QUFDSCxDQUxELEMsQ0FPQTs7O0FBQ0FQLE1BQU0sQ0FBQ1MsZ0JBQVAsQ0FBd0IsTUFBeEIsRUFBZ0MsWUFBWTtBQUN4QyxNQUFJQyxVQUFVLEdBQUdDLENBQUMsQ0FBQyxjQUFELENBQWxCO0FBQ0FELEVBQUFBLFVBQVUsQ0FBQ0UsSUFBWCxDQUFnQixZQUFZO0FBQ3hCLFFBQUk7QUFDQSxVQUFJQyxFQUFFLEdBQUdGLENBQUMsQ0FBQyxJQUFELENBQUQsQ0FBUUcsSUFBUixDQUFhLE1BQWIsQ0FBVDtBQUNBSCxNQUFBQSxDQUFDLENBQUMsSUFBRCxDQUFELENBQVFJLElBQVIsQ0FBYWQsY0FBYyxDQUFDWSxFQUFELENBQTNCO0FBQ0gsS0FIRCxDQUdFLE9BQU9HLEtBQVAsRUFBYyxDQUFFO0FBQ3JCLEdBTEQ7QUFNSCxDQVJEIiwic291cmNlc0NvbnRlbnQiOlsid2luZG93LnV0Y1RvTG9jYWxUaW1lID0gZnVuY3Rpb24gdXRjVG9Mb2NhbFRpbWUodXRjVGltZVN0cmluZykge1xuICAgIHZhciB0aGVUaW1lID0gbW9tZW50LnV0Yyh1dGNUaW1lU3RyaW5nKS50b0RhdGUoKTsgLy8gbW9tZW50IGRhdGUgb2JqZWN0IGluIGxvY2FsIHRpbWVcbiAgICB2YXIgbG9jYWxUaW1lID0gbW9tZW50KHRoZVRpbWUpLmZvcm1hdChcImxsbGxcIik7IC8vZm9ybWF0IHRoZSBtb21lbnQgdGltZSBvYmplY3QgdG8gc3RyaW5nXG5cbiAgICByZXR1cm4gbG9jYWxUaW1lO1xufTtcblxuLy8gZm9ybWF0IHRpbWUgd2l0aCBtb21lbnRcbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKFwibG9hZFwiLCBmdW5jdGlvbiAoKSB7XG4gICAgdmFyIGNvbGxlY3Rpb24gPSAkKFwiLmZvcm1hdC10aW1lXCIpO1xuICAgIGNvbGxlY3Rpb24uZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgIHRyeSB7XG4gICAgICAgICAgICBsZXQgZGQgPSAkKHRoaXMpLmRhdGEoXCJ0aW1lXCIpO1xuICAgICAgICAgICAgJCh0aGlzKS5odG1sKHV0Y1RvTG9jYWxUaW1lKGRkKSk7XG4gICAgICAgIH0gY2F0Y2ggKGVycm9yKSB7fVxuICAgIH0pO1xufSk7XG4iXSwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL3RpbWVyLmpzLmpzIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/timer.js\n");

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvc2Fzcy9hcHAuc2Nzcy5qcyIsIm1hcHBpbmdzIjoiO0FBQUEiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvc2Fzcy9hcHAuc2Nzcz9hODBiIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6W10sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/sass/app.scss\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/timer": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/js/timer.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/sass/app.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;