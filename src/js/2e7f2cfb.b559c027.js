(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["2e7f2cfb"],{"4ece":function(t,e,n){"use strict";n.r(e);var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("module-index",{attrs:{icon:t.icon,module:t.module,routes:t.routes,settingsRoute:t.globalSettingsRoute,settingsIcon:t.globalSettingsIcon,options:t.globalOptions,active:t.activeSession},on:{set:t.setActive}})},o=[],r=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("288e"),n("8e9e")),a=n.n(r),i=n("ca92"),c=n("9d37"),l=n("9ce4");function u(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,s)}return n}function f(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?u(n,!0).forEach((function(e){a()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):u(n).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var p={name:"PageTransportIndex",data:function(){return{icon:"fal fa-space-shuttle",module:"transport",routes:[{label:"Taxis",page:"taxis",icon:"fal fa-car-side",route:"/transport/taxis"},{label:"Coaches",page:"coaches",icon:"fal fa-bus",route:"/transport/coaches"},{label:"Accounts",page:"accounts",icon:"fal fa-money",route:"/transport/accounts"}],globalSettingsRoute:"/transport/sessions",globalSettingsIcon:"fal fa-cogs",sessions:[],activeSession:null}},components:{moduleIndex:i["a"]},computed:{globalOptions:function(){return this.sessions.map((function(t){return{value:t.id,label:t.description,sublabel:1===t.isActive?"ACTIVE":""}}))}},methods:f({},Object(l["d"])("transport",["setActiveSession"]),{setGlobalOptions:function(t){this.sessions=t;var e=this.globalOptions.find((function(t){return"ACTIVE"===t.sublabel}));e&&(this.activeSession=e,this.setActiveSession(e.value))},getGlobalOptions:function(){c["a"].getSessions(this.setGlobalOptions)},setActive:function(t){this.activeSession=t.value,this.setActiveSession(this.activeSession)}}),created:function(){this.getGlobalOptions(),this.$router.push("/transport/taxis")},mounted:function(){}},d=p,g=n("2be6"),b=Object(g["a"])(d,s,o,!1,null,null,null);e["default"]=b.exports},"584d":function(t,e,n){"use strict";var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[t.$q.fullscreen.isCapable?n("q-btn",{staticClass:"no-border q-mr-lg no-shadow text-white",class:{glow:t.$q.fullscreen.isActive},attrs:{icon:"fal fa-arrows-alt"},on:{click:function(e){return t.$q.fullscreen.toggle()}}}):t._e(),n("q-btn-dropdown",{staticClass:"no-border text-white",staticStyle:{"min-width":"60px"},attrs:{flat:"",round:"",dense:"",icon:"fal fa-user-circle","icon-rightx":"fas fa-sign-out-alt",align:"left","content-class":"bg-grey-9 text-white"}},[n("q-list",{attrs:{link:""}},[n("q-item",{attrs:{name:"uiColor"}},[n("q-toggle",{attrs:{color:"primary",label:"Eye Strain"},model:{value:t.darkUI,callback:function(e){t.darkUI=e},expression:"darkUI"}})],1),n("q-item",{attrs:{name:"logout"},nativeOn:{click:function(e){return t.logout(e)}}},[n("q-item-section",{attrs:{avater:"",color:"dark"}},[n("q-icon",{staticClass:"q-ml-md",attrs:{name:"fal fa-lg fa-sign-out-alt"}})],1),n("q-item-section",[n("q-item-label",{attrs:{label:""}},[t._v("Logout")])],1)],1)],1)],1)],1)},o=[],r=(n("ae66"),{name:"ComponentUserOptions",props:{},data:function(){return{}},methods:{logout:function(){var t=this;this.$store.dispatch("user/logout").then((function(e){t.$router.replace("/login")}))}},computed:{darkUI:{get:function(){return!this.$store.getters["user/isDark"]},set:function(t){this.$store.dispatch("user/isDark",!t)}}},components:{},created:function(){}}),a=r,i=(n("cf34"),n("2be6")),c=Object(i["a"])(a,s,o,!1,null,"749a142e",null);e["a"]=c.exports},"5d11":function(t,e,n){"use strict";var s=n("b426"),o=n.n(s);o.a},"9d37":function(t,e,n){"use strict";var s=n("4778");e["a"]={getSessions:function(t,e){s["a"].get("/transport/sessions").then((function(e){t(e.data)})).catch((function(t){t()}))},getSession:function(t,e,n){s["a"].get("/transport/sessions/"+n).then((function(e){t(e.data)})).catch((function(t){t()}))},activateSession:function(t,e,n){s["a"].post("/transport/sessions/activate/"+n).then((function(e){t(e.data)})).catch((function(t){t()}))},deleteSession:function(t,e,n){s["a"].delete("/transport/sessions/"+n).then((function(e){t(e.data)})).catch((function(t){t()}))},putSession:function(t,e,n){s["a"].put("/transport/sessions",n).then((function(e){t(e.data)})).catch((function(t){t()}))},postSession:function(t,e,n){s["a"].post("/transport/sessions",n).then((function(e){t(e.data)})).catch((function(t){t()}))}}},b426:function(t,e,n){},bc11:function(t,e,n){},ca92:function(t,e,n){"use strict";var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("q-page",{staticClass:"no-scroll"},[n("q-toolbar",{class:{"bg-mc":t.isLight}},[n("q-btn",{staticClass:"no-border glow",attrs:{flat:"",round:"",dense:"",icon:t.icon,color:"primary"}}),n("q-tabs",{staticClass:"q-pt-sm q-pl-sm",class:t.isLightx?"secondary-light":"text-secondary",attrs:{"inline-label":"","active-color":t.isLightx?"primary-light":"primary",dense:"",shrink:"",align:"left"},model:{value:t.currentTab,callback:function(e){t.currentTab=e},expression:"currentTab"}},t._l(t.routes,(function(e,s){return t.getPageAccess(t.module,e.page)?n("q-route-tab",{key:e.name,attrs:{default:1==s,name:e.page,icon:e.icon+" fa-mu",label:e.label,to:e.route,exact:""}}):t._e()})),1),n("q-space"),t.settingsRoute?n("q-btn",{staticClass:"no-shadow",attrs:{icon:t.settingsIcon,"text-color":t.$router.currentRoute.path===t.settingsRoute?"primary":"secondary"},on:{click:function(e){return t.$router.push(t.settingsRoute)}}}):t._e(),t.options.length>0?n("q-select",{staticClass:"no-margin text-secondary q-my-sm",class:{"bg-dark":t.isDark},staticStyle:{"min-width":"250px","max-width":"500px","border-radius":"5px"},attrs:{outlined:"",options:t.options,label:t.label,dense:"","options-dense":"","options-dark":"",dark:"","map-options":"",inverted:"",placeholder:t.placeholder,filter:"","filter-placeholder":"search",color:t.globalInterface?"primary":"warning"},model:{value:t.globalInterface,callback:function(e){t.globalInterface=e},expression:"globalInterface"}}):t._e(),n("user-options",{staticClass:"q-ml-lg"})],1),n("router-view",{class:{"bg-dark":t.isDark,"bg-white light-ui":t.isLight}})],1)},o=[],r=(n("e125"),n("4823"),n("2e73"),n("dde3"),n("76d0"),n("0c1f"),n("8e9e")),a=n.n(r),i=n("584d"),c=n("9ce4");function l(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,s)}return n}function u(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?l(n,!0).forEach((function(e){a()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):l(n).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var f={name:"ComponentModuleIndex",props:{settingsRoute:{default:null},settingsIcon:{type:String,default:"fal fa-cogs"},options:{type:Array,default:function(){return[]}},placeholder:{type:String,default:"Select"},label:{type:String,default:"Global"},icon:{type:String,default:"fal fa-bomb"},module:{retured:!0,type:String},routes:{required:!0,type:Array,default:function(){return[{label:"Test",page:"test",icon:"fal fa-bug",route:""}]}},active:null},data:function(){return{currentTab:null}},components:{userOptions:i["a"]},computed:u({},Object(c["e"])("user",["isDark","isLight"]),{},Object(c["c"])("user",["getPageAccess","getModuleColor"]),{globalInterface:{get:function(){return this.active},set:function(t){this.$emit("set",t)}}}),methods:u({},Object(c["b"])("user",["setPrimaryColor"])),mounted:function(){},created:function(){}},p=f,d=(n("5d11"),n("2be6")),g=Object(d["a"])(p,s,o,!1,null,"6d279844",null);e["a"]=g.exports},cf34:function(t,e,n){"use strict";var s=n("bc11"),o=n.n(s);o.a}}]);