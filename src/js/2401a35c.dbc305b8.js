(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["2401a35c"],{"08e9":function(e,t,n){"use strict";var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:"no-scroll toolbar-page"},[n("q-toolbar",{class:{"text-white bg-toolbar":e.isDark,"text-black bg-white-3":e.isLight},attrs:{dense:"",shrink:"",classx:"text-white shadow-2 rounded-borders narrowx justify"}},[n("q-tabs",{staticClass:"tbp-tabs",attrs:{dense:"",shrink:"","active-color":e.isLight?"#011b48":"primary"},model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.elements,(function(t){return n("div",{key:t.name},[t.menu?e._e():n("q-tab",{attrs:{label:t.label,name:t.name,icon:t.icon}}),t.menu?n("q-btn",{attrs:{flat:"",size:"sm",label:t.label,icon:t.icon?t.icon:"fal fa-caret-down","text-color":e.isDark?"white":"primary"}},[n("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-grey-9 text-white","auto-close":""}},[n("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},e._l(t.menu,(function(t){return n("q-item",{key:t.name,attrs:{clickable:""},nativeOn:{click:function(n){return e.clickMenu(t)}}},[n("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[n("q-icon",{attrs:{size:"20px",name:t.icon}})],1),n("q-item-section",[n("q-item-label",[e._v(e._s(t.label))])],1)],1)})),1)],1)],1):e._e()],1)})),0),n("q-space"),e._t("side")],2),n("q-tab-panels",{model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.tabPanels,(function(t){return n("q-tab-panel",{key:t.name,attrs:{name:t.name}},[n(t.component,{tag:"component",on:{close:e.close}})],1)})),1)],1)},r=[],a=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("c880"),n("6650")),i=n.n(a),s=n("9ce4");function c(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function l(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?c(n,!0).forEach((function(t){i()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):c(n).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var u={name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:l({},Object(s["e"])("user",["isDark","isLight"]),{tabPanels:function(){var e=[];return this.elements.forEach((function(t){t.menu?t.menu.forEach((function(t){e.push({name:t.name,component:t.component})})):e.push({name:t.name,component:t.component})})),e}}),methods:{close:function(){this.selectedTab=this.default},clickMenu:function(e){e.name&&(this.selectedTab=e.name),e.event&&this.$emit(e.event)}},created:function(){this.selectedTab=this.default}},d=u,f=(n("b0d4"),n("2be6")),m=Object(f["a"])(d,o,r,!1,null,null,null);t["a"]=m.exports},"141e":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return(0,o.withParams)({type:"and"},(function(){for(var e=this,n=arguments.length,o=new Array(n),r=0;r<n;r++)o[r]=arguments[r];return t.length>0&&t.reduce((function(t,n){return t&&n.apply(e,o)}),!0)}))};t.default=r},"14be":function(e,t,n){"use strict";var o=n("26cd"),r=n.n(o);r.a},"1b3c":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"minValue",min:e},(function(t){return!(0,o.req)(t)||(!/\s/.test(t)||t instanceof Date)&&+t>=+e}))};t.default=r},"26cd":function(e,t,n){},"30fa":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e,t){return(0,o.withParams)({type:"between",min:e,max:t},(function(n){return!(0,o.req)(n)||(!/\s/.test(n)||n instanceof Date)&&+e<=+n&&+t>=+n}))};t.default=r},"3a81":function(e,t,n){},"4a5e":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"requiredUnless",prop:e},(function(t,n){return!!(0,o.ref)(e,this,n)||(0,o.req)(t)}))};t.default=r},5358:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"sameAs",eq:e},(function(t,n){return t===(0,o.ref)(e,this,n)}))};t.default=r},5428:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=(0,o.regex)("integer",/^-?[0-9]*$/);t.default=r},"56f6":function(e,t,n){"use strict";var o=n("eb8c"),r=n.n(o);r.a},"5fc9":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return(0,o.withParams)({type:"or"},(function(){for(var e=this,n=arguments.length,o=new Array(n),r=0;r<n;r++)o[r]=arguments[r];return t.length>0&&t.reduce((function(t,n){return t||n.apply(e,o)}),!1)}))};t.default=r},"61cc":function(e,t,n){},6720:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=(0,o.regex)("numeric",/^[0-9]*$/);t.default=r},"6b68":function(e,t,n){"use strict";(function(e){function n(e){return n="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"===typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(e)}Object.defineProperty(t,"__esModule",{value:!0}),t.withParams=void 0;var o="undefined"!==typeof window?window:"undefined"!==typeof e?e:{},r=function(e,t){return"object"===n(e)&&void 0!==t?t:e((function(){}))},a=o.vuelidate?o.vuelidate.withParams:r;t.withParams=a}).call(this,n("4701"))},"6bce":function(e,t,n){"use strict";var o=n("bb0b"),r=n.n(o);r.a},"79a4":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=(0,o.withParams)({type:"required"},o.req);t.default=r},8025:function(e,t,n){"use strict";var o=n("921c"),r=n.n(o);r.a},"830c":function(e,t,n){"use strict";var o=n("61cc"),r=n.n(o);r.a},"89a2":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),Object.defineProperty(t,"alpha",{enumerable:!0,get:function(){return o.default}}),Object.defineProperty(t,"alphaNum",{enumerable:!0,get:function(){return r.default}}),Object.defineProperty(t,"numeric",{enumerable:!0,get:function(){return a.default}}),Object.defineProperty(t,"between",{enumerable:!0,get:function(){return i.default}}),Object.defineProperty(t,"email",{enumerable:!0,get:function(){return s.default}}),Object.defineProperty(t,"ipAddress",{enumerable:!0,get:function(){return c.default}}),Object.defineProperty(t,"macAddress",{enumerable:!0,get:function(){return l.default}}),Object.defineProperty(t,"maxLength",{enumerable:!0,get:function(){return u.default}}),Object.defineProperty(t,"minLength",{enumerable:!0,get:function(){return d.default}}),Object.defineProperty(t,"required",{enumerable:!0,get:function(){return f.default}}),Object.defineProperty(t,"requiredIf",{enumerable:!0,get:function(){return m.default}}),Object.defineProperty(t,"requiredUnless",{enumerable:!0,get:function(){return p.default}}),Object.defineProperty(t,"sameAs",{enumerable:!0,get:function(){return h.default}}),Object.defineProperty(t,"url",{enumerable:!0,get:function(){return b.default}}),Object.defineProperty(t,"or",{enumerable:!0,get:function(){return g.default}}),Object.defineProperty(t,"and",{enumerable:!0,get:function(){return v.default}}),Object.defineProperty(t,"not",{enumerable:!0,get:function(){return y.default}}),Object.defineProperty(t,"minValue",{enumerable:!0,get:function(){return w.default}}),Object.defineProperty(t,"maxValue",{enumerable:!0,get:function(){return P.default}}),Object.defineProperty(t,"integer",{enumerable:!0,get:function(){return O.default}}),Object.defineProperty(t,"decimal",{enumerable:!0,get:function(){return q.default}}),t.helpers=void 0;var o=j(n("a54d")),r=j(n("9a0b")),a=j(n("6720")),i=j(n("30fa")),s=j(n("b408")),c=j(n("ea72")),l=j(n("8f91")),u=j(n("90c2")),d=j(n("d082")),f=j(n("79a4")),m=j(n("da96")),p=j(n("4a5e")),h=j(n("5358")),b=j(n("bf12")),g=j(n("5fc9")),v=j(n("141e")),y=j(n("90e9")),w=j(n("1b3c")),P=j(n("b897")),O=j(n("5428")),q=j(n("e925")),_=k(n("d8f6"));function k(e){if(e&&e.__esModule)return e;var t={};if(null!=e)for(var n in e)if(Object.prototype.hasOwnProperty.call(e,n)){var o=Object.defineProperty&&Object.getOwnPropertyDescriptor?Object.getOwnPropertyDescriptor(e,n):{};o.get||o.set?Object.defineProperty(t,n,o):t[n]=e[n]}return t.default=e,t}function j(e){return e&&e.__esModule?e:{default:e}}t.helpers=_},"8f91":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:":";return(0,o.withParams)({type:"macAddress"},(function(t){if(!(0,o.req)(t))return!0;if("string"!==typeof t)return!1;var n="string"===typeof e&&""!==e?t.split(e):12===t.length||16===t.length?t.match(/.{2}/g):null;return null!==n&&(6===n.length||8===n.length)&&n.every(a)}))};t.default=r;var a=function(e){return e.toLowerCase().match(/^[0-9a-f]{2}$/)}},"90c2":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"maxLength",max:e},(function(t){return!(0,o.req)(t)||(0,o.len)(t)<=e}))};t.default=r},"90e9":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"not"},(function(t,n){return!(0,o.req)(t)||!e.call(this,t,n)}))};t.default=r},"921c":function(e,t,n){},"9a0b":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=(0,o.regex)("alphaNum",/^[a-zA-Z0-9]*$/);t.default=r},a249:function(e,t,n){},a54d:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=(0,o.regex)("alpha",/^[a-zA-Z]*$/);t.default=r},a86c:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o="web"===Object({NODE_ENV:"production",CLIENT:!0,SERVER:!1,DEV:!1,PROD:!0,MODE:"spa",API_URL:"/api/v1/public/",SOCKET:"wss://adazmq.marlboroughcollege.org/wss",VUE_ROUTER_MODE:"history",VUE_ROUTER_BASE:"/",APP_URL:"undefined"}).BUILD?n("6b68").withParams:n("480e").withParams,r=o;t.default=r},b0d4:function(e,t,n){"use strict";var o=n("a249"),r=n.n(o);r.a},b408:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=/(^$|^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$)/,a=(0,o.regex)("email",r);t.default=a},b897:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"maxValue",max:e},(function(t){return!(0,o.req)(t)||(!/\s/.test(t)||t instanceof Date)&&+t<=+e}))};t.default=r},bb0b:function(e,t,n){},bf12:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[\/?#]\S*)?$/i,a=(0,o.regex)("url",r);t.default=a},d082:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"minLength",min:e},(function(t){return!(0,o.req)(t)||(0,o.len)(t)>=e}))};t.default=r},d75e:function(e,t,n){"use strict";var o=n("3a81"),r=n.n(o);r.a},d8f6:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),Object.defineProperty(t,"withParams",{enumerable:!0,get:function(){return o.default}}),t.regex=t.ref=t.len=t.req=void 0;var o=r(n("a86c"));function r(e){return e&&e.__esModule?e:{default:e}}function a(e){return a="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"===typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},a(e)}var i=function(e){if(Array.isArray(e))return!!e.length;if(void 0===e||null===e)return!1;if(!1===e)return!0;if(e instanceof Date)return!isNaN(e.getTime());if("object"===a(e)){for(var t in e)return!0;return!1}return!!String(e).length};t.req=i;var s=function(e){return Array.isArray(e)?e.length:"object"===a(e)?Object.keys(e).length:String(e).length};t.len=s;var c=function(e,t,n){return"function"===typeof e?e.call(t,n):n[e]};t.ref=c;var l=function(e,t){return(0,o.default)({type:e},(function(e){return!i(e)||t.test(e)}))};t.regex=l},da96:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=function(e){return(0,o.withParams)({type:"requiredIf",prop:e},(function(t,n){return!(0,o.ref)(e,this,n)||(0,o.req)(t)}))};t.default=r},e5bb:function(e,t,n){"use strict";n.r(t);var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("toolbar-page",{attrs:{elements:e.elements,default:"roles"}})},r=[],a=n("08e9"),i=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("crud",{ref:"crud",attrs:{data:e.data,api:e.api,columns:e.columns,search:"",canNew:"",canDelete:"",canEdit:"",inlineEdit:"",sidebarComponents:e.sidebarComponents,sidebarWidth:"8",indicator:"isDefault"}})],1)},s=[],c=n("d612"),l=n("4778"),u={getRoles:function(e){l["a"].get("/admin/access/roles").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},getModuleTree:function(e){l["a"].get("/admin/access/module/tree").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},getRolePages:function(e,t){l["a"].get("/admin/access/roles/pages/"+e).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},getRoleUsers:function(e,t,n){n.roleID&&l["a"].get("/admin/access/roles/users/"+n.roleID).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},getRoleNewUsers:function(e,t,n){n.roleID&&l["a"].get("/admin/access/roles/users/new/"+n.roleID).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},postRoleUsers:function(e,t,n,o){o.roleID&&l["a"].post("/admin/access/roles/users/"+o.roleID,n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},deleteRoleUsers:function(e,t,n,o){l["a"].delete("/admin/access/roles/users/"+o.roleID+"/"+n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},putRolePages:function(e,t){l["a"].put("/admin/access/roles/pages",{id:e,pages:t}).then((function(e){})).catch((function(e){console.log(e)}))},deleteRole:function(e,t,n){l["a"].delete("/admin/access/roles/"+n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},deleteRoleUser:function(e,t,n){l["a"].delete("/admin/access/roles/user/"+e+"/"+t).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},postRole:function(e,t,n){l["a"].post("/admin/access/roles",n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},putRole:function(e,t,n){l["a"].put("/admin/access/roles",n).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},postUser:function(e,t,n){l["a"].post("/admin/access/roles/user",{roleId:e,userId:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},getAllUsers:function(e,t){l["a"].get("/admin/access/roles/allusers").then((function(e){t(e.data)})).catch((function(e){console.log(e)}))}},d=function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.selectedRow?n("div",{staticClass:"row"},[e.selectedRow.id&&e.tree?n("q-tree",{attrs:{nodes:e.tree,color:"primary","default-expand-all":"",ticked:e.ticked,"tick-strategy":e.tickStrategy,filter:e.tickFilter,"node-key":"id","label-key":"name",dark:""},on:{"update:ticked":function(t){e.ticked=t}}}):e._e()],1):e._e()},f=[],m={name:"ComponentCrudDetails",data:function(){return{tree:null,ticked:null,tickStrategy:"leaf-filtered",tickFilter:null,moduleIcon:{students:"fal fa-child",transport:"fal fa-space-shuttle"}}},methods:{getPages:function(){var e=this;u.getRolePages(this.roleID,(function(t){e.ticked=t}))}},computed:{roleID:function(){return this.selectedRow.id}},watch:{selectedRow:function(){console.log(this.selectedRow),this.getPages()},ticked:function(){u.putRolePages(this.roleID,this.ticked)}},components:{},created:function(){var e=this;u.getModuleTree((function(t){e.tree=t})),this.getPages()},props:{selectedRow:{required:!0}}},p=m,h=(n("56f6"),n("2be6")),b=Object(h["a"])(p,d,f,!1,null,"8c883b4e",null),g=b.exports,v=function(){var e=this,t=e.$createElement,n=e._self._c||t;return e.selectedRow?n("div",{staticClass:"row"},[n("crud",{ref:"crud",attrs:{api:e.api,columns:e.columns,search:"",canNew:"",canDelete:"",newComponent:e.newComponent,sortBy:"lastName"}})],1):e._e()},y=[],w=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"row",staticStyle:{"min-height":"400px","min-width":"500px"}},[n("crud",{ref:"crud",staticClass:"col",attrs:{columns:e.columns,search:"",sortBy:"lastName",dataOverride:e.staffList,hidePagination:"",rowsPerPage:"10",indicator:"exists"},on:{selected:e.selected}})],1),n("div",{staticClass:"row q-mt-md full-width"},[n("q-btn",{directives:[{name:"hotkey",rawName:"v-hotkey.once",value:{enter:e.submit},expression:"{'enter': submit}",modifiers:{once:!0}}],staticClass:"text-primary col",attrs:{outline:"",dark:"",icon:"fal fa-check",label:"Create New"},nativeOn:{click:function(t){return e.submit(t)}}})],1)])},P=[],O=n("230f"),q=n.n(O),_={name:"ComponentLabCrudAdvancedNew",props:{rowKey:{default:"id"},data:{required:!0,type:Array},api:{required:!0,type:Object},newUsers:[]},data:function(){return{staffList:[],columns:[{name:"id",label:"",field:"string",hidden:!0},{name:"lastName",label:"Last Name",field:"lastname",type:"string",align:"left"},{name:"firstName",label:"First Name",field:"firstname",type:"string",align:"left"}],dark:!0}},methods:{submit:function(){u.postRoleUsers(this.success,this.error,this.newUsers,this.api.parameters)},success:function(e){var t;console.log(e),(t=this.data).push.apply(t,q()(e))},error:function(){this.$q.notify("Oops, something went wrong")},selected:function(e){this.newUsers=e}},computed:{},watch:{},components:{Crud:c["a"]},created:function(){var e=this;u.getRoleNewUsers((function(t){e.staffList=t}),null,this.api.parameters)}},k=_,j=(n("8025"),Object(h["a"])(k,w,P,!1,null,"b53ec866",null)),C=j.exports,x={name:"AdminAccessRolesUsers",data:function(){return{api:{get:u.getRoleUsers,parameters:{roleID:null},post:u.postRoleUsers,delete:u.deleteRoleUsers},columns:[{name:"id",label:"",field:"string",hidden:!0},{name:"lastName",label:"Last Name",field:"lastname",type:"string",align:"left"},{name:"firstName",label:"First Name",field:"firstname",type:"string",align:"left"}],newComponent:C}},methods:{},computed:{roleID:function(){return this.selectedRow.id}},watch:{selectedRow:function(){this.selectedRow.id&&(this.api={get:u.getRoleUsers,parameters:{roleID:this.selectedRow.id},put:u.putRole,post:u.postRole,delete:u.deleteRole})}},components:{Crud:c["a"]},created:function(){this.selectedRow.id&&(this.api={get:u.getRoleUsers,parameters:{roleID:this.selectedRow.id},post:u.postRoleUsers,delete:u.deleteRoleUsers})},props:{selectedRow:{required:!0}}},R=x,M=(n("d75e"),Object(h["a"])(R,v,y,!1,null,"0d3892fe",null)),D=M.exports,N=n("89a2"),$={name:"AdminAccessRoles",data:function(){return{api:{get:u.getRoles,put:u.putRole,post:u.postRole,delete:u.deleteRole},columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"name",type:"string",align:"left",validations:{required:N["required"],minLength:Object(N["minLength"])(4)},editable:!0}],sidebarComponents:[{title:"Users",name:"Users",component:D},{title:"Pages",name:"Pages",component:g}],showForm:!0}},computed:{},components:{Crud:c["a"]},created:function(){}},I=$,S=(n("14be"),Object(h["a"])(I,i,s,!1,null,"0420d52a",null)),A=S.exports,T=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:"q-ml-xl"},[n("div",{staticClass:"row"},[n("div",{staticClass:"col-8 q-mt-xl"},[n("q-icon",{attrs:{name:"fal fa-plus-circle",color:"primary"},nativeOn:{click:function(t){return e.newModule(t)}}}),n("q-tree",{staticClass:"q-mt-lg",attrs:{nodes:e.tree,color:"primary","default-expand-all":"","node-key":"id","label-key":"name",dark:""},scopedSlots:e._u([{key:"default-header",fn:function(t){return n("div",{},[t.node.module?n("div",[n("span",{staticClass:"q-mx-sm"},[n("span",{staticClass:"inline-block text-center",staticStyle:{"min-width":"20px","min-height":"20px"}},[n("q-icon",{style:{color:t.node.color},attrs:{name:""===t.node.icon?"fal fa-empty-set":t.node.icon}})],1),n("q-popup-edit",{on:{save:function(n){return e.saveModuleIcon(t.node.moduleId,t.node.icon)}},model:{value:t.node.icon,callback:function(n){e.$set(t.node,"icon",n)},expression:"prop.node.icon"}},[n("q-field",[n("q-input",{model:{value:t.node.icon,callback:function(n){e.$set(t.node,"icon",n)},expression:"prop.node.icon"}})],1)],1)],1),n("span",[e._v("\n              "+e._s(t.node.name)+"\n              "),n("q-popup-edit",{attrs:{dark:""},on:{save:function(n){return e.saveModuleName(t.node.moduleId,t.node.name)}},model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}},[n("q-field",[n("q-input",{model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}})],1)],1)],1),n("span",{staticClass:"text-weight-thin no-shadow"},[n("q-btn",{staticClass:"no-shadow",staticStyle:{width:"20px"},attrs:{icon:"fal fa-palette fa-xs"},on:{click:function(n){return n.stopPropagation(),e.setColor(t.node.moduleId,t.node.name,t.node.color)}}}),n("q-icon",{staticClass:"q-mt-xs q-mr-xs q-ml-sm",attrs:{name:"fal fa-trash",color:"red"},nativeOn:{click:function(n){return e.deleteModule(t.node.moduleId)}}}),n("q-btn",{staticClass:"no-shadow",staticStyle:{width:"20px"},attrs:{icon:"fal fa-plus fa-xs"},on:{click:function(n){return n.stopPropagation(),e.newPage(t.node.moduleId)}}})],1)]):n("div",{},[n("span",{staticClass:"row text-primary"},[e._v("\n              "+e._s(t.node.name)+"\n              "),n("div",[n("q-icon",{staticClass:"q-mx-md",attrs:{name:"fa fa-edit",color:"primary"}}),n("q-popup-edit",{attrs:{dark:""},on:{save:function(n){return e.savePage(t.node.id,t.node.name)}},model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}},[n("q-field",[n("q-input",{model:{value:t.node.name,callback:function(n){e.$set(t.node,"name",n)},expression:"prop.node.name"}})],1)],1)],1),n("q-icon",{staticClass:"q-mt-xs q-mr-md",attrs:{name:"fal fa-trash",color:"red"},nativeOn:{click:function(n){return e.deletePage(t.node.id)}}}),n("q-icon",{staticClass:"q-mt-xs",attrs:{name:"fal fa-info-circle"}},[n("q-tooltip",{attrs:{anchor:"center right",self:"center left",offset:[10,10]}},[n("div",e._l(t.node.roles,(function(t){return n("p",{key:t.id,staticClass:"capitalize"},[e._v("\n                      "+e._s(t.name)+"\n                    ")])})),0)])],1)],1)])])}}])})],1),n("div",{staticClass:"col-4"})])])},U=[],E=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("6650")),L=n.n(E),z=n("9ce4"),V={deleteModule:function(e,t){l["a"].delete("/admin/access/structure/module/"+e).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},postModuleIcon:function(e,t,n){l["a"].post("/admin/access/structure/module/icon",{id:e,icon:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},postModuleName:function(e,t,n){l["a"].post("/admin/access/structure/module/name",{id:e,name:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},postModule:function(e,t){l["a"].post("/admin/access/structure/module",{name:e}).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},postPage:function(e,t,n){l["a"].post("/admin/access/structure/module/page",{moduleId:e,name:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},putPage:function(e,t,n){l["a"].put("/admin/access/structure/module/page",{id:e,name:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))},deletePage:function(e,t){l["a"].delete("/admin/access/structure/module/page/"+e).then((function(e){t(e.data)})).catch((function(e){console.log(e)}))},postColor:function(e,t,n){l["a"].post("/admin/access/structure/module/color",{id:e,color:t}).then((function(e){n(e.data)})).catch((function(e){console.log(e)}))}},F=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-dialog",{attrs:{"stack-buttons":"","prevent-close":"",dark:""},scopedSlots:e._u([{key:"buttons",fn:function(t){return[n("q-btn",{attrs:{label:"Save Color",stylexx:"background:black",stylex:{color:e.currentColor}},on:{click:e.saveColor}}),n("q-btn",{attrs:{flat:"",label:"No thanks"},on:{click:e.cancelMe}})]}}]),model:{value:e.showDialog,callback:function(t){e.showDialog=t},expression:"showDialog"}},[n("span",{attrs:{slot:"title"},slot:"title"},[e._v("Choose Color")]),n("span",{staticClass:"capitalize",attrs:{slot:"message"},slot:"message"},[e._v(e._s(e.nameText))]),n("div",{staticClass:"no-scroll",attrs:{slot:"body"},slot:"body"},[n("q-color",{staticStyle:{"max-width":"300px"},attrs:{"format-model":"hex","no-parent-field":""},model:{value:e.currentColor,callback:function(t){e.currentColor=t},expression:"currentColor"}})],1)])},B=[],Z=(n("c880"),{data:function(){return{}},props:["show","color","name"],computed:{showDialog:{get:function(){return this.show},set:function(e){this.$emit("update:show",!1)}},currentColor:{get:function(){return this.color},set:function(e){this.$emit("update:color",e)}},nameText:function(){return this.name}},methods:{cancelMe:function(){this.$emit("update:show",!1)},saveColor:function(){console.log(this.currentColor),this.$emit("saveColor",this.currentColor),this.$emit("update:show",!1)}},created:function(){}}),K=Z,J=Object(h["a"])(K,F,B,!1,null,null,null),Y=J.exports;function W(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function G(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?W(n,!0).forEach((function(t){L()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):W(n).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var H={name:"ComponentAdminAccessRoles",props:{},data:function(){return{showColorPicker:!1,tree:[],ticked:[],tickStrategy:"leaf-filtered",tickFilter:null,role:null,roles:[],moduleIcon:{students:"fal fa-child",transport:"fal fa-space-shuttle"},colorModuleId:null,colorModulecolor:null}},methods:{saveColor:function(e){var t=this;V.postColor(this.colorModuleId,this.colorModuleColor,(function(){t.getTree()}))},setColor:function(e,t,n){this.colorModuleColor=n,this.colorModuleName=t,this.colorModuleId=e,this.showColorPicker=!0},deleteModule:function(e){var t=this;this.$q.dialog({title:"Confirm",message:"Delete this Module? The consequences of an error are grave.",ok:"Yes",cancel:"NO!!!"}).onOK((function(){V.deleteModule(e,(function(){t.$q.notify("Deleted"),t.getTree()}))})).onDismiss((function(){t.$q.notify("Cancelled")}))},getTree:function(){var e=this;u.getModuleTree((function(t){console.log(t),e.tree=t}))},saveModuleIcon:function(e,t){var n=this;V.postModuleIcon(e,t,(function(){n.getTree()}))},saveModuleName:function(e,t){var n=this;V.postModuleName(e,t,(function(){n.getTree()}))},newModule:function(){var e=this;this.$q.dialog({title:"New Module",message:"Name?",prompt:{model:"",type:"text"},cancel:!0,color:"primary"}).onOk((function(t){V.postModule(t,(function(){e.getTree()}))})).onDismiss((function(){e.$q.notify("Cancelled")}))},newPage:function(e){var t=this;this.$q.dialog({title:"New Page",message:"Name?",prompt:{model:"",type:"text"},cancel:!0,color:"primary"}).onOk((function(n){console.log("ok"),V.postPage(e,n,(function(){t.getTree()}))})).onDismiss((function(){t.$q.notify("Cancelled")}))},savePage:function(e,t){var n=this;V.putPage(e,t,(function(){n.getTree()}))},deletePage:function(e){var t=this;this.$q.dialog({title:"Confirm",message:"Delete this Page? The consequences of an error are grave.",ok:"Yes",cancel:"NO!!!"}).onOk((function(){V.deletePage(e,(function(){t.$q.notify("Deleted"),t.getTree()}))})).onDismiss((function(){t.$q.notify("Cancelled")}))}},computed:G({},Object(z["c"])("user",["permissions"]),{test:function(){return console.log(this.showColorPicker),this.showColorPicker}}),watch:{ticked:function(){console.log(this.ticked)},role:function(){}},components:{colorPicker:Y},created:function(){var e=this;u.getRoles((function(t){e.roles=t})),this.getTree()}},Q=H,X=(n("830c"),Object(h["a"])(Q,T,U,!1,null,"a22eb124",null)),ee=X.exports,te=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:" row"},[n("div",{staticClass:"col-3 q-mr-md"},[n("q-list",{attrs:{dark:"",separator:""}},[n("q-item-label",{staticClass:"row q-pt-sm",staticStyle:{"border-bottom":"1px solid grey",height:"50px"},attrs:{header:""}},[n("span",{staticClass:"col"},[e._v("Roles")])]),e._l(e.roles,(function(t){return n("q-item",{key:t.value,class:{"bg-dark":t.value==e.role},nativeOn:{click:function(n){return e.clickRole(t.value,t.label)}}},[n("q-item-section",{staticClass:"capitalize",attrs:{label:t.label}}),n("q-item-side",{attrs:{right:""}},[n("q-btn",{attrs:{flat:"",round:"",dense:"",icon:"more_vert"}},[n("q-menu",[n("q-list",{attrs:{link:""}},[n("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}]},[n("q-item-label",[e._v("Rename")])],1),n("q-item",{directives:[{name:"close-overlay",rawName:"v-close-overlay"}],nativeOn:{click:function(n){return e.deleteRole(t.value)}}},[n("q-item-label",[e._v("Delete")])],1)],1)],1)],1)],1)],1)}))],2)],1),n("div",{staticClass:"col"},[n("q-btn-group",[n("q-btn",{attrs:{color:"dark",label:"users",icon:"timeline"}}),n("q-btn",{attrs:{color:"dark",icon:"visibility"}}),n("q-btn",{attrs:{color:"dark",icon:"update"}})],1),this.role?n("q-tabs",{staticClass:"hidden",attrs:{color:"grey-10","underline-color":"primary"}},[n("q-tab",{attrs:{slot:"title",default:"",iconx:"fal fa-users fa-sm",label:"Current",name:"current"},slot:"title"}),n("q-tab",{attrs:{slot:"title",label:"Add",iconx:"fal fa-plus fa-sm",exact:"",name:"add"},slot:"title"}),n("q-tab-panel",{attrs:{name:"current"}}),n("q-tab-panel",{attrs:{name:"add"}})],1):e._e()],1)])},ne=[];function oe(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function re(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?oe(n,!0).forEach((function(t){L()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):oe(n).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var ae={name:"ComponentAdminAccessRoles",props:{},data:function(){return{role:null,roleName:"",roles:[]}},methods:{clickRole:function(e,t){this.role=e,this.roleName=t},getRoles:function(e){var t=this;u.getRoles((function(n){t.roles=n,e&&(t.role=e)}))}},computed:re({},Object(z["c"])("user",["permissions"])),watch:{role:function(){var e=this;u.getRoleUsers(this.role,(function(t){e.roleUsers=t}))},ticked:function(){u.putRolePages(this.role,this.ticked)}},components:{},created:function(){this.getRoles()}},ie=ae,se=(n("6bce"),Object(h["a"])(ie,te,ne,!1,null,"de09fc58",null)),ce=se.exports,le={name:"PageAdminAccess",data:function(){return{elements:[{name:"roles",label:"Roles",component:A,shortcut:"r"},{name:"structure",label:"Structure",component:ee,shortcut:"s"},{name:"users",label:"Users",component:ce,shortcut:"u"}]}},components:{toolbarPage:a["a"]},methods:{refresh:function(){}}},ue=le,de=Object(h["a"])(ue,o,r,!1,null,null,null);t["default"]=de.exports},e925:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=(0,o.regex)("decimal",/^[-]?\d*(\.\d+)?$/);t.default=r},ea72:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=n("d8f6"),r=(0,o.withParams)({type:"ipAddress"},(function(e){if(!(0,o.req)(e))return!0;if("string"!==typeof e)return!1;var t=e.split(".");return 4===t.length&&t.every(a)}));t.default=r;var a=function(e){if(e.length>3||0===e.length)return!1;if("0"===e[0]&&"0"!==e)return!1;if(!e.match(/^\d+$/))return!1;var t=0|+e;return t>=0&&t<=255}},eb8c:function(e,t,n){}}]);