(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["a796f512"],{"4bb3":function(e,t,n){"use strict";n.r(t);var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("module-index",{attrs:{icon:e.icon,module:e.module,routes:e.routes,options:e.globalOptions,active:e.activeSession},on:{set:e.setActive}})},s=[],r=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("288e"),n("8e9e")),a=n.n(r),i=n("ca92"),c=n("de53"),l=n("9ce4");function u(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),n.push.apply(n,o)}return n}function f(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(n,!0).forEach(function(t){a()(e,t,n[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):u(n).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))})}return e}var p={name:"PageLabIndex",data:function(){return{icon:"fal fa-university",module:"exams",routes:[{label:"GCSE",page:"gcse",icon:"fal fa-sort-numeric-up-alt",route:"/exams/gcse"},{label:"A Level",page:"alevel",icon:"fal fa-sort-shapes-up",route:"/exams/alevel"},{label:"Admin",page:"admin",icon:"fal fa-sliders-h-square",route:"/exams/admin"}],sessions:[],activeSession:null}},components:{moduleIndex:i["a"]},computed:{globalOptions:function(){return this.sessions.map(function(e){return{value:e.TblExamManagerCyclesID,label:e.intFormatYear+" "+e.month,active:e.intActive}})}},methods:f({},Object(l["d"])("exams",["setActiveSession"]),{setGlobalOptions:function(e){this.sessions=e,this.activeSession=this.globalOptions.find(function(e){return"1"===e.active}),this.setActive(this.activeSession)},getGlobalOptions:function(){c["a"].getSessions(this.setGlobalOptions)},setActive:function(e){this.activeSession=e.value,this.setActiveSession(e.value)}}),created:function(){this.getGlobalOptions()},mounted:function(){}},d=p,g=n("2be6"),b=Object(g["a"])(d,o,s,!1,null,null,null);t["default"]=b.exports},"584d":function(e,t,n){"use strict";var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[e.$q.fullscreen.isCapable?n("q-btn",{staticClass:"no-border q-mr-lg no-shadow text-white",class:{glow:e.$q.fullscreen.isActive},attrs:{icon:"fal fa-arrows-alt"},on:{click:function(t){return e.$q.fullscreen.toggle()}}}):e._e(),n("q-btn-dropdown",{staticClass:"no-border text-white",staticStyle:{"min-width":"60px"},attrs:{flat:"",round:"",dense:"",icon:"fal fa-user-circle","icon-rightx":"fas fa-sign-out-alt",align:"left","content-class":"bg-grey-9 text-white"}},[n("q-list",{attrs:{link:""}},[n("q-item",{attrs:{name:"uiColor"}},[n("q-toggle",{attrs:{color:"primary",label:"Eye Strain"},model:{value:e.darkUI,callback:function(t){e.darkUI=t},expression:"darkUI"}})],1),n("q-item",{attrs:{name:"logout"},nativeOn:{click:function(t){return e.logout(t)}}},[n("q-item-section",{attrs:{avater:"",color:"dark"}},[n("q-icon",{staticClass:"q-ml-md",attrs:{name:"fal fa-lg fa-sign-out-alt"}})],1),n("q-item-section",[n("q-item-label",{attrs:{label:""}},[e._v("Logout")])],1)],1)],1)],1)],1)},s=[],r=(n("ae66"),{name:"ComponentUserOptions",props:{},data:function(){return{}},methods:{logout:function(){var e=this;this.$store.dispatch("user/logout").then(function(t){e.$router.replace("/login")})}},computed:{darkUI:{get:function(){return!this.$store.getters["user/isDark"]},set:function(e){this.$store.dispatch("user/isDark",!e)}}},components:{},created:function(){}}),a=r,i=(n("cf34"),n("2be6")),c=Object(i["a"])(a,o,s,!1,null,"749a142e",null);t["a"]=c.exports},"5d11":function(e,t,n){"use strict";var o=n("b426"),s=n.n(o);s.a},b426:function(e,t,n){},bc11:function(e,t,n){},ca92:function(e,t,n){"use strict";var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:"no-scroll"},[n("q-toolbar",{class:{"bg-mc":e.isLight}},[n("q-btn",{staticClass:"no-border glow",attrs:{flat:"",round:"",dense:"",icon:e.icon,color:"primary"}}),n("q-tabs",{staticClass:"q-pt-sm q-pl-sm",class:e.isLightx?"secondary-light":"text-secondary",attrs:{"inline-label":"","active-color":e.isLightx?"primary-light":"primary",dense:"",shrink:"",align:"left"},model:{value:e.currentTab,callback:function(t){e.currentTab=t},expression:"currentTab"}},e._l(e.routes,function(t,o){return e.getPageAccess(e.module,t.page)?n("q-route-tab",{key:t.name,attrs:{default:1==o,name:t.page,icon:t.icon+" fa-mu",label:t.label,to:t.route,exact:""}}):e._e()}),1),n("q-space"),e.settingsRoute?n("q-btn",{staticClass:"no-shadow",attrs:{icon:e.settingsIcon,"text-color":e.$router.currentRoute.path===e.settingsRoute?"primary":"secondary"},on:{click:function(t){return e.$router.push(e.settingsRoute)}}}):e._e(),e.options.length>0?n("q-select",{staticClass:"no-margin text-secondary q-my-sm",class:{"bg-dark":e.isDark},staticStyle:{"min-width":"250px","max-width":"500px","border-radius":"5px"},attrs:{outlined:"",options:e.options,label:e.label,dense:"","options-dense":"","options-dark":"",dark:"","map-options":"",inverted:"",placeholder:e.placeholder,filter:"","filter-placeholder":"search",color:e.globalInterface?"primary":"warning"},model:{value:e.globalInterface,callback:function(t){e.globalInterface=t},expression:"globalInterface"}}):e._e(),n("user-options",{staticClass:"q-ml-lg"})],1),n("router-view",{class:{"bg-dark":e.isDark,"bg-white light-ui":e.isLight}})],1)},s=[],r=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("8e9e")),a=n.n(r),i=n("584d"),c=n("9ce4");function l(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),n.push.apply(n,o)}return n}function u(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?l(n,!0).forEach(function(t){a()(e,t,n[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):l(n).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))})}return e}var f={name:"ComponentModuleIndex",props:{settingsRoute:{default:null},settingsIcon:{type:String,default:"fal fa-cogs"},options:{type:Array,default:function(){return[]}},placeholder:{type:String,default:"Select"},label:{type:String,default:"Global"},icon:{type:String,default:"fal fa-bomb"},module:{retured:!0,type:String},routes:{required:!0,type:Array,default:function(){return[{label:"Test",page:"test",icon:"fal fa-bug",route:""}]}},active:null},data:function(){return{currentTab:null}},components:{userOptions:i["a"]},computed:u({},Object(c["e"])("user",["isDark","isLight"]),{},Object(c["c"])("user",["getPageAccess","getModuleColor"]),{globalInterface:{get:function(){return this.active},set:function(e){this.$emit("set",e)}}}),methods:u({},Object(c["b"])("user",["setPrimaryColor"])),mounted:function(){},created:function(){}},p=f,d=(n("5d11"),n("2be6")),g=Object(d["a"])(p,o,s,!1,null,"6d279844",null);t["a"]=g.exports},cf34:function(e,t,n){"use strict";var o=n("bc11"),s=n.n(o);s.a},de53:function(e,t,n){"use strict";var o=n("4778");t["a"]={getSessions:function(e,t){o["a"].get("/exams/sessions").then(function(t){e(t.data)}).catch(function(e){e()})},getSession:function(e,t,n){console.log(n),o["a"].get("/exams/"+n.level+"/results/"+n.sessionID).then(function(t){console.log(t.data),e(t.data)}).catch(function(e){console.log("error",e),t(e)})},getSessionCache:function(e,t,n){console.log(n),o["a"].get("/exams/cache/"+n.level+"/results/"+n.sessionID).then(function(t){console.log(t.data.statistics),e(t.data)}).catch(function(e){console.log("error",e),t(e)})}}}}]);