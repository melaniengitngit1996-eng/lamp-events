"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Registration_DisclosurePromptComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      isAllowing: false,
      display: false,
      dialogVisible: true
    };
  },
  props: {
    event: {
      required: false
    }
  },
  mounted: function mounted() {
    console.log('test');
  },
  methods: {
    validateAuthorization: function validateAuthorization() {
      if (!this.isAllowing) this.display = true;else this.dialogVisible = false;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=template&id=39fef781&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=template&id=39fef781& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
      _c = _vm._self._c;

  return _c("el-dialog", {
    attrs: {
      visible: _vm.dialogVisible,
      "custom-class": "responsive-dialog",
      "close-on-click-modal": false,
      "close-on-press-escape": false,
      "show-close": false,
      "destroy-on-close": false
    },
    on: {
      "update:visible": function updateVisible($event) {
        _vm.dialogVisible = $event;
      }
    }
  }, [_c("span", {
    staticClass: "content"
  }, [_c("div", {
    staticClass: "m-0"
  }, [_c("el-checkbox", {
    model: {
      value: _vm.isAllowing,
      callback: function callback($$v) {
        _vm.isAllowing = $$v;
      },
      expression: "isAllowing"
    }
  }), _vm._v("  I authorize LAMP Church to collect, use, and retain the personal information in this form for the purpose of attending " + _vm._s(_vm.event.name) + " and in the pursuit of any legal interests of the church.\n            "), _vm.display ? _c("span", {
    staticClass: "error"
  }, [_vm._v("\n                Please check if you want to continue\n            ")]) : _vm._e()], 1)]), _vm._v(" "), _c("span", {
    staticClass: "dialog-footer",
    attrs: {
      slot: "footer"
    },
    slot: "footer"
  }, [_c("el-button", {
    on: {
      click: function click($event) {
        return _vm.validateAuthorization();
      }
    }
  }, [_vm._v("Continue")])], 1)]);
};

var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/Registration/DisclosurePromptComponent.vue":
/*!****************************************************************************!*\
  !*** ./resources/js/components/Registration/DisclosurePromptComponent.vue ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _DisclosurePromptComponent_vue_vue_type_template_id_39fef781___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DisclosurePromptComponent.vue?vue&type=template&id=39fef781& */ "./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=template&id=39fef781&");
/* harmony import */ var _DisclosurePromptComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DisclosurePromptComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _DisclosurePromptComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _DisclosurePromptComponent_vue_vue_type_template_id_39fef781___WEBPACK_IMPORTED_MODULE_0__.render,
  _DisclosurePromptComponent_vue_vue_type_template_id_39fef781___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Registration/DisclosurePromptComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DisclosurePromptComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DisclosurePromptComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DisclosurePromptComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=template&id=39fef781&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=template&id=39fef781& ***!
  \***********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_DisclosurePromptComponent_vue_vue_type_template_id_39fef781___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_DisclosurePromptComponent_vue_vue_type_template_id_39fef781___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_DisclosurePromptComponent_vue_vue_type_template_id_39fef781___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./DisclosurePromptComponent.vue?vue&type=template&id=39fef781& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Registration/DisclosurePromptComponent.vue?vue&type=template&id=39fef781&");


/***/ })

}]);