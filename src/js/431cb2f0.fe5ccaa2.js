(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["431cb2f0"],{"0082":function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"q-pa-md"},[n("div",{staticClass:"q-gutter-md row"},[n("q-select",{staticStyle:{"min-width":"300px"},attrs:{filled:"",dense:"",dark:"","use-input":"","input-debounce":"0",label:"Students",options:t.options},on:{filter:t.filterFn},scopedSlots:t._u([{key:"no-option",fn:function(){return[n("q-item",[n("q-item-section",{staticClass:"text-grey"},[t._v("\n            No Students\n          ")])],1)]},proxy:!0}]),model:{value:t.student,callback:function(e){t.student=e},expression:"student"}})],1)])},s=[],o=n("4778"),r={getStudentNames:function(t,e){o["a"].get("/students/names").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getStudent:function(t,e,n){o["a"].get("/students/details/"+n).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},i={name:"Component.Dropdowns.Students",props:{options:{type:Array,default:function(){return[]}}},data:function(){return{students:[],filter:null,studentOptions:[],student:{}}},methods:{process:function(t){this.students=t,this.studentOptions=t.map((function(t){return{value:t.id,label:t.displayName,firstName:t.firstname,lastName:t.lastname}})),this.options=this.studentOptions},filterFn:function(t,e){var n=this;e(""!==t?function(){var e=t.toLowerCase();n.options=n.studentOptions.filter((function(t){return t.label.toLowerCase().indexOf(e)>-1}))}:function(){n.options=n.studentOptions})},emit:function(t){this.$emit("change",t)}},computed:{},watch:{student:function(){r.getStudent(this.emit,null,this.student.value)}},components:{},created:function(){r.getStudentNames(this.process)}},c=i,l=(n("1df1"),n("2be6")),u=Object(l["a"])(c,a,s,!1,null,"658f0319",null);e["a"]=u.exports},"1df1":function(t,e,n){"use strict";var a=n("8394"),s=n.n(a);s.a},2382:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("module-index",{attrs:{icon:t.icon,module:t.module,routes:t.routes,default:"watch"}})},s=[],o=n("ca92"),r={name:"PageLabIndex",data:function(){return{icon:"fal fa-cheese-swiss",module:"smt",routes:[{label:"Watch",page:"watch",icon:"fal fa-eye",route:"/smt/watch"}]}},components:{moduleIndex:o["a"]},computed:{},methods:{},created:function(){},mounted:function(){}},i=r,c=n("2be6"),l=Object(c["a"])(i,a,s,!1,null,null,null);e["default"]=l.exports},4997:function(t,e,n){},8394:function(t,e,n){},a672:function(t,e,n){},ba04:function(t,e,n){"use strict";var a=n("a672"),s=n.n(a);s.a},bab0:function(t,e,n){"use strict";var a=n("4997"),s=n.n(a);s.a},ca92:function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("q-page",{staticClass:"no-scroll"},[n("q-toolbar",{class:{"bg-mc":t.isLight,"bg-dark":t.isDark}},[n("q-btn",{staticClass:"no-border glow",attrs:{flat:"",round:"",dense:"",icon:t.icon,color:"primary"}}),t.selectStudent?n("students",{on:{change:t.changeStudent}}):t._e(),n("q-tabs",{staticClass:"q-pt-sm q-pl-sm",class:t.isLightx?"secondary-light":"text-secondary",attrs:{"inline-label":"","active-color":t.isLightx?"primary-light":"primary",dense:"",shrink:"",align:"left"},model:{value:t.currentTab,callback:function(e){t.currentTab=e},expression:"currentTab"}},t._l(t.routes,(function(e,a){return t.getPageAccess(t.module,e.page)?n("q-route-tab",{key:e.name,attrs:{default:1==a,name:e.page,icon:e.icon+" fa-mu",label:e.label,to:e.route,exact:""}}):t._e()})),1),n("q-space"),t.settingsRoute?n("q-btn",{staticClass:"no-shadow",attrs:{icon:t.settingsIcon,"text-color":t.$router.currentRoute.path===t.settingsRoute?"primary":"secondary"},on:{click:function(e){return t.$router.push(t.settingsRoute)}}}):t._e(),t.options.length>0?n("q-select",{staticClass:"no-margin text-secondary q-my-sm",class:{"bg-dark":t.isDark},staticStyle:{"min-width":"250px","max-width":"500px","border-radius":"5px"},attrs:{outlined:"",options:t.options,label:t.label,dense:"","options-dense":"","options-dark":"",dark:"","map-options":"",inverted:"",placeholder:t.placeholder,filter:"","filter-placeholder":"search",color:t.globalInterface?"primary":"warning"},model:{value:t.globalInterface,callback:function(e){t.globalInterface=e},expression:"globalInterface"}}):t._e(),n("user-options",{staticClass:"q-ml-lg"})],1),n("router-view",{class:{"bg-dark":t.isDark,"bg-white light-ui":t.isLight}})],1)},s=[],o=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("8e9e")),r=n.n(o),i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[t.$q.fullscreen.isCapable?n("q-btn",{staticClass:"no-border q-mr-lg no-shadow text-white",class:{glow:t.$q.fullscreen.isActive},attrs:{icon:"fal fa-arrows-alt"},on:{click:function(e){return t.$q.fullscreen.toggle()}}}):t._e(),n("q-btn-dropdown",{staticClass:"no-border text-white",staticStyle:{"min-width":"60px"},attrs:{flat:"",round:"",dense:"",icon:"fal fa-user-circle","icon-rightx":"fas fa-sign-out-alt",align:"left","content-class":"bg-grey-9 text-white","content-style":"width:200px"}},[n("q-bar",{staticClass:"bg-primary text-black"}),n("q-list",{staticStyle:{"min-width":"200px"},attrs:{link:"",clickable:""}},[n("q-item",{attrs:{name:"bug",clickable:""},nativeOn:{click:function(e){return t.bug(e)}}},[n("q-item-section",{attrs:{avatar:"",color:"dark"}},[n("q-avatar",{staticClass:"q-ml-md",attrs:{icon:"fal fa-lg fa-bug"}})],1),n("q-item-section",{staticClass:"text-left"},[n("q-item-label",{staticClass:"text-left",attrs:{label:""}},[t._v("Bug / Features")])],1)],1),n("q-item",{attrs:{name:"uiColor",clickable:""}},[n("q-toggle",{attrs:{color:"primary",label:"Eye Strain"},model:{value:t.darkUI,callback:function(e){t.darkUI=e},expression:"darkUI"}})],1),n("q-item",{attrs:{name:"logout",clickable:""},nativeOn:{click:function(e){return t.logout(e)}}},[n("q-item-section",{attrs:{avatar:"",color:"dark"}},[n("q-avatar",{staticClass:"q-ml-md",attrs:{icon:"fal fa-lg fa-sign-out-alt"}})],1),n("q-item-section",[n("q-item-label",{attrs:{label:""}},[t._v("Logout")])],1)],1)],1)],1),n("q-dialog",{model:{value:t.showBug,callback:function(e){t.showBug=e},expression:"showBug"}},[n("q-card",{staticStyle:{width:"700px","max-width":"80vw"}},[n("q-card-section",[n("div",{staticClass:"text-h6"},[t._v("Report Bug / Suggest Feature")])]),n("q-card-section",[n("q-input",{attrs:{filled:"",label:"Subject"},model:{value:t.subject,callback:function(e){t.subject=e},expression:"subject"}}),n("q-input",{staticClass:"q-mt-md",attrs:{filled:"",type:"textarea",label:"Message"},model:{value:t.message,callback:function(e){t.message=e},expression:"message"}})],1),n("q-card-section",[n("p",[t._v(t._s(t.$route.path))])]),n("q-card-actions",{staticClass:"bg-white text-dark",attrs:{align:"right"}},[n("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Forget it",outline:""}}),n("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Send",outline:""},on:{click:t.send}})],1)],1)],1)],1)},c=[],l=(n("ae66"),n("9ce4")),u=n("4778");function d(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function f(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?d(n,!0).forEach((function(e){r()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):d(n).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var p={name:"ComponentUserOptions",props:{},data:function(){return{showBug:!1,subject:"",message:""}},methods:{logout:function(){var t=this;this.$store.dispatch("user/logout").then((function(e){t.$router.replace("/login")}))},bug:function(){this.showBug=!0},send:function(){var t=this,e={userId:this.userId,path:this.$route.path,subject:this.subject,message:this.message};u["a"].post("/auth/bug",e).then((function(e){t.message="",t.subject=""})).catch((function(t){console.warn(t)}))}},computed:f({},Object(l["e"])("user",["name","userId"]),{route:function(){return this.$route.path},darkUI:{get:function(){return!this.$store.getters["user/isDark"]},set:function(t){this.$store.dispatch("user/isDark",!t)}}}),components:{},created:function(){console.log(this.$route)}},g=p,b=(n("ba04"),n("2be6")),m=Object(b["a"])(g,i,c,!1,null,"6daddcca",null),h=m.exports,y=n("0082");function v(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,a)}return n}function w(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?v(n,!0).forEach((function(e){r()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):v(n).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var q={name:"ComponentModuleIndex",props:{selectStudent:{type:Boolean,default:!1},settingsRoute:{default:null},settingsIcon:{type:String,default:"fal fa-cogs"},options:{type:Array,default:function(){return[]}},placeholder:{type:String,default:"Select"},label:{type:String,default:"Global"},icon:{type:String,default:"fal fa-bomb"},module:{retured:!0,type:String},routes:{required:!0,type:Array,default:function(){return[{label:"Test",page:"test",icon:"fal fa-bug",route:""}]}},active:null},data:function(){return{currentTab:null}},components:{userOptions:h,Students:y["a"]},computed:w({},Object(l["e"])("user",["isDark","isLight"]),{},Object(l["c"])("user",["getPageAccess","getModuleColor"]),{globalInterface:{get:function(){return this.active},set:function(t){this.$emit("set",t)}}}),methods:w({},Object(l["b"])("user",["setPrimaryColor"]),{changeStudent:function(t){this.$emit("changeStudent",t)}}),mounted:function(){},created:function(){}},O=q,k=(n("bab0"),Object(b["a"])(O,a,s,!1,null,"3a1ca321",null));e["a"]=k.exports}}]);