(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["3323f0d3"],{"584d":function(t,e,r){"use strict";var n=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[t.$q.fullscreen.isCapable?r("q-btn",{staticClass:"no-border q-mr-lg no-shadow text-white",class:{glow:t.$q.fullscreen.isActive},attrs:{icon:"fal fa-arrows-alt"},on:{click:function(e){return t.$q.fullscreen.toggle()}}}):t._e(),r("q-btn-dropdown",{staticClass:"no-border text-white",staticStyle:{"min-width":"60px"},attrs:{flat:"",round:"",dense:"",icon:"fal fa-user-circle","icon-rightx":"fas fa-sign-out-alt",align:"left","content-class":"bg-grey-9 text-white"}},[r("q-list",{attrs:{link:""}},[r("q-item",{attrs:{name:"uiColor"}},[r("q-toggle",{attrs:{color:"primary",label:"Eye Strain"},model:{value:t.darkUI,callback:function(e){t.darkUI=e},expression:"darkUI"}})],1),r("q-item",{attrs:{name:"logout"},nativeOn:{click:function(e){return t.logout(e)}}},[r("q-item-section",{attrs:{avater:"",color:"dark"}},[r("q-icon",{staticClass:"q-ml-md",attrs:{name:"fal fa-lg fa-sign-out-alt"}})],1),r("q-item-section",[r("q-item-label",{attrs:{label:""}},[t._v("Logout")])],1)],1)],1)],1)],1)},a=[],o=(r("ae66"),{name:"ComponentUserOptions",props:{},data:function(){return{}},methods:{logout:function(){var t=this;this.$store.dispatch("user/logout").then(function(e){t.$router.replace("/login")})}},computed:{darkUI:{get:function(){return!this.$store.getters["user/isDark"]},set:function(t){this.$store.dispatch("user/isDark",!t)}}},components:{},created:function(){}}),s=o,i=(r("cf34"),r("2be6")),l=Object(i["a"])(s,n,a,!1,null,"749a142e",null);e["a"]=l.exports},"5d11":function(t,e,r){"use strict";var n=r("b426"),a=r.n(n);a.a},b426:function(t,e,r){},bc11:function(t,e,r){},ca92:function(t,e,r){"use strict";var n=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("q-page",{staticClass:"no-scroll"},[r("q-toolbar",{class:{"bg-mc":t.isLight}},[r("q-btn",{staticClass:"no-border glow",attrs:{flat:"",round:"",dense:"",icon:t.icon,color:"primary"}}),r("q-tabs",{staticClass:"q-pt-sm q-pl-sm",class:t.isLightx?"secondary-light":"text-secondary",attrs:{"inline-label":"","active-color":t.isLightx?"primary-light":"primary",dense:"",shrink:"",align:"left"},model:{value:t.currentTab,callback:function(e){t.currentTab=e},expression:"currentTab"}},t._l(t.routes,function(e,n){return t.getPageAccess(t.module,e.page)?r("q-route-tab",{key:e.name,attrs:{default:1==n,name:e.page,icon:e.icon+" fa-mu",label:e.label,to:e.route,exact:""}}):t._e()}),1),r("q-space"),t.settingsRoute?r("q-btn",{staticClass:"no-shadow",attrs:{icon:t.settingsIcon,"text-color":t.$router.currentRoute.path===t.settingsRoute?"primary":"secondary"},on:{click:function(e){return t.$router.push(t.settingsRoute)}}}):t._e(),t.options.length>0?r("q-select",{staticClass:"no-margin text-secondary q-my-sm",class:{"bg-dark":t.isDark},staticStyle:{"min-width":"250px","max-width":"500px","border-radius":"5px"},attrs:{outlined:"",options:t.options,label:t.label,dense:"","options-dense":"","options-dark":"",dark:"","map-options":"",inverted:"",placeholder:t.placeholder,filter:"","filter-placeholder":"search",color:t.globalInterface?"primary":"warning"},model:{value:t.globalInterface,callback:function(e){t.globalInterface=e},expression:"globalInterface"}}):t._e(),r("user-options",{staticClass:"q-ml-lg"})],1),r("router-view",{class:{"bg-dark":t.isDark,"bg-white light-ui":t.isLight}})],1)},a=[],o=(r("e125"),r("4823"),r("2e73"),r("dde3"),r("76d0"),r("0c1f"),r("8e9e")),s=r.n(o),i=r("584d"),l=r("9ce4");function c(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter(function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable})),r.push.apply(r,n)}return r}function u(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?c(r,!0).forEach(function(e){s()(t,e,r[e])}):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):c(r).forEach(function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))})}return t}var d={name:"ComponentModuleIndex",props:{settingsRoute:{default:null},settingsIcon:{type:String,default:"fal fa-cogs"},options:{type:Array,default:function(){return[]}},placeholder:{type:String,default:"Select"},label:{type:String,default:"Global"},icon:{type:String,default:"fal fa-bomb"},module:{retured:!0,type:String},routes:{required:!0,type:Array,default:function(){return[{label:"Test",page:"test",icon:"fal fa-bug",route:""}]}},active:null},data:function(){return{currentTab:null}},components:{userOptions:i["a"]},computed:u({},Object(l["e"])("user",["isDark","isLight"]),{},Object(l["c"])("user",["getPageAccess","getModuleColor"]),{globalInterface:{get:function(){return this.active},set:function(t){this.$emit("set",t)}}}),methods:u({},Object(l["b"])("user",["setPrimaryColor"])),mounted:function(){},created:function(){}},f=d,p=(r("5d11"),r("2be6")),g=Object(p["a"])(f,n,a,!1,null,"6d279844",null);e["a"]=g.exports},cf34:function(t,e,r){"use strict";var n=r("bc11"),a=r.n(n);a.a},d047:function(t,e,r){"use strict";r.r(e);var n=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("module-index",{attrs:{icon:t.icon,module:t.module,routes:t.routes,default:"exgarde"}})},a=[],o=r("ca92"),s={name:"PageLabIndex",data:function(){return{icon:"fal fa-eye",module:"watch",routes:[{label:"Exgarde",page:"exgarde",icon:"fal fa-location",route:"/watch/exgarde"}]}},components:{moduleIndex:o["a"]},computed:{},methods:{},created:function(){this.$router.push("/watch/exgarde")},mounted:function(){}},i=s,l=r("2be6"),c=Object(l["a"])(i,n,a,!1,null,null,null);e["default"]=c.exports}}]);