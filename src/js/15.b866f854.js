(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[15],{"45a1":function(t,e,s){},"5b6e":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-layout",{staticClass:"primary-layoutx",attrs:{id:"primaryLayoutx",view:t.view,stylex:"overflow-y:hidden"}},[s("q-ajax-bar",{attrs:{color:t.barColor}}),s("q-header",{staticClass:"fixed-topx desktop-only",attrs:{stylex:"max-height:40px"}},[s("q-toolbar",{staticStyle:{"min-height":"40px","max-height":"40px",background:"#1c252c"},attrs:{id:"top-toolbar"}},[s("img",{staticClass:"q-ml-sm",attrs:{width:"20px",src:"/statics/icons/plain.svg"}}),s("q-btn",{staticClass:"q-ml-lg",attrs:{colorx:"#e5e5e5",color:"red",flat:"",dense:"",round:"",unelevated:"",size:"sm",icon:"fad fa-angry"}}),s("q-btn",{staticClass:"q-ml-sm",attrs:{colorx:"#e5e5e5",color:"orange",flat:"",dense:"",round:"",unelevated:"",size:"sm",icon:"fad fa-carrot"}}),s("q-space"),s("user-options",{staticClass:"q-ml-lg"})],1)],1),s("q-drawer",{staticClass:"drawer-border",staticStyle:{top:"0px!important"},attrs:{id:"primaryLayoutDrawer",mini:t.miniModeOn,behaviour:"desktop",breakpoint:t.breakpointx,"content-class":"bg-primary","content-style":{overflow:"visible!important",paddingTopx:"20px"}},on:{mouseover:t.hideTextProcess,mouseout:t.hideTextProcess},model:{value:t.menuIsOpen,callback:function(e){t.menuIsOpen=e},expression:"menuIsOpen"}},[s("div",{staticClass:"row mobile-only"},[s("div",{staticClass:"full-height text-center column",staticStyle:{width:"70px","min-height":"100vh"},attrs:{id:"mobile-draw-column"}},[s("img",{staticClass:"q-pt-sm",staticStyle:{margin:"10px auto"},attrs:{width:"30px",src:"/statics/icons/plain.svg"}}),s("br"),s("q-btn",{attrs:{colorx:"#e5e5e5",color:"red",flat:"",dense:"",round:"",unelevated:"",size:"lg",icon:"fad fa-angry"}}),s("q-btn",{attrs:{colorx:"#e5e5e5",color:"orange",flat:"",dense:"",round:"",unelevated:"",size:"lg",icon:"fad fa-carrot"}}),s("q-space"),s("user-options",{staticClass:"q-mb-lg"})],1),s("q-list",{staticClass:"col q-mt-sm text-primary",attrs:{"no-border":"",link:"","inset-":"",classx:{hideText:t.hideText}}},[s("q-item",{staticClass:"q-pa-xs q-mb-sm desktop-only",attrs:{clickable:""},on:{click:function(e){t.miniModeOn=!t.miniModeOn}}},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"menu"}})],1)],1),s("router-link",{attrs:{to:"/",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-home"}})],1),s("q-item-section",[t._v("Home")])],1)],1),t.hasModuleAccess("comms")?s("router-link",{attrs:{to:"/",exact:""}},[s("q-item",{staticClass:"q-pa-xs",attrs:{count:"3"}},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{name:"fad fa-envelope"}})],1),s("q-item-section",[t._v("Comms")])],1)],1):t._e(),t.hasModuleAccess("students")?s("router-link",{attrs:{to:"/students",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-child"}})],1),s("q-item-section",[t._v("Students")])],1)],1):t._e(),t.hasModuleAccess("smt")?s("router-link",{attrs:{to:"/smt",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-cheese-swiss"}})],1),s("q-item-section",[t._v("SMT")])],1)],1):t._e(),t.hasModuleAccess("hm")?s("router-link",{attrs:{to:"/hm",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-house-night"}})],1),s("q-item-section",[t._v("HM")])],1)],1):t._e(),t.hasModuleAccess("accounts")?s("router-link",{attrs:{to:"/accounts",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-credit-card"}})],1),s("q-item-section",[t._v("Accounts")])],1)],1):t._e(),t.hasModuleAccess("beaks")?s("router-link",{attrs:{to:"/",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-graduation-cap"}})],1),s("q-item-section",[t._v("Beaks")])],1)],1):t._e(),t.hasModuleAccess("watch")?s("router-link",{staticClass:"bg-black",attrs:{to:"/watch",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-eye"}})],1),s("q-item-section",[t._v("Watch")])],1)],1):t._e(),t.hasModuleAccess("bookings")?s("router-link",{staticClass:"bg-black",attrs:{to:"/",exact:""}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-calendar-check"}})],1),s("q-item-section",[t._v("Bookings")])],1)],1):t._e(),t.hasModuleAccess("exams")?s("router-link",{staticClass:"bg-black",attrs:{to:"/exams"}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-university"}})],1),s("q-item-section",[t._v("Exams")])],1)],1):t._e(),t.hasModuleAccess("transport")?s("router-link",{staticClass:"bg-black",attrs:{to:"/transport"}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-center q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-space-shuttle"}})],1),s("q-item-section",[t._v("Transport")])],1)],1):t._e(),t.hasModuleAccess("lab")?s("router-link",{staticClass:"bg-black",attrs:{to:"/lab"}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-left q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-flask"}})],1),s("q-item-section",[t._v("Lab")])],1)],1):t._e(),t.hasModuleAccess("admin")?s("router-link",{staticClass:"bg-black",attrs:{to:"/admin"}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-left q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-wrench"}})],1),s("q-item-section",{staticClass:"text-font"},[t._v("Admin")])],1)],1):t._e(),t.hasModuleAccess("academic")?s("router-link",{staticClass:"bg-black",attrs:{to:"/academic"}},[s("q-item",{staticClass:"q-pa-xs"},[s("q-item-section",{staticClass:"flex-left q-pl-sm",attrs:{side:""}},[s("q-icon",{attrs:{color:"font-secondary",name:"fad fa-smile"}})],1),s("q-item-section",[t._v("Academic")])],1)],1):t._e()],1)],1)]),s("q-page-container",{staticClass:"no-scroll bg-toolbar",staticStyle:{"padding-top":"0px","padding-bottom":"0px",heightx:"100vh","overflow-y":"hidden"}},[s("router-view",{on:{toggle:function(e){t.leftDrawerOpen=!t.leftDrawerOpen}}}),s("q-page-sticky",{attrs:{position:"bottom-right",offset:[18,18]}},[s("q-btn",{attrs:{round:"",color:"accent",icon:"fad fa-envelope"},on:{click:function(e){t.messages=!0}}})],1)],1)],1)},o=[],n=(s("e125"),s("4823"),s("2e73"),s("dde3"),s("76d0"),s("0c1f"),s("ae66"),s("8e9e")),r=s.n(n),i=s("c569"),c=s("9ce4"),l=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("q-btn",{staticClass:"q-mr-sm",attrs:{round:"",color:"#e5e5e5",size:t.$q.platform.is.mobile?"md":"sm",icon:"fad fa-sun",flat:""},on:{click:function(e){t.isDark=!t.isDark}}},[s("q-tooltip",[t._v("\n      Eye strain mode\n    ")])],1),t.$q.fullscreen.isCapable?s("q-btn",{staticClass:"no-border q-mr-sm no-shadow",class:{glow:t.$q.fullscreen.isActive},attrs:{icon:"fad fa-arrows-alt",flat:"",color:t.$q.fullscreen.isActive?"#4ce8f6":"#e5e5e5",size:t.$q.platform.is.mobile?"md":"sm",round:""},on:{click:function(e){return t.$q.fullscreen.toggle()}}},[s("q-tooltip",[t._v("\n        Toggle full screen\n      ")])],1):t._e(),s("q-btn",{staticClass:"q-mr-sm",attrs:{round:"",color:"#e5e5e5",size:t.$q.platform.is.mobile?"md":"sm",icon:"fad fa-bug",flat:""},on:{click:t.bug}},[s("q-tooltip",[t._v("\n      Report a bug\n    ")])],1),s("q-btn",{staticClass:"q-mr-sm",attrs:{round:"",color:"#e5e5e5",size:t.$q.platform.is.mobile?"md":"sm",icon:"fad fa-sign-out",flat:""},on:{click:t.logout}},[s("q-tooltip",[t._v("\n      Sign out\n    ")])],1),s("q-dialog",{model:{value:t.showBug,callback:function(e){t.showBug=e},expression:"showBug"}},[s("q-card",{staticClass:"bg-tertiary",staticStyle:{width:"700px","max-width":"80vw"}},[s("q-card-section",[s("div",{staticClass:"text-h6"},[t._v("Report Bug / Suggest Feature")])]),s("q-card-section",[s("q-input",{attrs:{filled:"",label:"Subject"},model:{value:t.subject,callback:function(e){t.subject=e},expression:"subject"}}),s("q-input",{staticClass:"q-mt-md",attrs:{filled:"",type:"textarea",label:"Message"},model:{value:t.message,callback:function(e){t.message=e},expression:"message"}})],1),s("q-card-section",[s("p",[t._v(t._s(t.$route.path))])]),s("q-card-actions",{staticClass:"bg-secondary text-font",attrs:{align:"right"}},[s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Forget it",outline:"",color:"font"}}),s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Send",outline:"",color:"accent"},on:{click:t.send}})],1)],1)],1)],1)},u=[],d=s("4778");function m(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,a)}return s}function p(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?m(Object(s),!0).forEach((function(e){r()(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):m(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}var f={name:"ComponentUserOptions",props:{},data:function(){return{showBug:!1,subject:"",message:""}},methods:{logout:function(){var t=this;this.$store.dispatch("user/logout").then((function(e){t.$router.replace("/login")}))},bug:function(){this.showBug=!0},send:function(){var t=this,e={userId:this.userId,path:this.$route.path,subject:this.subject,message:this.message};d["a"].post("/auth/bug",e).then((function(e){t.message="",t.subject=""})).catch((function(t){console.warn(t)}))}},computed:p({},Object(c["e"])("user",["name","userId"]),{route:function(){return this.$route.path},isDark:{get:function(){return this.$store.getters["user/isDarkMode"]},set:function(t){this.$store.dispatch("user/isDarkMode",t)}}}),components:{},created:function(){console.log(this.$route)}},q=f,h=(s("5e96"),s("2be6")),b=s("e279"),g=s.n(b),x=s("2ef0"),v=s("3aaf"),y=s("e81c"),C=s("ebe6"),k=s("965d"),w=s("bc74"),O=s("5b32"),_=s("58c0"),j=Object(h["a"])(q,l,u,!1,null,"20b5856e",null),Q=j.exports;function $(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,a)}return s}function M(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?$(Object(s),!0).forEach((function(e){r()(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):$(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}g()(j,"components",{QBtn:x["a"],QTooltip:v["a"],QDialog:y["a"],QCard:C["a"],QCardSection:k["a"],QInput:w["a"],QCardActions:O["a"]}),g()(j,"directives",{ClosePopup:_["a"]});var A={name:"LayoutDefault",data:function(){return{leftDrawerOpen:this.$q.platform.is.desktop,miniModeOn:!0,hideText:!0,router:this.$router,breakpoint:1,messages:!1,search:"",message:"",currentConversationIndex:0,conversations:[{id:1,person:"Razvan Stoenescu",avatar:"https://cdn.quasar.dev/team/razvan_stoenescu.jpeg",caption:"I'm working on Quasar!",time:"15:00",sent:!0},{id:2,person:"Dan Popescu",avatar:"https://cdn.quasar.dev/team/dan_popescu.jpg",caption:"I'm working on Quasar!",time:"16:00",sent:!0},{id:3,person:"Jeff Galbraith",avatar:"https://cdn.quasar.dev/team/jeff_galbraith.jpg",caption:"I'm working on Quasar!",time:"18:00",sent:!0},{id:4,person:"Allan Gaunt",avatar:"https://cdn.quasar.dev/team/allan_gaunt.png",caption:"I'm working on Quasar!",time:"17:00",sent:!0}]}},methods:{openURL:i["a"],hasModuleAccess:function(t){return this.getModuleAccess(t)},hideTextProcess:function(){this.hideText=!this.hideText},go:function(t){this.$router.push(t)}},computed:M({},Object(c["e"])("user",["menuIsOpen"]),{},Object(c["c"])("user",["permissions","getModuleAccess","getModuleColor"]),{},Object(c["c"])("sockets",["isConnected"]),{keymap:function(){return{"ctrl+1":this.toggle,enter:{keydown:this.hide,keyup:this.show}}},menuIsOpen:{set:function(){this.$store.commit("user/toggleMenu")},get:function(){return this.$store.getters["user/menuIsOpen"]}},barColor:function(){var t="#4ce8f6";return this.isConnected?t:"negative"},currentConversation:function(){return this.conversations[this.currentConversationIndex]},style:function(){return{height:this.$q.screen.height+"px"}},view:function(){var t=this.$q.platform.is.mobile;return console.log(t),t?"hHh lpR fFf":"HHh Lpr lFf"}}),created:function(){var t=this;this.$store.dispatch("user/tryAutoLogin").then((function(e){!0===e||t.$router.replace("/login")})),this.$q.platform.is.desktop&&(this.menuIsOpen=!0)},components:{userOptions:Q}},P=A,D=(s("7f67"),s("d152"),s("b4af")),I=s("9676"),S=s("5c88"),T=s("eb05"),B=s("f85a"),z=s("f962c"),L=s("34ff"),E=s("eb3a"),H=s("6c93"),F=s("ac9b"),R=s("66dc"),J=s("2ce9"),G=s("4f38"),N=s("dfd0"),U=s("b693"),W=Object(h["a"])(P,a,o,!1,null,null,null);e["default"]=W.exports;g()(W,"components",{QLayout:D["a"],QAjaxBar:I["a"],QHeader:S["a"],QToolbar:T["a"],QBtn:x["a"],QToolbarTitle:B["a"],QSpace:z["a"],QIcon:L["a"],QInput:w["a"],QDrawer:E["a"],QList:H["a"],QItem:F["a"],QItemSection:R["a"],QPageContainer:J["a"],QPageSticky:G["a"],QColor:N["a"],QField:U["a"]})},"5e96":function(t,e,s){"use strict";var a=s("45a1"),o=s.n(a);o.a},"6e6f":function(t,e,s){},"7f67":function(t,e,s){"use strict";var a=s("dd77"),o=s.n(a);o.a},d152:function(t,e,s){"use strict";var a=s("6e6f"),o=s.n(a);o.a},dd77:function(t,e,s){}}]);